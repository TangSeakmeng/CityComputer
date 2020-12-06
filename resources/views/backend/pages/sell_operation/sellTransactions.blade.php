@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/sell_operation/sellTransactions.css') }}">
@endsection

@section('content')
    <div id="transactionContainer">
        <div class="transactionHeader">
            <h1 class="display-4">Sell Transaction</h1>

            <a href="/admin/sell_operation/">
                <button class="btn btn-primary mt-2">Sell Now</button>
            </a>
        </div>

        <div class="transactionContent mt-4">
            <table id="transactionTable" class="table">
                <thead class="thead-dark">
                <th>Invoice ID</th>
                <th>Invoice Number</th>
                <th>Invoice Date</th>
                <th>Customer Name</th>
                <th>Contact</th>
                <th>Discount</th>
                <th>Subtotal</th>
                <th>Managed By</th>
                <th>Paid Status</th>
                <th>Action</th>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('link_js')
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script type="text/javascript"  src=" https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
    <script>
        $(document).ready(function() {
            $('#transactionTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "/admin/invoices/getDataTableInvoicesData",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    {
                        "data": 'id',
                        "name": 'id',
                        orderable: false
                    },
                    {
                        "data": 'invoice_number',
                        "name": 'invoice_number'
                    },
                    {
                        "data": 'invoice_date',
                        "name": 'invoice_date'
                    },
                    {
                        "data": 'customer_name',
                        "name": 'customer_name'
                    },
                    {
                        "data": 'customer_contact',
                        "name": 'customer_contact'
                    },
                    {
                        "data": 'discount',
                        "name": 'discount'
                    },
                    {
                        "data": 'subtotal',
                        "name": 'subtotal'
                    },
                    {
                        "data": 'username',
                        "name": 'username'
                    },
                    {
                        "data": 'status',
                        "name": 'status'
                    },
                    {
                        "data": 'action',
                        "name": 'action',
                        orderable: false
                    },
                ]
            });
        });
    </script>
@endsection
