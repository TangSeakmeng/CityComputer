@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/settings/exchange_rate_style.css') }}">
@endsection

@section('content')
    <div id="exchangeRateContainer">
        <h1 class="display-4">Exchange Rate</h1>

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

        <div class="mt-4">
            <form action="/admin/exchange_rate" method="post">
                @csrf

                <div class="form-group">
                    <label for="txtExchangeRateIn">Exchange Rate In</label>
                    <input type="number" class="form-control" id="txtExchangeRateIn" name="txtExchangeRateIn" placeholder="enter exchange rate in" value="{{ $data[0]->value }}" required>
                </div>
                <div class="form-group">
                    <label for="txtExchangeRateOut">Exchange Rate Out</label>
                    <input type="number" class="form-control" id="txtExchangeRateOut" name="txtExchangeRateOut" placeholder="enter exchange rate out" value="{{ $data[1]->value }}" required>
                </div>

                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection
