@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/categories/addCategory.css') }}">
@endsection

@section('content')
    <div id="addCategoryContainer">
        <h1 class="display-4">Add Category</h1>

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

        <form class="mt-4" enctype="multipart/form-data" method="POST" action="/admin/categories/{{ $category->id }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="txtCategoryName">Name</label>
                <input type="text" class="form-control" id="txtCategoryName" name="txtCategoryName" placeholder="brand name" value="{{ $category->name }}" required>
            </div>

            <div class="form-group">
                <label for="selectSubCategoryOf">SubCategory of</label>
                <select class="form-control" id="selectSubCategoryOf" name="selectSubCategoryOf" required>
                    @foreach($parent_categories as $parent_category)
                        @if($parent_category->id == $category->subcategory_id)
                            <option value="{{ $parent_category->id }}" selected>{{ $parent_category->name }}</option>
                        @else
                            <option value="{{ $parent_category->id }}">{{ $parent_category->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary" type="submit">Save</button>
            <button class="btn btn-danger" type="reset">Clear</button>
            <a href="/admin/categories">
                <button class="btn btn-warning" type="button">Back to Categories Dashboard</button>
            </a>
        </form>
    </div>
@endsection
