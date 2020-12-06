<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('/backend/css/pages/sell_operation/invoice_style.css') }}">

    <script type="text/javascript" src="{{ asset('/backend/js/JsBarcode.all.min.js') }}"></script>
</head>
<body>
<div class="invoiceHeader">
    <button class="btn btn-warning" onclick="goBackToSellPage()">Back to Dashboard</button>
    <p>Invoice Preview</p>
    <button class="btn btn-success" onclick="submitPayment()">Print</button>
</div>

<div class="invoiceContainer" id="invoiceContainer">
    <div class="invoiceContainer_invoiceHeader">
        <div class="invoiceHeader_name">
            <h1>Computer Store</h1>
            <p>Address: 9822 East Talbot Avenue Annandale, VA 22003</p>
            <p>Tel: (263) 318-8446 / (578) 930-1220 / (269) 448-5415</p>
        </div>
        <div class="invoiceHeader_label">
            <h1>Invoice</h1>
        </div>
    </div>

    <input type="hidden" id="txtInvoiceNumber" value="{{ $data1->invoice_number }}">

    <div class="invoiceTable">
        <div class="tableHeader">
            <div class="tableHeader_left">
                <p>Customer Name: {{ $data1->customer_name }}</p>
                <p>Customer Contact: {{ $data1->customer_contact }}</p>

                <svg id="barcode"></svg>
            </div>
            <div class="tableHeader_right">
                <p>Invoice Number: {{ $data1->invoice_number }}</p>
                <p>Invoice Date: {{ $data1->invoice_date }}</p>
                <p>Managed By: {{ $data1->name }}</p>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="thead-dark"></thead>
            <tbody>

            <tr>
                <th scope="col" >No</th>
                <th scope="col">Description</th>
                <th scope="col">Qty</th>
                <th scope="col">Price</th>
                <th scope="col">Discount</th>
                <th scope="col">Subtotal</th>
            </tr>

            @foreach($data2 as $key=>$value)
                <tr>
                    <th style="width: 5%;">{{ $key }}</th>
                    <td style="width: 40%;" class="setTextleft">
                        {{ $value->name }}
                    </td>
                    <td style="width: 10%;">{{ $value->qty }}</td>
                    <td style="width: 10%;">{{ $value->price }}</td>
                    <td style="width: 10%;">{{ $value->discount }}</td>
                    <td style="width: 15%;">
                        {{ round(floatval(($value->qty * $value->price) - (($value->qty * $value->price) * $value->discount)) / 1000) * 1000 }}
                    </td>
                </tr>
            @endforeach

            <tr>
                <td colspan="4" rowspan="4" ></td>
                <td style="text-align: right;"><h4>Total :</h4></td>
                <td>{{ $data1->subtotal }}</td>
            </tr>
            <tr>
                <td style="text-align: right;"><h4>Deposit :</h4></td>
                <td>
                    {{ floatval($data1->money_received_in_dollar) + (round((floatval($data1->money_received_in_riel) / floatval($data1->exchange_rate_in)) / 1000) * 1000) }}
                </td>
            </tr>
            <tr>
                <td style="text-align: right;"><h4>Balance :</h4></td>
                <td>{{ floatval($data1->subtotal) - floatval($data1->money_received_in_dollar) + (round((floatval($data1->money_received_in_riel) / floatval($data1->exchange_rate_in)) / 1000) * 1000) }}</td>
            </tr>
            </tbody>
        </table>

        <div class="warrantyContainer">
            <p>Thanks for your supports and please come again.</p><br>
            <p>Warning:</p>
            <p>
                - Computer: 1 year warranty on part and service (exclude mouse, keyboard, fan, power supply and speaker), and no warranty if seal broken,
                misuse, electronic shock or burned, accident or modification by none BEST authorized professional.
            </p>
            <p>
                - Printer: 1 year warranty on part and service if used original ink. If not warranty only service one year.
            </p>
        </div>

        <div class="signatureContainer">
            <p>Customer Sign</p>
            <p>Delivery Sign</p>
            <p>Controller Sign</p>
            <p>Prepare Sign</p>
            <p>Seller Sign</p>
        </div>
    </div>
</div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function generateBarcode() {
        let value = document.querySelector('#txtInvoiceNumber').value;
        JsBarcode('#barcode', value);
    }

    generateBarcode();

    function submitPayment() {
        window.print();
    }

    function goBackToSellPage() {
        window.location = "/admin/sell_operation/";
    }
</script>
</html>
