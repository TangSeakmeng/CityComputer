@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/home.css') }}">
@endsection

@section('content')
    <div id="homeContainer">
        <div class="welcomeText">
            <h3 class="display-4">Welcome to City Computer Admin Panel!</h3>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            <div style="display: flex; text-align: center; justify-content: center; align-items: center">
                <a href="/admin/sell_operation/"><button class="btn btn-primary">Sell Now</button></a>
                <a href="/admin/import_products/create"><button  class="btn btn-primary ml-4">Import Now</button></a>
                <input type="text" class="form-control w-25 ml-4" id="txtInvoiceNumber" placeholder="invoice barcode or number" >
                <input type="text" class="form-control w-25 ml-4" id="txtProductSerialNumber" placeholder="product serial number">
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="messageBody"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('link_js')
    <script>
        document.querySelector('#txtInvoiceNumber').addEventListener("keyup", function(event) {
            try {
                if (event.keyCode === 13) {
                    event.preventDefault();

                    let invoiceNumber = event.target.value;

                    if(invoiceNumber == '') {
                        $('#exampleModal').modal('show');
                        document.querySelector('#exampleModalLabel').innerHTML = "Message";
                        document.querySelector('#messageBody').innerHTML = "<p>Please input the invoice number.</p>";
                        return;
                    }

                    let xhr = new XMLHttpRequest();

                    xhr.onload = (format, data) => {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            const response = JSON.parse(xhr.responseText);

                            if(response.message) {
                                $('#exampleModal').modal('show');
                                document.querySelector('#exampleModalLabel').innerHTML = "Message";
                                document.querySelector('#messageBody').innerHTML = `<p>${response.message}</p>`;
                                return;
                            }

                            let invoiceId = response.result;
                            window.location.href = `/admin/invoicedetails/${invoiceId.id}`;
                        }
                        else {
                            const response = JSON.parse(xhr.responseText);

                            $('#exampleModal').modal('show');
                            document.querySelector('#exampleModalLabel').innerHTML = "Error";
                            document.querySelector('#messageBody').innerHTML = `<p>${response.error}</p>`;
                        }
                    };

                    xhr.open("GET", "/admin/get_invoice_id/" + invoiceNumber);
                    xhr.send();
                }
            } catch (e) {
                alert(e)
            }
        });

        document.querySelector('#txtProductSerialNumber').addEventListener("keyup", function(event) {
            try {
                if (event.keyCode === 13) {
                    event.preventDefault();

                    let serial_number = event.target.value;

                    if(serial_number == '') {
                        $('#exampleModal').modal('show');
                        document.querySelector('#exampleModalLabel').innerHTML = "Message";
                        document.querySelector('#messageBody').innerHTML = "<p>Please input the invoice number.</p>";
                        return;
                    }

                    let xhr = new XMLHttpRequest();

                    xhr.onload = (format, data) => {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            const response = JSON.parse(xhr.responseText);

                            if(response.message) {
                                $('#exampleModal').modal('show');
                                document.querySelector('#exampleModalLabel').innerHTML = "Message";
                                document.querySelector('#messageBody').innerHTML = `<p>${response.message}</p>`;
                                return;
                            }

                            let result = response.result;
                            window.location.href = `/admin/products/getProductWithSerialNumberByProductId/${result.product_id}/${serial_number}`;
                        }
                        else {
                            const response = JSON.parse(xhr.responseText);

                            $('#exampleModal').modal('show');
                            document.querySelector('#exampleModalLabel').innerHTML = "Error";
                            document.querySelector('#messageBody').innerHTML = `<p>${response.error}</p>`;
                        }
                    };

                    xhr.open("GET", "/admin/products/get_product_id/" + serial_number);
                    xhr.send();
                }
            } catch (e) {
                alert(e)
            }
        });
    </script>
@endsection
