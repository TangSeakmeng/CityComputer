@extends('backend.layout.layout')

@section('link_css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('backend/css/pages/import_products/addTransaction.css') }}">
    <script src="https://cdn.ckeditor.com/4.15.1/basic/ckeditor.js"></script>
@endsection

@section('content')
    <div id="addTransactionContainer">
        <h1 class="display-4">Add Import Transaction</h1>

        @if ($notification = Session::get('success'))
            <div class="alert alert-success alert-block mt-4">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $notification }}</strong>
            </div>
        @endif

        @if ($notification = Session::get('error'))
            <div class="alert alert-danger alert-block mt-4">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $notification }}</strong>
            </div>
        @endif

        <div class="mt-4" id="frmAddImportTransaction">
            <div class="pt-2 pt-2 pl-4 pr-4 mb-4" style="background-color: #ECECEC; border-radius: 5px">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="txtInvoiceNumber">Invoice Number</label>
                        <input type="text" class="form-control" id="txtInvoiceNumber" name="txtInvoiceNumber" placeholder="invoice number" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtImportDate">Import Date</label>
                        <input type="date" class="form-control" id="txtImportDate" name="txtImportDate" placeholder="import date" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="selectSupplier">Supplier</label>
                        <select class="form-control" id="selectSupplier" name="selectSupplier">
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <input type="hidden" id="txtProductId" value="" required>
                    <input type="hidden" id="txtImagePath" value="" required>

                    <div class="form-group">
                        <label for="txtBarcode">Barcode</label>
                        <input type="text"
                               class="form-control"
                               id="txtBarcode"
                               name="txtBarcode"
                               placeholder="product barcode"
                               onkeypress="return enterKeyPressed(event)"
                        >
                    </div>
                    <div class="form-group">
                        <label for="txtProductName">Product Name</label>
                        <input type="text" class="form-control" id="txtProductName" name="txtProductName" placeholder="product name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="txtCategory">Category</label>
                        <input type="text" class="form-control" id="txtCategory" name="txtCategory" placeholder="category name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="txtBrand">Brand</label>
                        <input type="text" class="form-control" id="txtBrand" name="txtBrand" placeholder="brand name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="txtCostofSale">Cost of Sale</label>
                        <input type="number" class="form-control" id="txtCostofSale" name="txtCostofSale" placeholder="cost of sale" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="txtQuantity">Quantity</label>
                        <input type="number" class="form-control" id="txtQuantity" name="txtQuantity" placeholder="quantity" value="0">
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <div class="text-center">
                            <img src="{{ url('images/no_image_available.png') }}" style="width: 50%" id="txtImagePreview">
                        </div>
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-primary w-50" onclick="addProductToImportTable()">Add</button>
                        <button class="btn btn-danger w-50" style="margin-left: 20px" onclick="clearAllGeneratedInput()">Clear</button>
                    </div>
                </div>
                <div class="form-group col-md-8">
                    <div id="productTableContainer" class="mt-4">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Barcode</th>
                                <th scope="col">Name</th>
                                <th scope="col">Category</th>
                                <th scope="col">Brand</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Image</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="tbodyImportedProducts">
                                <td colspan="9" style="text-align: center">No Any Row!</td>
                            </tbody>
                        </table>
                    </div>

                    <div class="table_footer">
                        <p id="table_footer__total_quantity">Total Quantity: 0 Unit</p>
                        <p id="table_footer__total_cost">Total Cost: 0 $</p>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary" type="submit" onclick="confirmationOfSubmitTransactions()">Submit</button>
                <button class="btn btn-danger" type="reset" id="btnClear">Clear</button>
                <a href="/admin/import_products">
                    <button class="btn btn-warning" type="button">Back to Transactions Dashboard</button>
                </a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modalAddProductContainer">
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" id="alertSuccessful" style="display: none">
                            <strong>Successful!</strong> Product is added.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" id="alertWarning" style="display: none">
                            <strong>Error!</strong> <span id="alertWarning_text"></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form id="frmAddProduct">
                            @csrf

                            <div class="form-group">
                                <label for="txtNewBarcode">Barcode</label>
                                <input type="text"
                                       class="form-control"
                                       id="txtNewBarcode"
                                       placeholder="barcode"
                                       required
                                >
                            </div>

                            <div class="form-group">
                                <label for="txtNewName">Name</label>
                                <input type="text" class="form-control" id="txtNewName" name="txtNewName" placeholder="product name" required>
                            </div>

                            <div class="form-group">
                                <label for="selectNewCategory">Category</label>
                                <select class="form-control" id="selectNewCategory" name="selectNewCategory">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="selectNewBrand">Brand</label>
                                <select class="form-control" id="selectNewBrand" name="selectNewBrand">
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="txtNewDescription">Description</label>
                                <textarea name="txtNewDescription" id="txtNewDescription"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="txtNewCostOfSale">Cost of Sale</label>
                                <input type="number" class="form-control" id="txtNewCostOfSale" name="txtNewCostOfSale" placeholder="cost of sale" step="0.01" required>
                            </div>

                            <div class="form-group">
                                <div class="form-group">
                                    <label for="fileImage">Upload Image</label>
                                    <div id="thumbnail_preview" style="width: 100%;">
                                        <img src="{{ asset('images/no_image_available.png') }}" id="img_thumbnail" class="img-thumbnail">
                                    </div>

                                    <input type="file" name="thumbnail" id="thumbnail" class="form-control-file form-control-sm w-50 mt-2" required>
                                    <input type="hidden" id="old_thumbnail" name="old_thumbnail">
                                    <input type="hidden" name="additional_image_name" id="additional_image_name">
                                </div>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-primary" type="submit">Add Product</button>
                                <button class="btn btn-danger" type="reset" id="btnClearfrmAddProduct">Clear</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSerialNumberModal" tabindex="-1" role="dialog" aria-labelledby="addSerialNumberModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSerialNumberModalLabel">Add Serial Number</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="snModal_productBarcode"></p>
                    <p id="snModal_productName"></p>

                    <div class="form-row mt-4">
                        <div class="form-group col-md-8">
                            <label for="snModal_txtSerialNumber">Serial Number:</label>
                            <input type="text" class="form-control" id="snModal_txtSerialNumber" placeholder="serial number">
                        </div>
                        <div class="form-group col-md-4">
                            <button class="btn btn-dark w-100" style="margin-top: 32px" onclick="addSerialNumber()" id="btnAddSerialNumber">Add SN</button>
                        </div>
                    </div>

                    <div class="alert alert-danger alert-block mt-2" id="addSerialNumberAlertErrorMessage" style="display: none"></div>
                    <div class="alert alert-warning alert-block mt-2" id="addSerialNumberAlertMessage"></div>

                    <table class="table mt-4">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Serial Number</th>
                            <th scope="col">Delete</th>
                        </tr>
                        </thead>
                        <tbody id="tbodySerialNumberTable">

                        </tbody>
                    </table>
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
                    <h5 class="modal-title" id="messageModalLabel"><div id="messageModalTitle"></div></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="messageModalBody"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Submit Transactions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure that you want to continue?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" onclick="submitTransactions()" data-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('link_js')
    <script src="{{ url('backend/js/pages/import_products/app.js') }}"></script>
    <script>
        CKEDITOR.replace( 'txtNewDescription' );

        let currentDate = new Date();
        document.querySelector('#txtImportDate').value = [currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate()].join('-');

        $(document).ready(function() {
            $("#thumbnail").change(function() {
                preview_thumbnail(this);
            });

            function preview_thumbnail(input) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();

                    reader.onload = function(e) {
                        $('#img_thumbnail').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        });

        document.querySelector('#frmAddProduct').addEventListener('submit', (e) => {
            e.preventDefault();

            document.getElementById('alertSuccessful').style.display = 'none';
            document.getElementById('alertWarning').style.display = 'none';

            let description = CKEDITOR.instances['txtNewDescription'].getData();

            if(description == '') {
                document.getElementById('alertWarning').style.display = 'block';
                document.getElementById('alertWarning_text').innerHTML = 'Please fill the product description.';
                return;
            }

            let xhr = new XMLHttpRequest();
            let formData = new FormData();

            let barcode = document.querySelector("#txtNewBarcode").value;
            let name = document.querySelector("#txtNewName").value;
            let category_id = document.querySelector("#selectNewCategory").value;
            let brand_id = document.querySelector("#selectNewBrand").value;

            let cost_of_sale = document.querySelector("#txtNewCostOfSale").value;
            let fileImage = document.querySelector("#thumbnail").files[0];

            formData.append("barcode", barcode);
            formData.append("name", name);
            formData.append("category_id", category_id);
            formData.append("brand_id", brand_id);
            formData.append("description", description);
            formData.append("cost_of_sale", cost_of_sale);
            formData.append("unit_in_stock", "0");
            formData.append("sale_price", "0");
            formData.append("discount_price", "0");
            formData.append("thumbnail", fileImage);

            xhr.onload = (format, data) => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    document.getElementById("frmAddProduct").reset();
                    document.getElementById("img_thumbnail").setAttribute('src', '{{ asset('images/no_image_available.png') }}');
                    CKEDITOR.instances['txtNewDescription'].setData(' ');

                    document.getElementById('alertSuccessful').style.display = 'block';
                }
                else {
                    const response = JSON.parse(xhr.responseText);
                    document.getElementById('alertWarning').style.display = 'block';
                    document.getElementById('alertWarning_text').innerHTML = response.message;
                }
            };

            xhr.open("POST", "/admin/products", true);
            xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
            xhr.send(formData);
        });

        document.querySelector('#btnClearfrmAddProduct').addEventListener('click', (e) => {
            document.getElementById("frmAddProduct").reset();
            document.getElementById("img_thumbnail").setAttribute('src', '{{ asset('images/no_image_available.png') }}');
            CKEDITOR.instances['txtNewDescription'].setData(' ');
        });

        // get product information from database and set default default to form
        function enterKeyPressed(event) {
            try {
                if (event.keyCode == 13) {
                    let xhr = new XMLHttpRequest();
                    let formData = new FormData();

                    let barcode = document.querySelector("#txtBarcode").value;
                    formData.append("barcode", barcode);

                    xhr.onload = (format, data) => {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            const response = JSON.parse(xhr.responseText);

                            if(response.data != null) {
                                document.querySelector('#txtProductId').value = response.data.id;
                                document.querySelector('#txtProductName').value = response.data.name;
                                document.querySelector('#txtCategory').value = response.data.category_name;
                                document.querySelector('#txtBrand').value = response.data.brand_name;
                                document.querySelector('#txtCostofSale').value = response.data.cost_of_sale;
                                document.querySelector('#txtImagePath').value = response.data.image_path;
                                document.querySelector('#txtImagePreview').setAttribute('src', "{{ asset('uploaded_images/products/') }}/" + response.data.image_path );
                            } else {
                                clearAllGeneratedInput();
                                document.querySelector("#txtNewBarcode").value = barcode;
                                $('#exampleModal').modal('show');
                            }
                        }
                        else {
                            const response = JSON.parse(xhr.responseText);
                            alert(response.message);
                        }
                    };

                    xhr.open("POST", "/admin/import_products/getProductByBarcode", true);
                    xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                    xhr.send(formData);

                    return true;
                }
            } catch (e) {
                alert(e)
            }
        }

        // generate row from arr_importedproducts to temporary import table
        function generateRowsToTable() {
            try {
                document.querySelector('#tbodyImportedProducts').innerHTML = "";
                let tbody = document.querySelector('#tbodyImportedProducts');

                arr_ImportedProduct.forEach((item, index) => {
                    let dom = document.createElement('tr');

                    dom.innerHTML = `
                <td>${item.productId}</td>
                <td>${item.barcode}</td>
                <td style="width: 300px;">${item.productName}</td>
                <td>${item.category_name}</td>
                <td>${item.brand_name}</td>
                <td>${item.cost_of_sale}</td>
                <td>
                    <input type="number"
                            class="form-control"
                            id="txtRowProductQty${index}"
                            onchange="rowQtyChanged(${index})"
                            value="${item.quantity}"
                            style="width: 100px"
                            >
                </td>
                <td>
                    <img src="{{ asset('uploaded_images/products') }}/${item.image_path}" style="width: 100px;">
                </td>
                <td style="width: 100px;">
                    <button class="btn btn-danger" onclick="deleteRow(${index})">x</button>
                    <button class="btn btn-success" onclick="openAddSerialNumberModal(${index})">+</button>
                </td>
               `;

                    tbody.appendChild(dom);
                });

                calculateTotalQuantity();
            } catch (e) {
                alert(e)
            }
        }

        // clear generated default value form
        function clearAllGeneratedInput() {
            try {
                document.querySelector('#txtBarcode').value = "";
                document.querySelector('#txtProductName').value = "";
                document.querySelector('#txtCategory').value = "";
                document.querySelector('#txtBrand').value = "";
                document.querySelector('#txtCostofSale').value = "0";
                document.querySelector('#txtQuantity').value ='0';
                document.querySelector('#txtImagePreview').setAttribute('src', "{{ asset('images/no_image_available.png') }}");
            } catch (e) {
                alert(e)
            }
        }

        let arr_ImportedProduct = [];
        let arr_productSerialNumbers = [];
        let selectedProductIndex = 0;
        let total_importedPrice = 0;

        // add product from generated form to temporary import table
        function addProductToImportTable() {
            try {
                if(document.querySelector('#txtBarcode').value == '') {
                    document.querySelector('#messageModalTitle').innerHTML = 'Error';
                    document.querySelector('#messageModalBody').innerHTML = 'Barcode can not be empty.';
                    $('#messageModal').modal('show');
                    return;
                }

                if(document.querySelector('#txtCostofSale').value == '') {
                    document.querySelector('#messageModalTitle').innerHTML = 'Error';
                    document.querySelector('#messageModalBody').innerHTML = 'Cost of Sale can not be empty.';
                    $('#messageModal').modal('show');
                    return;
                }

                if(document.querySelector('#txtQuantity').value == '') {
                    document.querySelector('#messageModalTitle').innerHTML = 'Error';
                    document.querySelector('#messageModalBody').innerHTML = 'Quantity can not be empty.';
                    $('#messageModal').modal('show');
                    return;
                }

                let productId = document.querySelector('#txtProductId').value;
                let barcode = document.querySelector('#txtBarcode').value;
                let productName = document.querySelector('#txtProductName').value;
                let category_name = document.querySelector('#txtCategory').value;
                let brand_name = document.querySelector('#txtBrand').value;
                let cost_of_sale = document.querySelector('#txtCostofSale').value;
                let quantity = document.querySelector('#txtQuantity').value;
                let image_path = document.querySelector('#txtImagePath').value;

                let importedProduct = {
                    productId,
                    barcode,
                    productName,
                    category_name,
                    brand_name,
                    cost_of_sale,
                    quantity,
                    image_path
                }

                let result = arr_ImportedProduct.findIndex((item) => {
                    return importedProduct.productId == item.productId;
                })

                if(result == -1) {
                    arr_ImportedProduct.push(importedProduct);
                } else {
                    let existedQty = parseInt(arr_ImportedProduct[result].quantity);
                    existedQty += parseInt(quantity);
                    arr_ImportedProduct[result].quantity = existedQty + "";
                }

                generateRowsToTable();
                clearAllGeneratedInput();
            } catch (e) {
                alert(e)
            }
        }

        // delete any product row from temporary import table and re-render the table
        function deleteRow(rowIndex) {
            try {
                arr_productSerialNumbers = arr_productSerialNumbers.filter((item) => {
                    return item.productId != arr_ImportedProduct[rowIndex].productId;
                });

                arr_ImportedProduct.splice(rowIndex, 1);

                generateRowsToTable();
            } catch (e) {
                alert(e)
            }
        }

        // change the value of quantity in arr_importedproducts and re-render the table footer
        function rowQtyChanged(rowIndex) {
            try {
                let value = document.querySelector('#txtRowProductQty' + rowIndex).value;
                arr_ImportedProduct[rowIndex].quantity = value;

                calculateTotalQuantity();
            } catch (e) {
                alert(e)
            }
        }

        // calculate the total import quantity and imported subtotal and re-render the table footer
        function calculateTotalQuantity() {
            try {
                let total_quantity = 0;
                let total_cost = 0;

                arr_ImportedProduct.forEach((item) => {
                    total_quantity += parseInt(item.quantity);
                    total_cost += item.quantity * item.cost_of_sale;
                })

                document.querySelector('#table_footer__total_quantity').innerHTML = `Total Quantity: ${total_quantity} units.`
                document.querySelector('#table_footer__total_cost').innerHTML = `Total Cost: ${total_cost} $.`

                total_importedPrice = total_cost;
            } catch (e) {
                alert(e)
            }
        }

        // Open AddProductModal when the input barcode is not found
        function openAddSerialNumberModal(rowIndex) {
            try {
                selectedProductIndex = rowIndex;

                checkQtyAndNumberOfSerialNumber();
                generateSerialNumbersToTable();

                document.querySelector('#snModal_productBarcode').innerHTML = "Product Barcode: " + arr_ImportedProduct[rowIndex].barcode;
                document.querySelector('#snModal_productName').innerHTML = "Product Name: " + arr_ImportedProduct[rowIndex].productName;
                $('#addSerialNumberModal').modal('show');
            } catch (e) {
                alert(e)
            }
        }

        // event of add serial number button in add serial modal modal
        function addSerialNumber() {
            try {
                let serialNumber = document.querySelector('#snModal_txtSerialNumber').value;
                let productId = arr_ImportedProduct[selectedProductIndex].productId;

                if(serialNumber == '') {
                    document.querySelector('#addSerialNumberAlertErrorMessage').innerHTML = '<strong>Serial Number can not be empty.</strong>';
                    document.getElementById('addSerialNumberAlertErrorMessage').style.display = 'block';
                    return;
                }

                let isExist = arr_productSerialNumbers.some((item) => serialNumber == item.serialNumber && productId == item.productId);

                if(isExist) {
                    document.querySelector('#addSerialNumberAlertErrorMessage').innerHTML = '<strong>Serial Number is already existed.</strong>';
                    document.querySelector('#snModal_txtSerialNumber').value = "";
                    document.getElementById('addSerialNumberAlertErrorMessage').style.display = 'block';
                    return;
                }

                let data = {
                    serialNumber,
                    productId
                }

                document.getElementById('addSerialNumberAlertErrorMessage').style.display = 'none';

                arr_productSerialNumbers.push(data);
                document.querySelector('#snModal_txtSerialNumber').value = "";

                checkQtyAndNumberOfSerialNumber();
                generateSerialNumbersToTable();
            } catch (e) {
                alert(e)
            }
        }

        // generate row from arr_serialnumber to temporary serial number table
        function generateSerialNumbersToTable() {
            try {
                document.querySelector('#tbodySerialNumberTable').innerHTML = "";
                let tbody = document.querySelector('#tbodySerialNumberTable');

                arr_productSerialNumbers.forEach((item, index) => {
                    if(item.productId == arr_ImportedProduct[selectedProductIndex].productId) {
                        let dom = document.createElement('tr');

                        dom.innerHTML = `
                        <td>${index}</td>
                        <td>${item.serialNumber}</td>
                        <td>
                            <button class="btn btn-danger" onclick="deleteSerialNumberRow(${index})">x</button>
                        </td>
                   `;

                        tbody.appendChild(dom);
                    }
                });
            } catch (e) {
                alert(e)
            }
        }

        // delete any row of serial number row from table and delete that row from that arr
        function deleteSerialNumberRow(rowIndex) {
            try {
                arr_productSerialNumbers.splice(rowIndex, 1);

                generateSerialNumbersToTable();
                checkQtyAndNumberOfSerialNumber();
            } catch (e) {
                alert(e)
            }
        }

        // check the number of serial number must be equal to the product import quantity by specific productId and return true if equal and return number is it difference
        function countIsNumberOfSerialNumberEqualToProductQty(productId) {
            try {
                let product_index = arr_ImportedProduct.findIndex((item) => item.productId == productId);
                let product_qty = arr_ImportedProduct[product_index].quantity;

                let count = 0;
                arr_productSerialNumbers.forEach((item) => {
                    if(item.productId == productId)
                        count++;
                })

                if(count == product_qty)
                    return true;
                else
                    return product_qty - count;
            } catch (e) {
                alert(e)
            }
        }

        // check the number of serial number must be equal to the product import quantity for all product and use it for validate before submit to database
        function checkQtyAndNumberOfSerialNumber() {
            try {
                let resultCount = countIsNumberOfSerialNumberEqualToProductQty(arr_ImportedProduct[selectedProductIndex].productId);

                if(resultCount === true) {
                    document.getElementById('addSerialNumberAlertMessage').style.display = "none";
                    document.getElementById('btnAddSerialNumber').disabled = true;
                    return;
                } else {
                    document.getElementById('addSerialNumberAlertMessage').style.display = "block";
                    document.getElementById('btnAddSerialNumber').disabled = false;
                    document.querySelector('#addSerialNumberAlertMessage').innerHTML = `<strong>There are ${resultCount} serial number(s) left.</strong>`;
                }
            } catch (e) {
                alert(e)
            }
        }

        //
        function countAllNumberOfSerialNumberIsEqualToImportedQty() {
            try {
                let arr_productsError = [];

                arr_ImportedProduct.forEach((item) => {
                    let qty = item.quantity;
                    let count = 0;

                    arr_productSerialNumbers.forEach((jtem) => {
                        if(item.productId == jtem.productId)
                            count++;
                    })

                    if(count == 0) {

                    }
                    else if(qty != count)
                        arr_productsError.push(item);
                })

                if(arr_productsError.length != 0) {
                    document.querySelector('#messageModalTitle').innerHTML = 'Error';
                    document.querySelector('#messageModalBody').innerHTML = "Number of Serial Number is not equal to imported quantity.";
                    $('#messageModal').modal('show');
                    return false;
                }

                return true;
            } catch (e) {
                alert(e)
            }
        }

        // open modal before submit for user confirmation
        function confirmationOfSubmitTransactions() {
            $('#confirmationModal').modal('show');
        }

        let importId = 0;

        // final submit to database
        function submitTransactions() {
            try {
                if(arr_ImportedProduct.length == 0) {
                    document.querySelector('#messageModalTitle').innerHTML = 'Error';
                    document.querySelector('#messageModalBody').innerHTML = 'Please input any product.';
                    $('#messageModal').modal('show');
                    return;
                }

                if(document.querySelector('#txtInvoiceNumber').value == '') {
                    document.querySelector('#messageModalTitle').innerHTML = 'Error';
                    document.querySelector('#messageModalBody').innerHTML = 'Please input invoice number.';
                    $('#messageModal').modal('show');
                    return;
                }

                if(!countAllNumberOfSerialNumberIsEqualToImportedQty())
                    return;

                let xhr = new XMLHttpRequest();
                let formData = new FormData();

                let invoice_number = document.querySelector('#txtInvoiceNumber').value;
                let import_date = document.querySelector('#txtImportDate').value;
                let supplier_id = document.querySelector('#selectSupplier').value;
                let import_total = total_importedPrice;

                formData.append("invoice_number", invoice_number);
                formData.append("import_date", import_date);
                formData.append("supplier_id", supplier_id);
                formData.append("import_total", import_total);

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        const response = JSON.parse(xhr.responseText);
                        importId = response.importId;

                        submitTransactionImportDetails();
                    }
                    else {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    }
                };

                xhr.open("POST", "/admin/import_products/addImportMaster", true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }

        function submitTransactionImportDetails() {
            try {
                let xhr = new XMLHttpRequest();
                let formData = new FormData();

                let import_id = importId;
                let list_importedProducts = arr_ImportedProduct;

                formData.append("import_id", import_id);
                formData.append("list_importedProducts", JSON.stringify(list_importedProducts));

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        const response = JSON.parse(xhr.responseText);
                        submitTransactionImportDetailSerialNumbers();
                    }
                    else {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    }
                };

                xhr.open("POST", "/admin/import_products/addImportDetails", true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }

        function submitTransactionImportDetailSerialNumbers() {
            try {
                let xhr = new XMLHttpRequest();
                let formData = new FormData();

                let import_id = importId;
                let list_productSerialNumbers = arr_productSerialNumbers;

                formData.append("import_id", import_id);
                formData.append("list_productSerialNumbers", JSON.stringify(list_productSerialNumbers));

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        const response = JSON.parse(xhr.responseText);

                        document.querySelector('#messageModalTitle').innerHTML = 'Success';
                        document.querySelector('#messageModalBody').innerHTML = 'Import Transactions are registered successfully.';
                        $('#messageModal').modal('show');

                        document.querySelector('#txtInvoiceNumber').value = '';
                        document.querySelector('#txtImportDate').value = `${new Date().getFullYear()}-${new Date().getMonth() + 1}-${new Date().getDate()}`;
                        document.getElementById('selectSupplier').value = 1;
                        document.querySelector('#tbodyImportedProducts').innerHTML = '<td colspan="9" style="text-align: center">No Any Row!</td>';

                        arr_ImportedProduct = [];
                        arr_productSerialNumbers = [];
                        selectedProductIndex = 0;
                        total_importedPrice = 0;
                        importId = 0;
                    }
                    else {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    }
                };

                xhr.open("POST", "/admin/import_products/addImportProductSerialNumbers", true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }
    </script>
@endsection
