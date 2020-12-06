@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/home.css') }}">
@endsection

@section('content')
    <div id="homeContainer">
        <div class="welcomeText">
            <h3 class="display-4">Welcome to City Computer Admin Panel!</h3>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            <div style="display: flex; text-align: center; justify-content: center; align-items: center">
                <a href="/admin/sell_operation/"><button class="btn btn-primary">Sell Now</button></a>
                <a href="/admin/import_products/create"><button  class="btn btn-primary ml-4">Import Now</button></a>
                <input type="text" class="form-control w-25 ml-4" id="txtInvoiceNumber" placeholder="enter invoice barcode or number">
                <input type="text" class="form-control w-25 ml-4" id="txtProductSerialNumber" placeholder="enter product serial number">
            </div>
        </div>
    </div>
@endsection
