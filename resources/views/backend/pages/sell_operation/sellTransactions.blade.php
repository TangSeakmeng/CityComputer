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


        <table class="table mt-4">
            <thead class="thead-dark">
                <tr>
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
                </tr>
            </thead>
            <tbody>
                @php
                    foreach ($data2 as $item) {
                        $result1 = $item->money_received_in_dollar + ($item->money_received_in_riel / $item->exchange_rate_in);
                        $result2 = $item->subtotal <= $result1;

                        if($result2) {
                             echo "
                                <tr>
                                    <td>{$item->id}</td>
                                    <td>{$item->invoice_number}</td>
                                    <td>{$item->invoice_date}</td>
                                    <td>{$item->customer_name}</td>
                                    <td>{$item->customer_contact}</td>
                                    <td>{$item->discount}</td>
                                    <td>{$item->subtotal}</td>
                                    <td>{$item->username}</td>
                                    <td>
                                        <div style='text-align: center'>
                                            <img src='/assets/icons/check.png' style='width: 30px' />
                                        </div>
                                    </td>
                                    <td>
                                        <a href='/admin/invoicedetails/{$item->id}'>
                                            <button class='btn btn-success'>View</button>
                                        </a>
                                    </td>
                                </tr>
                            ";
                        }
                        else {
                            echo "
                                <tr>
                                    <td>{$item->id}</td>
                                    <td>{$item->invoice_number}</td>
                                    <td>{$item->invoice_date}</td>
                                    <td>{$item->customer_name}</td>
                                    <td>{$item->customer_contact}</td>
                                    <td>{$item->discount}$</td>
                                    <td>{$item->subtotal}$</td>
                                    <td>{$item->username}</td>
                                    <td>
                                        <div style='text-align: center'>
                                            <img src='/assets/icons/close.png' style='width: 30px' />
                                        </div>
                                    </td>
                                    <td>
                                        <a href='/admin/invoicedetails/{$item->id}'>
                                            <button class='btn btn-success'>View</button>
                                        </a>
                                    </td>
                                </tr>
                        "   ;
                        }
                    }
                @endphp

            <tr>
                <td colspan="6" style="text-align: right">Total:</td>
                <td colspan="4">{{ $data3->sum_invoices_total == "" ? "0" : $data3->sum_invoices_total }}$</td>
            </tr>
            </tbody>
        </table>

        <h1 class="display-4 mt-4" style="font-size: 40px;">All Transaction</h1>

        <div class="transactionContent mt-4">
            <table id="transactionTable" class="table" data-page-length='50'>
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
