@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/suppliers/addSupplier.css') }}">
@endsection

@section('content')
    <div id="addSupplierContainer">
        <h1 class="display-4">Add Supplier</h1>

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

        <form class="mt-4" enctype="multipart/form-data" method="POST" action="/admin/suppliers">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="supplier name" required>
            </div>

            <div class="form-group">
                <label for="name">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="address" required>
            </div>

            <div class="form-group">
                <label for="name">Contact</label>
                <input type="text" class="form-control" id="contact" name="contact" placeholder="contact" required>
            </div>

            <div class="form-group">
                <label for="name">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="email" required>
            </div>

            <div class="form-group">
                <label for="name">Note</label>
                <input type="text" class="form-control" id="note" name="note" placeholder="note" required>
            </div>

            <button class="btn btn-primary" type="submit">Add Supplier</button>
            <button class="btn btn-danger" type="reset">Clear</button>
            <a href="/admin/suppliers">
                <button class="btn btn-warning" type="button">Back to Suppliers Dashboard</button>
            </a>
        </form>
    </div>
@endsection
