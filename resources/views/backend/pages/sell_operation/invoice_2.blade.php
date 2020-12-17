<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hanuman&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('backend/css/pages/sell_operation/invoice_2_style.css') }}">
    <script type="text/javascript" src="{{ asset('backend/js/JsBarcode.all.min.js') }}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
<div class="header">
    <button class="btn btn-warning" onclick="backToSell()">
        Back to Website
    </button>
    <h1 class="display-4">Invoice</h1>
    <button class="btn btn-success" onclick="onClickPrint()">
        Print
    </button>
</div>

<div class="invoiceContainer">
    <div class="invoiceHeader">
        <div class="firstColumn">
            <h4>Sale Contact</h4>
            <p>068 996 199</p>
            <p>010 996 199</p>
            <h4>Service Contact</h4>
            <p>069 965 055</p>
            <p>086 965 555</p>
            <p>076 777 053</p>
        </div>
        <div class="secondColumn">
            <h1 class="khmerFont">វិក័យប័ត្រ</h1>
            <h1>INVOICE</h1>
        </div>
        <div class="thirdColumn">
            <div class="barcodeContainer">
                <svg id="barcode"></svg>
            </div>
            <p class="khmerFont">អាសយដ្ធាន: ផ្ទះលេខ​​ ០១BEo ផ្លូវ ១៣៨</p>
            <p class="khmerFont">សង្កាត់ផ្សារដេប៉ូII ខណ្ឌទួលគោក ក្រុងភ្នំពេញ</p>
            <p>Facebook Page: City Computer</p>
            <p>E-mail: sreymean996199@gmail.com</p>
        </div>
    </div>

    <input type="hidden" value="{{ $data1->invoice_number }}" id="txtInvoiceNumber">

    <div class="invoiceHeader_2">
        <div class="invoiceHeader_2__leftside">
            <p>Customer Name &nbsp;&nbsp;&nbsp;&nbsp;: {{ $data1->customer_name }}</p>
            <p>Customer Contact &nbsp;: {{ $data1->customer_contact }}</p>
        </div>

        <div class="invoiceHeader_2__rightside">
            <p>Invoice Date &nbsp;: {{ $data1->invoice_date }}</p>
            <p>Invoice By &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $data1->name }}</p>
        </div>
    </div>

    <div class="tableContainer">
        <table class="table">
            <tbody>
            <tr>
                <th>
                    <p class="khmerFont">ល.រ</p>
                    <p>No.</p>
                </th>
                <th>
                    <p class="khmerFont">ឈ្មោះទំនិញ</p>
                    <p>Description</p>
                </th>
                <th >
                    <p class="khmerFont">ចំនួន</p>
                    <p>Quantity</p>
                </th>
                <th>
                    <p class="khmerFont">តម្លៃរាយ</p>
                    <p>Unit Price</p>
                </th>
                <th>
                    <p class="khmerFont">តម្លៃសរុប</p>
                    <p>Amount</p>
                </th>
            </tr>
            @foreach($data2 as $key => $item)
                <tr>
                    <td style="width: 2%;">{{ $key + 1 }}</td>
                    <td style="text-align: left;">{{ $item->name }}</td>
                    <td style="width: 10%;">{{ $item->qty }}</td>
                    <td style="width: 18%;">{{ $item->price - ($item->price * ($item->discount / 100)) }}$</td>
                    <td style="width: 18%;">{{ ($item->qty * $item->price) - (($item->qty * $item->price) * ($item->discount / 100)) }}$</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" rowspan="3"></td>
                <td style="font-weight: 800; text-align: right;">
                    <span class="khmerFont">(សរុប)</span>&nbsp;Total:</td>
                <td>{{ round($data1->subtotal * 1000) / 1000 }}$</td>​​​​​​
            </tr>​​​​
            <tr>
                <td style="font-weight: 800; text-align: right;">
                    <span class="khmerFont">(អោយមុន)</span>&nbsp;Deposit:</td>
                <td>{{ round(($data1->money_received_in_dollar + ($data1->money_received_in_riel / $data1->exchange_rate_in)) * 1000) / 1000 }}$</td>​​​​
            </tr>
            <tr>
                <td style="font-weight: 800; text-align: right;">
                    <span class="khmerFont">(នៅខ្វះ)</span>&nbsp;Balance:</td>
                <td>{{ round(($data1->subtotal - ($data1->money_received_in_dollar + ($data1->money_received_in_riel / $data1->exchange_rate_in))) * 1000) / 1000 }}$</td>
            </tr>
            </tbody>
        </table>

        <div class="signatureContainer">
            <div>
                <p class="khmerFont">អតិថិជន</p>
                <p>Client</p>
            </div>
            <div>
                <p class="khmerFont">អ្នកត្រួតពិនិត្យទំនិញ</p>
                <p>Controller</p>
            </div>
            <div>
                <p class="khmerFont">អ្នកលក់</p>
                <p>Seller</p>
            </div>
        </div>

        <div class="invoiceFooter">
            <div>
                <p class="khmerFont">បញ្ជាក់: មុនពេលទទួលត្រូវពិនិត្យគុណភាព និងបរិមាណ ទិញហើយមិនអាចដូរវិញបានទេ</p>
                <div>
                    <p>
                        Terms and Conditions:
                    </p>
                    <ul>
                        <li>1 Month On Part and Service 5 Years (only new computer)</li>
                        <li>6 Month Warranty on Software</li>
                        <li>No Warranty on Adapter (Laptop), Keyboard, Mouse, Fan, Speaker, Burning Part, Power Supply and Software</li>
                        <li>Goods Sold are not returnable.</li>
                        <li>Printer: All Printers are one year warranty on services only. No warranty on parts.</li>
                        <li>Warranty is voided if the company seal is broken, missed, accident, or allowed by other party.</li>
                    </ul>
                </div>
            </div>

            <div style="text-align: right;">
                <p>ABA (Ngov Sangly): 000 217 412</p>
                <p><span​​​​​​​​​​​​​​​​​ class="khmerFont">អេស៊ីលីដាទាន់ចិត្ត</span>: 010 996 199</p>
            </div>
        </div>
    </div>
</div>
</body>

<script>
    function backToSell() {
        window.location = '/admin/sell_operation';
    }

    function onClickPrint() {
        window.print();
    }

    JsBarcode('#barcode', document.querySelector('#txtInvoiceNumber').value);
</script>
</html>
