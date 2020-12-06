@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/saleStatuses/addSaleStatus.css') }}">
@endsection

@section('content')
    <div id="addSaleStatusContainer">
        <h1 class="display-4">Add Sale Status</h1>

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

        <form class="mt-4" enctype="multipart/form-data" method="POST" action="/admin/saleStatuses">
            {{csrf_field()}}

            <div class="form-group">
                <label for="txtSaleName">Name</label>
                <input type="text" class="form-control" id="txtSaleName" name="txtSaleName" placeholder="sale status name" required>
            </div>

            <div class="form-group">
                <label for="fileLogo">Upload Label</label>
                <input type="file" class="form-control-file" id="fileLabel" name="fileLabel">
            </div>

            <button class="btn btn-primary" type="submit">Add Sale Status</button>
            <button class="btn btn-danger" type="reset">Clear</button>
            <a href="/admin/saleStatuses">
                <button class="btn btn-warning" type="button">Back to Sale Statuses Dashboard</button>
            </a>
        </form>
    </div>
@endsection
