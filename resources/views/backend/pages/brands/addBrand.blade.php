@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/brands/addbrand.css') }}">
@endsection

@section('content')
    <div id="addBrandContainer">
        <h1 class="display-4">Add Brand</h1>

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

        <form class="mt-4" enctype="multipart/form-data" method="POST" action="/admin/brands">
            {{csrf_field()}}

            <div class="form-group">
                <label for="txtBrandName">Name</label>
                <input type="text" class="form-control" id="txtBrandName" name="txtBrandName" placeholder="brand name" required>
            </div>

            <div class="form-group">
                <label for="fileLogo">Upload Logo</label>
                <input type="file" class="form-control-file" id="fileLogo" name="fileLogo" required>
            </div>

            <button class="btn btn-primary" type="submit">Add Brand</button>
            <button class="btn btn-danger" type="reset">Clear</button>
            <a href="/admin/brands">
                <button class="btn btn-warning" type="button">Back to Brands Dashboard</button>
            </a>
        </form>
    </div>
@endsection
