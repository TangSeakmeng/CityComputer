@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/import_products/style.css') }}">
@endsection

@section('content')
    <div id="transactionContainer">
        <div class="transactionHeader">
            <h1 class="display-4">Import Transaction</h1>

            <a href="/admin/import_products/create">
                <button class="btn btn-primary mt-2">Add Transaction</button>
            </a>
        </div>

        <div class="transactionContent mt-4">
            <table id="transactionTable" class="table">
{{--                table-bordered table-striped--}}
                <thead class="thead-dark">
                    <th>Import ID</th>
                    <th>Invoice Number</th>
                    <th>Import Date</th>
                    <th>Import Total</th>
                    <th>Supplier</th>
                    <th>Managed By</th>
                    <th>Created At</th>
                    <th>Action</th>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('link_js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
        crossorigin="anonymous"></script>
    <script type="text/javascript"  src=" https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
    <script>
        $(document).ready(function() {
            $('#transactionTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "/admin/import_products/getDataTableImportsData",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    {
                        "data": 'id',
                        "name": 'id'
                    },
                    {
                        "data": 'invoice_number',
                        "name": 'invoice_number'
                    },
                    {
                        "data": 'import_date',
                        "name": 'import_date'
                    },
                    {
                        "data": 'import_total',
                        "name": 'import_total'
                    },
                    {
                        "data": 'supplier_name',
                        "name": 'supplier_name'
                    },
                    {
                        "data": 'username',
                        "name": 'username'
                    },
                    {
                        "data": 'created_at',
                        "name": 'created_at'
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
