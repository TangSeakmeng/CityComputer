@extends('backend.layout.layout')

@section('link_css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('backend/css/pages/products/addProduct.css') }}">
    <script src="https://cdn.ckeditor.com/4.15.1/basic/ckeditor.js"></script>
@endsection

@section('content')
    <div id="addProductContainer">
        <h1 class="display-4">Edit Product</h1>

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

        <form class="mt-4" id="formUpdateProduct">
            @csrf

            <input type="hidden" name="_method" value="PUT">

            <input type="hidden" id="txtProductId" value="{{$data->productId}}">

            <div class="form-group">
                <label for="txtBarcode">Barcode</label>
                <input type="text" class="form-control" id="txtBarcode" name="txtBarcode" placeholder="barcode" required value="{{ $data->barcode }}">
            </div>

            <div class="form-group">
                <label for="txtName">Name</label>
                <input type="text" class="form-control" id="txtName" name="txtName" placeholder="product name" required value="{{ $data->name }}">
            </div>

            <div class="form-group">
                <label for="selectCategory">Category</label>
                <select class="form-control" id="selectCategory" name="selectCategory">
                    @foreach($categories as $category)
                        @if($category->name == $data->category_name)
                            <option value="{{$category->id}}" selected>{{$category->name}}</option>
                        @else
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="selectBrand">Brand</label>
                <select class="form-control" id="selectBrand" name="selectBrand">
                    @foreach($brands as $brand)
                        @if($brand->name == $data->brand_name)
                            <option value="{{$brand->id}}" selected>{{$brand->name}}</option>
                        @else
                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="txtDescription">Description</label>
                <textarea name="txtDescription" id="txtDescription"></textarea>
            </div>

            <div class="form-group">
                <label for="txtCostOfSale">Cost of Sale</label>
                <input type="number" class="form-control" id="txtCostOfSale" name="txtCostOfSale" placeholder="cost of sale" step="any" required value="{{ $data->cost_of_sale }}">
            </div>

            <div class="form-group">
                <label for="txtUnitInStock">Unit In Stock</label>
                <input type="number" class="form-control" id="txtUnitInStock" name="txtUnitInStock" placeholder="unit in stock" required value="{{ $data->unit_in_stock }}">
            </div>

            <div class="form-group">
                <label for="txtSalePrice">Sale Price</label>
                <input type="number" class="form-control" id="txtSalePrice" name="txtSalePrice" placeholder="sale price" step="any" required value="{{ $data->price }}">
            </div>

            <div class="form-group">
                <label for="txtDiscountPrice">Discount Price</label>
                <input type="number" class="form-control" id="txtDiscountPrice" name="txtDiscountPrice" placeholder="discount" step="any" required value="{{ $data->discount_price }}">
            </div>

            <div class="form-group">
                <div class="form-group">
                    <label for="fileImage">Upload Image</label>
                    <div id="thumbnail_preview">
                        <img src="{{ asset('uploaded_images/products/' . $data->image_path) }}" id="img_thumbnail" class="img-thumbnail">
                    </div>

                    <input type="file" name="thumbnail" id="thumbnail" class="form-control-file form-control-sm w-50 mt-2">
                    <input type="hidden" id="old_thumbnail" name="old_thumbnail" value="{{ $data->image_path }}">
                    <input type="hidden" name="additional_image_name" id="additional_image_name">
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary" type="submit">Save</button>
                <a href="/admin/products">
                    <button class="btn btn-warning" type="button">Back to Products Dashboard</button>
                </a>
            </div>
        </form>
    </div>

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
                    Update Product Successfully!
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
        CKEDITOR.replace( 'txtDescription' );
        let productId = document.querySelector('#txtProductId').value;

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

        function getProductByProductId() {
            let xhr = new XMLHttpRequest();

            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    const result = JSON.parse(xhr.responseText);
                    CKEDITOR.instances['txtDescription'].setData(result.data.description);
                }
            };

            xhr.open("GET", "/admin/products/getProductByProductId/" + productId);
            xhr.send();
        }

        getProductByProductId();

        document.querySelector('#formUpdateProduct').addEventListener('submit', (e) => {
            e.preventDefault();

            let xhr = new XMLHttpRequest();
            let formData = new FormData();

            let barcode = document.querySelector("#txtBarcode").value;
            let name = document.querySelector("#txtName").value;
            let category_id = document.querySelector("#selectCategory").value;
            let brand_id = document.querySelector("#selectBrand").value;
            let description = CKEDITOR.instances['txtDescription'].getData();
            let cost_of_sale = document.querySelector("#txtCostOfSale").value;
            let unit_in_stock = document.querySelector("#txtUnitInStock").value;
            let sale_price = document.querySelector("#txtSalePrice").value;
            let discount_price = document.querySelector("#txtDiscountPrice").value;
            let fileImage = document.querySelector("#thumbnail").files[0];
            let oldImagePath = document.querySelector("#old_thumbnail").value;

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
            formData.append("old_thumbnail", oldImagePath);

            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    // const response = JSON.parse(xhr.responseText);
                    $('#messageModal').modal('show');
                }
            };

            xhr.open("post", "/admin/products/updateProduct/" + productId, false);
            xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
            xhr.send(formData);
        })
    </script>
@endsection
