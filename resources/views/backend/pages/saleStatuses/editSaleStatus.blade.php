@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/saleStatuses/addSaleStatus.css') }}">
@endsection

@section('content')
    <div id="addSaleStatusContainer">
        <h1 class="display-4">Edit Sale Status</h1>

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

        <form class="mt-4" enctype="multipart/form-data" method="POST" action="/admin/saleStatuses/{{ $saleStatus->id }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="txtSaleName">Name</label>
                <input type="text" class="form-control" id="txtSaleName" name="txtSaleName" placeholder="sale status name" value="{{ $saleStatus->name }}">
            </div>

            @if($saleStatus->image_path != '')
                <label>Uploaded Logo</label>
                <div class="uploadedLabel">
                    <img src="{{ url('uploaded_images/sale_statuses/' . (trim($saleStatus->image_path))) }}" required>
                </div>
            @endif

            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="checkboxNoImage" name="checkboxNoImage">
                <label class="form-check-label" for="checkboxNoImage">No Image</label>
            </div>

            <div class="form-group">
                <label for="fileLabel">Upload Label</label>
                <input type="file" class="form-control-file" id="fileLabel" name="fileLabel">
            </div>

            <input type="hidden" name="oldImagePath" value="{{ trim($saleStatus->image_path) }}">

            <button class="btn btn-primary" type="submit">Save</button>
            <a href="/admin/saleStatuses">
                <button class="btn btn-warning" type="button">Back to Brands Dashboard</button>
            </a>
        </form>
    </div>
@endsection
