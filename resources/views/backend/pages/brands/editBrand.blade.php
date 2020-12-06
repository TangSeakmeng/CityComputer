@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/brands/addBrand.css') }}">
@endsection

@section('content')
    <div id="addBrandContainer">
        <h1 class="display-4">Edit Brand</h1>

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

        <form class="mt-4" enctype="multipart/form-data" method="POST" action="/admin/brands/{{ $brand->id }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="txtBrandName">Name</label>
                <input type="text" class="form-control" id="txtBrandName" name="txtBrandName" placeholder="brand name" value="{{ $brand->name }}" required>
            </div>

            <label>Uploaded Logo</label>
            <div class="uploadedLogo">
                <img src="{{ url('uploaded_images/brands/' . (trim($brand->imagePath))) }}">
            </div>

            <div class="form-group">
                <label for="fileLogo">Upload Logo</label>
                <input type="file" class="form-control-file" id="fileLogo" name="fileLogo">
            </div>

            <input type="hidden" name="oldImagePath" value="{{ trim($brand->imagePath) }}">

            <button class="btn btn-primary" type="submit">Save</button>
            <a href="/admin/brands">
                <button class="btn btn-warning" type="button">Back to Brands Dashboard</button>
            </a>
        </form>
    </div>
@endsection
