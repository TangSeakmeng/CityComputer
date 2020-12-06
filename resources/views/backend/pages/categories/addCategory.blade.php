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

        <form class="mt-4" enctype="multipart/form-data" method="POST" action="/admin/categories">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="category name" required>
            </div>

            <div class="form-group">
                <label for="subcategory_id">SubCategory of</label>
                <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                    @foreach($parent_categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary" type="submit">Add Category</button>
            <button class="btn btn-danger" type="reset">Clear</button>
            <a href="/admin/categories">
                <button class="btn btn-warning" type="button">Back to Categories Dashboard</button>
            </a>
        </form>
    </div>
@endsection
