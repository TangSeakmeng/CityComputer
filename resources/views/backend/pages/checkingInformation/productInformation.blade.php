@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/products/addProduct.css') }}">
    <link rel="stylesheet" href="{{ url('backend/css/pages/products/viewProduct.css') }}">
@endsection

@section('content')
    <div id="addProductContainer">
        <div class="viewProductHeader">
            <div>
                <h4>{{ $data->name }}</h4>
                <small>Created At: {{ $data->created_at }}, Updated At: {{ $data->updated_at }}</small><br/><br/>
            </div>

            <div class="groupActionButtons">
                <a href="/admin/products/{{ $data->id }}/edit">
                    <button class="btn btn-warning">
                        <img src="{{ url('/assets/icons/edit_2.png') }}" class="actionButtonIcon">
                    </button>
                </a>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" onclick="deleteProduct({{$data->id}})">
                    <img src="{{ url('/assets/icons/rubbish_2.png') }}" class="actionButtonIcon">
                </button>
            </div>
        </div>

        <div class="blockContainer">
            <div class="leftSideContainer">
                <div class="detailContainer">
                    <p>More Details</p>
                    <ul>
                        <li>Barcode: {{ $data->barcode }}</li>
                        <li>Category: {{ $data->category_name }}</li>
                        <li>Brand: {{ $data->brand_name }}</li>
                        <li>Cost of Sale: {{ $data->cost_of_sale }}$</li>
                        <li>Unit in Stock: {{ $data->unit_in_stock }}$</li>
                        <li>Sale Price: {{ $data->price }}$</li>
                        <li>Discount Price: {{ $data->discount_price }}$</li>
                    </ul>
                </div>

                <div class="descriptionContainer">
                    <p>Description</p>
                    {!!html_entity_decode($data->description)!!}
                </div>
            </div>
            <div class="rightSideContainer">
                <img src="{{ url('uploaded_images/products/' . $data->image_path) }}">
            </div>
        </div>

        @if($data2 != null)
            <div class="mt-4">
                <h1 class="display-4 mb-4">Sell History</h1>
                <div>
                    <p>Invoice ID: <a href="/admin/invoicedetails/{{ $data2->id }}">{{ $data2->id }}</a></p>
                    <p>Invoice Number: {{ $data2->invoice_number }}</p>
                    <p>Invoice Date: {{ $data2->invoice_date }}</p>
                    <p>Customer Name: {{ $data2->customer_name }}</p>
                    <p>Customer Contact: {{ $data2->customer_contact }}</p>
                    <p>Note: {{ $data2->note }}</p>
                    <p>Username: {{ $data2->username }}</p>
                </div>
            </div>
        @endif

        @if($data3 != null)
            <div class="mt-4">
                <h1 class="display-4 mb-4">Import History</h1>
                <div>
                    <p>Import ID: <a href="/admin/import_products/getImportDetailsDataByImportId/{{ $data3->id }}">{{ $data3->id }}</a></p>
                    <p>Invoice Number: {{ $data3->invoice_number }}</p>
                    <p>Import Date: {{ $data3->import_date }}</p>
                    <p>Supplier Name: {{ $data3->supplier_name }}</p>
                    <p>Username: {{ $data3->username }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure that you want to delete this record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" onclick="deleteProductAfterConfirm()" data-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('link_js')
    <script>
        let productIdForDelete = 0;

        function deleteProduct(productId) {
            productIdForDelete = productId;
        };

        function deleteProductAfterConfirm() {
            let xhr = new XMLHttpRequest();

            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    window.location = "/admin/products";
                }
            };

            xhr.open("delete", "/admin/products/" + productIdForDelete, false);
            xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
            xhr.send();
        }
    </script>
@endsection
