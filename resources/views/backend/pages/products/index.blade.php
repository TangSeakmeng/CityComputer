@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/products/styles.css') }}">
@endsection

@section('content')
    <div id="productsContainer">
        <div class="productsHeader">
            <h1 class="display-4">Products</h1>

            <a href="/admin/products/create">
                <button class="btn btn-primary mt-2">Add Product</button>
            </a>
        </div>

        <div class="formSearchProduct mt-4">
            <form action="/admin/products/searchProducts" method="post">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Keyword</label>
                        <input
                            type="text"
                            class="form-control"
                            id="txtKeyword"
                            name="txtKeyword"
                            placeholder="search"
                            style="width: 100%;"
                            value="{{ isset($inputValue) ? $inputValue : '' }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="exampleFormControlSelect2">Search By</label>
                        <select class="form-control" id="selectSearchBy" name="selectSearchBy">
                            <option value="barcode" {{ isset($inputValue) ? $selectedOption == 'barcode' ? 'selected' : '' : '' }}>Barcode</option>
                            <option value="name" {{ isset($inputValue) ? $selectedOption == 'name' ? 'selected' : '' : '' }}>Product Name</option>
                            <option value="category" {{ isset($inputValue) ? $selectedOption == 'category' ? 'selected' : '' : '' }}>Category</option>
                            <option value="brand" {{ isset($inputValue) ? $selectedOption == 'brand' ? 'selected' : '' : '' }}>Brand</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <button class="btn btn-primary" style="width: 100%; margin-top: 30px" type="submit">Search Product</button>
                    </div>
                </div>
            </form>
        </div>

        <div id="productTableContainer" class="mt-4">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Barcode</th>
                        <th scope="col">Name</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Category</th>
                        <th scope="col">Cost of Sale</th>
                        <th scope="col">Unit In Stock</th>
                        <th scope="col">Sale Price</th>
                        <th scope="col">Discount Price</th>
                        <th scope="col">Published</th>
                        <th scope="col">Sale Status</th>
                        <th scope="col">Image</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">

                    @foreach($data as $product)
                        @if($product->unit_in_stock < 5)
                            <tr style="background-color: #FEB5C0;">
                        @else
                            <tr>
                        @endif
                            <td scope="row">{{ $product->id }}</td>
                            <td>{{ $product->barcode }}</td>
                            <td style="width: 300px">{{ $product->name }}</td>
                            <td>{{ $product->brand_name }}</td>
                            <td>{{ $product->category_name }}</td>
                            <td>{{ $product->cost_of_sale }}</td>
                            <td>{{ $product->unit_in_stock }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->discount_price }}</td>
                            <td>
                                <div class="form-check text-center">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="checkboxPublished"
                                           id="checkBoxPublished{{ $product->id }}"
                                           onchange="updateProductPublished({{ $product->id }})"
                                           {{ $product->published == true ? 'checked' : '' }}
                                    />
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control" id="selectSaleStatus{{ $product->id }}" onchange="updateProductSaleStatus({{ $product->id }})">
                                        @foreach($saleStatuses as $saleStatus)
                                            @if($saleStatus->name == $product->sale_name)
                                                <option value="{{$saleStatus->id}}" selected>{{$saleStatus->name}}</option>
                                            @else
                                                <option value="{{$saleStatus->id}}">{{$saleStatus->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td>
                                <img src="{{URL::to('uploaded_images/products/' . (trim($product->image_path)))}}" class="imagePreview">
                            </td>
                            <td class="groupActionButtons">
                                <a href="/admin/products/{{ $product->id }}/">
                                    <button class="btn btn-success">
                                        <img src="{{ url('/assets/icons/view_2.png') }}" class="actionButtonIcon" />
                                    </button>
                                </a>
                                <a href="/admin/products/{{ $product->id }}/edit">
                                    <button class="btn btn-warning">
                                        <img src="{{ url('/assets/icons/edit_2.png') }}" class="actionButtonIcon" />
                                    </button>
                                </a>
                                <button
                                    type="button"
                                    class="btn btn-danger"
                                    data-toggle="modal"
                                    data-target="#exampleModal"
                                    onclick="deleteProduct({{$product->id}})"
                                >
                                    <img src="{{ url('/assets/icons/rubbish_2.png') }}" class="actionButtonIcon" />
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {!! $data->links() !!}
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

        <!-- Modal -->
        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Delete Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Delete Successfully!
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
        let productIdForDelete = 0;

        function deleteProduct(productId) {
            productIdForDelete = productId;
        };

        function deleteProductAfterConfirm() {
            let xhr = new XMLHttpRequest();

            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    let route = window.location.pathname + window.location.search;
                    window.location = route;
                }
            };

            xhr.open("delete", "/admin/products/" + productIdForDelete, false);
            xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
            xhr.send();
        }

        function updateProductPublished(productId) {
            let result = document.querySelector('#checkBoxPublished' + productId).checked;
            result = result === true ? 1 : 0;
            let xhr = new XMLHttpRequest();
            let formData = new FormData();

            formData.append("published", result);

            xhr.onload = (format, data) => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    const response = JSON.parse(xhr.responseText);
                }
                else {
                    const response = JSON.parse(xhr.responseText);
                    alert(response.message);
                }
            };

            xhr.open("POST", "/admin/products/updateProductPublished/" + productId, true);
            xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
            xhr.send(formData);
        }

        function updateProductSaleStatus(productId) {
            let result = document.querySelector('#selectSaleStatus' + productId).value;
            let xhr = new XMLHttpRequest();
            let formData = new FormData();

            formData.append("sale_status_id", result);

            xhr.onload = (format, data) => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    const response = JSON.parse(xhr.responseText);
                }
                else {
                    const response = JSON.parse(xhr.responseText);
                    alert(response.message);
                }
            };

            xhr.open("POST", "/admin/products/updateProductSaleStatus/" + productId, true);
            xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
            xhr.send(formData);
        }

        {{--function generateProductsIntoTableBody() {--}}
        {{--    document.querySelector('#productTableBody').innerHTML = "";--}}

        {{--    let xhr = new XMLHttpRequest();--}}

        {{--    xhr.onload = () => {--}}
        {{--        if (xhr.status >= 200 && xhr.status < 300) {--}}
        {{--            // $('#exampleModal2').modal('show');--}}

        {{--            let result = JSON.parse(xhr.responseText);--}}
        {{--            let saleStatuses = "";--}}

        {{--            console.log(result)--}}

        {{--            result.data.data.forEach((product) => {--}}
        {{--                saleStatuses = "";--}}

        {{--                result.saleStatuses.forEach((saleStatus) => {--}}
        {{--                    if(product.sale_name == saleStatus.name)--}}
        {{--                        saleStatuses += `<option value="${saleStatus.id}" selected>${saleStatus.name}</option>`;--}}
        {{--                    else--}}
        {{--                        saleStatuses += `<option value="${saleStatus.id}">${saleStatus.name}</option>`;--}}
        {{--                });--}}

        {{--                result2 = product.published == true ? 'checked' : '';--}}

        {{--                let dom = document.createElement('tr');--}}
        {{--                dom.innerHTML = `--}}
        {{--                    <td scope="row">${product.id}</td>--}}
        {{--                    <td>${product.barcode}</td>--}}
        {{--                    <td style="width: 300px">${product.name}</td>--}}
        {{--                    <td>${product.brand_name}</td>--}}
        {{--                    <td>${product.category_name}</td>--}}
        {{--                    <td>${product.cost_of_sale}</td>--}}
        {{--                    <td>${product.unit_in_stock}</td>--}}
        {{--                    <td>${product.price}</td>--}}
        {{--                    <td>${product.discount_price}</td>--}}
        {{--                    <td>--}}
        {{--                            <div class="form-check text-center">--}}
        {{--                                <input class="form-check-input"--}}
        {{--                                       type="checkbox"--}}
        {{--                                       name="checkboxPublished"--}}
        {{--                                       id="checkBoxPublished${product.id}"--}}
        {{--                                       onchange="updateProductPublished(${product.id})"--}}
        {{--                                       ${result2}--}}
        {{--                            />--}}
        {{--                        </div>--}}
        {{--                    </td>--}}
        {{--                    <td>--}}
        {{--                        <div class="form-group">--}}
        {{--                            <select class="form-control" id="selectSaleStatus${product.id}" onchange="updateProductSaleStatus(${product.id})">--}}
        {{--                                ${saleStatuses}--}}
        {{--                            </select>--}}
        {{--                        </div>--}}
        {{--                    </td>--}}
        {{--                    <td>--}}
        {{--                        <img src="{{ asset('uploaded_images/products/') }}/${product.image_path}" class="imagePreview">--}}
        {{--                    </td>--}}
        {{--                    <td class="groupActionButtons">--}}
        {{--                        <a href="/admin/products/${product.id}/">--}}
        {{--                            <button class="btn btn-success">--}}
        {{--                                <img src="{{ asset('/assets/icons/view_2.png') }}" class="actionButtonIcon">--}}
        {{--                            </button>--}}
        {{--                        </a>--}}
        {{--                        <a href="/admin/products/${product.id}/edit">--}}
        {{--                            <button class="btn btn-warning">--}}
        {{--                                <img src="{{ asset('/assets/icons/edit_2.png') }}" class="actionButtonIcon">--}}
        {{--                            </button>--}}
        {{--                        </a>--}}
        {{--                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" onclick="deleteProduct(${product.id})">--}}
        {{--                            <img src="{{ asset('/assets/icons/rubbish_2.png') }}" class="actionButtonIcon">--}}
        {{--                        </button>--}}
        {{--                    </td>--}}
        {{--                `;--}}

        {{--                document.querySelector('#productTableBody').appendChild(dom);--}}
        {{--            })--}}
        {{--        }--}}
        {{--    };--}}

        {{--    xhr.open("get", "/admin/products/getAllProducts", true);--}}
        {{--    xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');--}}
        {{--    xhr.send();--}}
        {{--}--}}

        // generateProductsIntoTableBody();
    </script>
@endsection
