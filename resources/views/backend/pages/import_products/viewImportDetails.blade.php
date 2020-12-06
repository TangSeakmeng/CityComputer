@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/import_products/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.ckeditor.com/4.15.1/basic/ckeditor.js"></script>
@endsection

@section('content')
    <div id="transactionContainer">
        <div class="transactionHeader">
            <h1 class="display-4">View Transaction</h1>
        </div>

        <div class="transactionContent mt-4">
            <p>Import ID     : {{ $data->id }}</p>
            <p>Import Date   : {{ $data->import_date }}</p>
            <p>Invoice Number: {{ $data->invoice_number }}</p>
            <p>Import Total  : {{ $data->import_total }} $</p>
            <p>Supplier Name : {{ $data->supplier_name }}</p>
            <p>Managed By    : {{ $data->username }}</p>
            <p>Created At    : {{ $data->created_at }}</p>

            <div>
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Product ID</th>
                        <th scope="col">Product Barcode</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Import Price</th>
                        <th scope="col">Import Quantity</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($data_2 as $item)
                            <tr>
                                <th scope="row">{{ $item->product_id }}</th>
                                <td>{{ $item->product_barcode }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->import_price }}</td>
                                <td>{{ $item->import_quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div>
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Product ID</th>
                        <th scope="col">Product Barcode</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Product Serial Number</th>
                        <th scope="col">Status</th>
                        <th scope="col">Note</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data_3 as $item)
                        <tr>
                            <th scope="row">{{ $item->product_id }}</th>
                            <td>{{ $item->product_barcode }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->serial_number }}</td>
                            <td>{{ $item->status}}</td>
                            <td>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal" onclick="btnViewNote({{ $item->product_id }}, '{{ $item->serial_number }}')">
                                    View Note
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success alert-block mt-2" id="addSerialNumberSuccessMessage" style="display: none"></div>
                    <div class="alert alert-danger alert-block mt-2" id="addSerialNumberErrorMessage" style="display: none"></div>

                    <div class="formContainer">
                        <div class="form-group">
                            <textarea name="txtDescription" id="txtDescription"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="btnUpdateNote()">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('link_js')
    <script>
        CKEDITOR.replace( 'txtDescription' );

        let PRODUCT_ID = 0;
        let SERIAL_NUMBER = 0;

        function btnViewNote(productId, serialNumber) {
            document.getElementById('addSerialNumberSuccessMessage').style.display = 'none';
            document.getElementById('addSerialNumberErrorMessage').style.display = 'none';

            PRODUCT_ID = productId;
            SERIAL_NUMBER = serialNumber;

            try {
                let xhr = new XMLHttpRequest();

                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        const response = JSON.parse(xhr.responseText);
                        CKEDITOR.instances['txtDescription'].setData(response.data.note);
                    }
                    else {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    }
                };

                xhr.open("GET", `/admin/serial_number/getDataByProductIdAndSerialNumber/${SERIAL_NUMBER}/${PRODUCT_ID}`);
                xhr.send();
            } catch (e) {
                alert(e)
            }
        }

        function btnUpdateNote() {
            document.getElementById('addSerialNumberErrorMessage').style.display = 'none';
            document.getElementById('addSerialNumberErrorMessage').style.display = 'none';

            try {
                let updatedNote = CKEDITOR.instances['txtDescription'].getData();

                if(!updatedNote) {
                    document.querySelector('#addSerialNumberErrorMessage').innerHTML = "Note can be empty. Please add any letter.";
                    document.getElementById('addSerialNumberErrorMessage').style.display = 'block';
                    return;
                }

                let xhr = new XMLHttpRequest();
                let formData = new FormData();
                formData.append("note", updatedNote);

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        let response = JSON.parse(xhr.responseText);
                        document.querySelector('#addSerialNumberSuccessMessage').innerHTML = response.success;
                        document.getElementById('addSerialNumberSuccessMessage').style.display = 'block';
                    }
                    else {
                        const response = JSON.parse(xhr.responseText);
                        document.querySelector('#addSerialNumberErrorMessage').innerHTML = response.error;
                        document.getElementById('addSerialNumberErrorMessage').style.display = 'block';
                    }
                };

                xhr.open("POST", `/admin/serial_number/updateNote/${SERIAL_NUMBER}/${PRODUCT_ID}`, true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }
    </script>
@endsection
