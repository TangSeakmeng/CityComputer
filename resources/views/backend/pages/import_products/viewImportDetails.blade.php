@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/import_products/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.ckeditor.com/4.15.1/basic/ckeditor.js"></script>
@endsection

@section('content')
    <div id="transactionContainer">
        <div class="transactionHeader">
            <h1 class="display-4">View Import Transaction</h1>
            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#returnProductModal">Return Product</button>
        </div>

        <input type="hidden" id="txtImportId" value="{{ $data->id }}">

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
                        @if(sizeof($data_2) == 0)
                            <tr>
                                <td colspan="5" style="text-align: center">No Row(s).</td>
                            </tr>
                        @endif

                        @foreach($data_2 as $item)
                            <tr>
                                <th scope="row">{{ $item->product_id }}</th>
                                <td>{{ $item->product_barcode }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->import_price }}$</td>
                                <td>{{ $item->import_quantity }} unit(s)</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 mt-4" style="border-top: 2px solid grey; padding-top: 20px">
                <h1 class="display-4">Product Serial Number</h1>
                <button class="btn btn-success" onclick="btnShowProductSerialNumberDialog()">
                    Add Serial Number
                </button>
            </div>

            @foreach($arr_alertMessageToUser as $item)
                {!!html_entity_decode($item)!!}
            @endforeach

            <div>
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Product ID</th>
                        <th scope="col">Product Barcode</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Product Serial Number</th>
                        <th scope="col">Status</th>
                        <th scope="col">Note</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(sizeof($data_3) == 0)
                        <tr>
                            <td colspan="7" style="text-align: center">No Row(s).</td>
                        </tr>
                    @endif

                    @foreach($data_3 as $key=>$item)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <th>{{ $item->product_id }}</th>
                            <td>{{ $item->product_barcode }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->serial_number }}</td>
                            <td>{{ $item->status}}</td>
                            <td>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal" onclick="btnViewNote({{ $item->product_id }}, '{{ $item->serial_number }}')">
                                    View Note
                                </button>
                                <button type="button" class="btn btn-danger" onclick="deleteProductSerialNumber({{ $item->product_id }}, '{{ $item->serial_number }}')">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 mt-4" style="border-top: 2px solid grey; padding-top: 20px">
                <h1 class="display-4">Return History</h1>
            </div>

            <div>
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Return ID</th>
                        <th scope="col">Product ID</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Return Quantity</th>
                        <th scope="col">Return Date</th>
                    </tr>
                    </thead>s
                    <tbody>
                    @if(sizeof($data_5) == 0)
                        <tr>
                            <td colspan="5" style="text-align: center">No Row(s).</td>
                        </tr>
                    @endif

                    @foreach($data_5 as $item)
                        <tr>
                            <th scope="row">{{ $item->return_id }}</th>
                            <th>{{ $item->product_id }}</th>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->return_qty }}</td>
                            <td>{{ $item->return_date }}</td>
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

    <div class="modal fade" id="addSerialNumberModal" tabindex="-1" role="dialog" aria-labelledby="addSerialNumberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSerialNumberModalLabel">Add Serial Number</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger alert-block mt-2" id="addSerialNumberAlertErrorMessage" style="display: none"></div>
                    <div class="alert alert-success alert-block mt-2" id="addSerialNumberAlertSuccessMessage" style="display: none"></div>

                    <div class="form-group">
                        <label for="selectProductName">Product Name</label>
                        <select class="form-control" id="selectProductName" name="selectProductName">
                            @foreach($data_2 as $item)
                                <option value="{{ $item->product_id }}">{{ $item->product_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label for="snModal_txtSerialNumber">Serial Number:</label>
                            <input type="text" class="form-control" id="snModal_txtSerialNumber" placeholder="serial number">
                        </div>
                        <div class="form-group col-md-2">
                            <button class="btn btn-dark w-100" style="margin-top: 32px" onclick="addSerialNumber()" id="btnAddSerialNumber">Add SN</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel"><span id="messageTitle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="messageBody"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="returnProductModal" tabindex="-1" role="dialog" aria-labelledby="returnProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnProductLabel">Return Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" role="alert" id="alertReturnProductSuccessMessage" style="display: none"></div>
                    <div class="alert alert-danger" role="alert" id="alertReturnProductErrorMessage" style="display: none"></div>

                    <form id="formAddReturnProduct">
                        <div class="form-group">
                            <label for="selectProductName">Product Name</label>
                            <select class="form-control" id="selectProductName_ForReturn">
                                @foreach($data_2 as $key=>$item)
                                    <option value="{{ $item->product_id }}">
                                        {{ $item->product_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="txtReturnQuantity">Return Quantity</label>
                            <input type="number" class="form-control" id="txtReturnQuantity" placeholder="enter return quantity">
                        </div>
                    </form>

                    <button type="button" class="btn btn-primary w-100" onclick="btnAddReturnProduct()">Add</button>

                    <table class="table mt-3">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyReturnProducts">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitReturnProductsOperation()">Submit</button>
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

        function btnShowProductSerialNumberDialog() {
            $('#addSerialNumberModal').modal('show');
            document.getElementById('addSerialNumberAlertSuccessMessage').style.display = 'none';
            document.getElementById('addSerialNumberAlertErrorMessage').style.display = 'none';
            document.querySelector('#snModal_txtSerialNumber').value = "";
            document.querySelector('#snModal_txtSerialNumber').focus();
        }

        function addSerialNumber() {
            try {
                document.getElementById('addSerialNumberAlertSuccessMessage').style.display = 'none';
                document.getElementById('addSerialNumberAlertErrorMessage').style.display = 'none';

                let import_id = document.querySelector('#txtImportId').value;
                let product_id = document.querySelector('#selectProductName').value;
                let serial_number = document.querySelector('#snModal_txtSerialNumber').value;

                if(serial_number == '') {
                    document.querySelector('#addSerialNumberAlertErrorMessage').innerHTML = 'Product Serial Number is required.';
                    document.getElementById('addSerialNumberAlertErrorMessage').style.display = 'block';
                    return;
                }

                let xhr = new XMLHttpRequest();
                let formData = new FormData();

                formData.append("import_id", import_id);
                formData.append("product_id", product_id);
                formData.append("serial_number", serial_number);

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        let response = JSON.parse(xhr.responseText);
                        document.querySelector('#addSerialNumberAlertSuccessMessage').innerHTML = response.message;
                        document.getElementById('addSerialNumberAlertSuccessMessage').style.display = 'block';

                        document.querySelector('#snModal_txtSerialNumber').value = "";
                        document.querySelector('#snModal_txtSerialNumber').focus();
                    }
                    else {
                        const response = JSON.parse(xhr.responseText);
                        document.querySelector('#addSerialNumberAlertErrorMessage').innerHTML = response.error;
                        document.getElementById('addSerialNumberAlertErrorMessage').style.display = 'block';
                    }
                };

                xhr.open("POST", `/admin/import_products/addImportProductSerialNumber_OnlyOne`, true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }

        document.querySelector('#snModal_txtSerialNumber').addEventListener('keyup', (e) => {
            if(e.keyCode === 13) {
                addSerialNumber();
            }
        })

        function deleteProductSerialNumber(productId, serialNumber) {
            try {
                let import_id = document.querySelector('#txtImportId').value;

                let xhr = new XMLHttpRequest();
                let formData = new FormData();

                formData.append("import_id", import_id);
                formData.append("product_id", productId);
                formData.append("serial_number", serialNumber);

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        let response = JSON.parse(xhr.responseText);
                        $('#messageModal').modal('show');
                        document.getElementById('messageTitle').innerHTML = "Message";
                        document.getElementById('messageBody').innerHTML = response.message;
                    }
                    else {
                        let response = JSON.parse(xhr.responseText);
                        $('#messageModal').modal('show');
                        document.getElementById('messageTitle').innerHTML = "Error";
                        document.getElementById('messageBody').innerHTML = response.message;
                    }
                };

                xhr.open("POST", `/admin/import_products/deleteImportProductSerialNumber_OnlyOne`, true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }

        let arr_returnProduct = [];
        let arr_importedProduct = [];

        function getImportDetailsByImportId() {
            try {
                let import_id = document.querySelector('#txtImportId').value;

                let xhr = new XMLHttpRequest();

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        let response = JSON.parse(xhr.responseText);
                        arr_importedProduct = response.data;
                    }
                    else {
                        let response = JSON.parse(xhr.responseText);
                        alert(response.error);
                    }
                };

                xhr.open("GET", `/admin/import_products/getImportDetails/` + import_id);
                xhr.send();
            } catch (e) {
                alert(e)
            }
        }

        getImportDetailsByImportId();

        function btnAddReturnProduct() {
            document.getElementById('alertReturnProductErrorMessage').style.display = 'none';

            try {
                let product_id = document.getElementById('selectProductName_ForReturn').value;
                let return_qty = parseInt(document.querySelector('#txtReturnQuantity').value);
                let productIndex = arr_importedProduct.findIndex((item) => item.product_id == product_id);
                let product_name = arr_importedProduct[productIndex].product_name;
                let imported_qty = parseInt(arr_importedProduct[productIndex].import_quantity + "");

                if(document.querySelector('#txtReturnQuantity').value == '') {
                    document.querySelector('#alertReturnProductErrorMessage').innerHTML = 'Return Quantity can not be empty.';
                    document.getElementById('alertReturnProductErrorMessage').style.display = 'block';
                    document.querySelector('#txtReturnQuantity').value = 1;
                    return;
                }

                if(return_qty <= 0) {
                    document.querySelector('#alertReturnProductErrorMessage').innerHTML = 'Return Quantity can not be negative or equal zero.';
                    document.getElementById('alertReturnProductErrorMessage').style.display = 'block';
                    document.querySelector('#txtReturnQuantity').value = 1;
                    return;
                }

                let index = arr_returnProduct.findIndex((item) => item.product_id == product_id);

                if(index >= 0) {
                    let old_qty = arr_returnProduct[index].return_qty;

                    if(old_qty + return_qty > imported_qty) {
                        document.querySelector('#alertReturnProductErrorMessage').innerHTML = 'Return Quantity can not greater than number of imported quantity.';
                        document.getElementById('alertReturnProductErrorMessage').style.display = 'block';
                        document.querySelector('#txtReturnQuantity').value = 1;
                        return;
                    } else {
                        arr_returnProduct[index].return_qty = old_qty + return_qty;
                    }
                } else {
                    if(return_qty > imported_qty) {
                        document.querySelector('#alertReturnProductErrorMessage').innerHTML = 'Return Quantity can not greater than number of imported quantity.';
                        document.getElementById('alertReturnProductErrorMessage').style.display = 'block';
                        return;
                    }

                    arr_returnProduct.push({ product_id, product_name, imported_qty, return_qty });
                }

                renderArrReturnProduct();
            } catch (e) {
                alert(e);
            }
        }

        function deleteReturnProduct(productIndex) {
            arr_returnProduct.splice(productIndex, 1);
            renderArrReturnProduct();
        }

        function renderArrReturnProduct() {
            try {
                document.querySelector('#tbodyReturnProducts').innerHTML = '';

                arr_returnProduct.forEach((item, index) => {
                    let dom = document.createElement('tr');

                    dom.innerHTML = `
                        <th scope="row">${index + 1}</th>
                        <td>${item.product_name}</td>
                        <td>${item.return_qty}</td>
                        <td>
                            <button class="btn btn-danger" onclick="deleteReturnProduct(${index})">x</button>
                        </td>
                    `;

                    document.querySelector('#tbodyReturnProducts').appendChild(dom);
                })
            } catch (e) {
                alert(e);
            }
        }

        document.querySelector('#formAddReturnProduct').addEventListener('submit', (e) => {
            e.preventDefault();
        })

        function submitReturnProductsOperation() {
            try {
                if(arr_returnProduct == [])
                    return;

                let import_id = document.querySelector('#txtImportId').value;

                let xhr = new XMLHttpRequest();
                let formData = new FormData();

                formData.append("import_id", import_id);
                formData.append("data", JSON.stringify(arr_returnProduct));

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        let route = window.location.pathname + window.location.search;
                        window.location = route;
                    }
                    else {
                        let response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    }
                };

                xhr.open("POST", `/admin/returnImportedProduct/`, true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }
    </script>
@endsection
