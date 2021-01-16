@extends('backend.layout.layout')

@section('link_css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('backend/css/pages/products/addProduct.css') }}">
    <script src="https://cdn.ckeditor.com/4.15.1/basic/ckeditor.js"></script>
@endsection

@section('content')
    <div id="addProductContainer">
        <h1 class="display-4">Add Product</h1>

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

        <div class="alert alert-success alert-dismissible fade show mt-4 mb-4" role="alert" id="alertSuccessful" style="display: none">
            <strong>Successful!</strong> Product is added.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="alert alert-danger alert-dismissible fade show mt-4 mb-4" role="alert" id="alertWarning" style="display: none">
            <strong>Error!</strong> <span id="alertWarning_text"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form class="mt-4" id="frmAddProduct">
            @csrf

            <div class="form-group">
                <label for="txtBarcode">Barcode</label>
                <input type="text" class="form-control" id="txtBarcode" name="txtBarcode" placeholder="barcode" required>
            </div>

            <div class="form-group">
                <label for="txtName">Name</label>
                <input type="text" class="form-control" id="txtName" name="txtName" placeholder="product name" required>
            </div>

            <div class="form-group">
                <label for="selectCategory">Category</label>
                <select class="form-control" id="selectCategory" name="selectCategory">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="selectBrand">Brand</label>
                <select class="form-control" id="selectBrand" name="selectBrand">
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="txtDescription">Description</label>
                <textarea name="txtDescription" id="txtDescription"></textarea>
            </div>

            <div class="form-group">
                <label for="txtCostOfSale">Cost of Sale</label>
                <input type="number"
                       class="form-control"
                       id="txtCostOfSale"
                       name="txtCostOfSale"
                       placeholder="cost of sale"
                       step="0.01"
                       required
                       onchange="inputConstraintCannotBeNegative('txtCostOfSale', 0)">
            </div>

            <div class="form-group">
                <label for="txtUnitInStock">Unit In Stock</label>
                <input type="number"
                       class="form-control"
                       id="txtUnitInStock"
                       name="txtUnitInStock"
                       placeholder="unit in stock"
                       required
                       onchange="inputConstraintCannotBeNegative('txtUnitInStock', 0)">
            </div>

            <div class="form-group">
                <label for="txtSalePrice">Sale Price</label>
                <input type="number"
                       class="form-control"
                       id="txtSalePrice"
                       name="txtSalePrice"
                       placeholder="sale price"
                       step="0.01"
                       required
                       onchange="inputConstraintCannotBeNegative('txtSalePrice', 0)">
            </div>

            <div class="form-group">
                <label for="txtDiscountPrice">Discount Price</label>
                <input type="number"
                       class="form-control"
                       id="txtDiscountPrice"
                       name="txtDiscountPrice"
                       placeholder="discount"
                       step="0.01"
                       value="0"
                       required
                       onchange="inputConstraintCannotBeNegative('txtDiscountPrice', 0)">
            </div>

            <div class="form-group">
                <div class="form-group">
                    <label for="fileImage">Upload Image</label>
                    <div id="thumbnail_preview" style="max-width: 400px;">
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
                <a href="/admin/products">
                    <button class="btn btn-warning" type="button">Back to Products Dashboard</button>
                </a>
            </div>
        </form>

        <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="messageModalLabel">Add Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Add Product Successfully!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="messageDescriptionErrorModal" tabindex="-1" role="dialog" aria-labelledby="messageDescriptionErrorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="messageDescriptionErrorModalLabel">Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Description is required.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('link_js')
    <script>
        CKEDITOR.replace( 'txtDescription' );

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
            document.getElementById('alertSuccessful').style.display = 'none';
            document.getElementById('alertWarning').style.display = 'none';

            e.preventDefault();

            let xhr = new XMLHttpRequest();
            let formData = new FormData();

            let barcode = document.querySelector("#txtBarcode").value;
            let name = document.querySelector("#txtName").value;
            let category_id = document.querySelector("#selectCategory").value;
            let brand_id = document.querySelector("#selectBrand").value;
            let description = CKEDITOR.instances['txtDescription'].getData();

            if(!description) {
                $('#messageDescriptionErrorModal').modal('show');
                return;
            }

            let cost_of_sale = document.querySelector("#txtCostOfSale").value;
            let unit_in_stock = document.querySelector("#txtUnitInStock").value;
            let sale_price = document.querySelector("#txtSalePrice").value;
            let discount_price = document.querySelector("#txtDiscountPrice").value;
            let fileImage = document.querySelector("#thumbnail").files[0];

            formData.append("barcode", barcode);
            formData.append("name", name);
            formData.append("category_id", category_id);
            formData.append("brand_id", brand_id);
            formData.append("description", description);
            formData.append("cost_of_sale", cost_of_sale);
            formData.append("unit_in_stock", unit_in_stock);
            formData.append("sale_price", sale_price);
            formData.append("discount_price", discount_price);
            formData.append("thumbnail", fileImage);

            xhr.onload = (format, data) => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    // $('#messageModal').modal('show');
                    // const response = JSON.parse(xhr.responseText);

                    document.getElementById("frmAddProduct").reset();
                    document.getElementById("img_thumbnail").setAttribute('src', '{{ asset('images/no_image_available.png') }}');
                    CKEDITOR.instances['txtDescription'].setData(' ');

                    // document.getElementById('alertSuccessful').style.display = 'block';
                    $('#messageModal').modal('show');
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
            CKEDITOR.instances['txtDescription'].setData(' ');
        });

        function inputConstraintCannotBeNegative(inputName, defaultValue) {
            let value = document.querySelector(`#${inputName}`).value;

            if(value == "" || value < 0)
                document.querySelector(`#${inputName}`).value = defaultValue;
        }

        function inputConstraintCannotBeNegativeAndZero(inputName, defaultValue) {
            let value = document.querySelector(`#${inputName}`).value;

            if(value == "" || value <= 0)
                document.querySelector(`#${inputName}`).value = defaultValue;
        }
    </script>
@endsection
