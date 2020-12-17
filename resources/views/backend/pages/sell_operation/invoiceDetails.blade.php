@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/sell_operation/viewInvoiceDetail.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.ckeditor.com/4.15.1/basic/ckeditor.js"></script>
@endsection

@section('content')
    <div id="transactionContainer">
        <div class="transactionHeader">
            <div class="d-flex align-items-center justify-content-between">
                <h1 class="display-4">View Sell Transaction</h1>
            </div>
            <div>
                <a href="/admin/invoices/"><button class="btn btn-warning">Sell Transactions</button></a>
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#returnProductModal">Return Product</button>
                <a href="/admin/invoice/{{ $data->id }}"><button class="btn btn-success">Print Invoice</button></a>
                @if($data->subtotal > ($data->money_received_in_dollar + ($data->money_received_in_riel / $data->exchange_rate_in)))
                    <button class="btn btn-warning" onclick="openPayMoreDialog()">Pay More</button>
                @endif
            </div>
        </div>

        <input type="hidden" value="{{ $data->id }}" id="txtInvoiceID">
        <input type="hidden" value="{{ $data->invoice_date }}" id="txtInvoiceDate">
        <input type="hidden" value="{{ $data->money_received_in_dollar + ($data->money_received_in_riel / $data->exchange_rate_in) }}" id="txtDeposit">
        <input type="hidden" value="{{ $data->subtotal }}" id="txtSubtotal">
        <input type="hidden" value="{{ $data->exchange_rate_in }}" id="txtExchangeRateIn">
        <input type="hidden" value="{{ $data->exchange_rate_out }}" id="txtExchangeRateOut">

        <div class="transactionContent mt-4">
            <div>
                <p>Invoice ID     : {{ $data->id }}</p>
                <p>Invoice Date   : {{ $data->invoice_date }}</p>
                <p>Invoice Number : {{ $data->invoice_number }}</p>
                <p>Customer Name  : {{ $data->customer_name }}</p>
                <p>Customer Contact : {{ $data->customer_contact }}</p>
            </div>

            <div>
                <p>Note: {{ $data->note }}</p>
                <p>Discount: {{ number_format($data->discount, 2) }}$</p>
                <p>Subtotal: {{ number_format($data->subtotal, 2) }}$</p>
                <p>Exchange Rate In: {{ number_format($data->exchange_rate_in) }}R / 1$</p>
                <p>Exchange Rate Out: {{ number_format($data->exchange_rate_out) }}R / 1$</p>
            </div>

            <div>
                <p>Payment Method: {{ $data->payment_method }}</p>
                <p>Money Received In Dollar: {{ number_format($data->money_received_in_dollar, 2) }}$</p>
                <p>Money Received In Riel: {{ number_format($data->money_received_in_riel) }}R</p>
                <p>Managed By: {{ $data->username }}</p>
                <p>Created At     : {{ $data->created_at }}</p>
            </div>

        </div>

        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Product ID</th>
                <th scope="col">Product Barcode</th>
                <th scope="col">Product Name</th>
                <th scope="col">Qty</th>
                <th scope="col">Price</th>
                <th scope="col">Discount</th>
            </tr>
            </thead>
            <tbody>
            @if(sizeof($data2) == 0)
                <tr>
                    <td colspan="6" style="text-align: center">No Row(s).</td>
                </tr>
            @endif

            @foreach($data2 as $item)
                <tr>
                    <th scope="row">{{ $item->product_id }}</th>
                    <td>{{ $item->product_barcode }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->qty }} unit(s)</td>
                    <td>{{ $item->price }}$</td>
                    <td>{{ $item->discount }}%</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mb-4 mt-4" style="border-top: 2px solid grey; padding-top: 20px">
            <h1 class="display-4">Product Serial Number</h1>
            <button class="btn btn-success" onclick="btnShowProductSerialNumberDialog()">
                Add Serial Number
            </button>
        </div>

        @foreach($arr_alertMessageToUser as $item)
            {!!html_entity_decode($item)!!}
        @endforeach

        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Product ID</th>
                    <th scope="col">Product Barcode</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Product Serial Number</th>
                    <th scope="col">Warranty Period</th>
                    <th scope="col">Expire Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            @if(sizeof($data3) == 0)
                <tr>
                    <td colspan="6" style="text-align: center">No Row(s).</td>
                </tr>
            @endif

            @foreach($data3 as $item)
                <tr>
                    <th scope="row">{{ $item->product_id }}</th>
                    <td>{{ $item->product_barcode }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->serial_number }}</td>
                    <td>{{ $item->warranty_period}} Month(s)</td>
                    <td>{{ $item->expired_date}}</td>
                    <td>
                        <button type="button" class="btn btn-danger" onclick="deleteProductSoldSerialNumber({{ $item->id }})">
                            x
                        </button>
                        <button type="button" class="btn btn-success">
                            Return
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mb-4 mt-4" style="border-top: 2px solid grey; padding-top: 20px">
            <h1 class="display-4">Return History</h1>
        </div>

        <div>
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Return ID</th>
                    <th scope="col">Product ID</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Return Quantity</th>
                    <th scope="col">Return Date</th>
                </tr>
                </thead>
                <tbody>
                    @if(sizeof($data_5) == 0)
                        <tr>
                            <td colspan="5" style="text-align: center">No Row(s).</td>
                        </tr>
                    @endif

                    @foreach($data_5 as $item)
                        <tr>
                            <th scope="row">{{ $item->return_id }}</th>
                            <th>{{ $item->product_id }}</th>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->return_qty }}</td>
                            <td>{{ $item->return_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Product Serial Number</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success alert-block mt-2" id="addSerialNumberSuccessMessage" style="display: none"></div>
                    <div class="alert alert-danger alert-block mt-2" id="addSerialNumberErrorMessage" style="display: none"></div>

                    <div class="form-group">
                        <label for="selectSoldProduct">Product Name</label>
                        <select class="form-control" id="selectSoldProduct">
                            @foreach($data2 as $item)
                                <option value="{{ $item->product_id }}">{{ $item->product_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="txtProductSerialNumber">Product Serial Number</label>
                        <input type="text" class="form-control" id="txtProductSerialNumber" placeholder="enter product serial number">
                    </div>

                    <div class="form-group">
                        <label for="txtWarrantyPeriod">Warranty Period (month)</label>
                        <input type="number" class="form-control" id="txtWarrantyPeriod" placeholder="enter number of month, example: 12">
                        <small class="form-text text-muted">Please enter warranty period in month.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="btnAddProductSerialNumber()">Add</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="example2Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModal2Label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2Label">Pay More</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success alert-block mt-2" id="PayNowSuccessMessage" style="display: none"></div>
                    <div class="alert alert-danger alert-block mt-2" id="PayNowErrorMessage" style="display: none"></div>

                    <div class="">
                        <div class="d-flex justify-content-between align-items-center">
                            <p>Deposit: <span id="labelDeposit"></span></p>
                            <p>Balance: <span id="labelBalance"></span></p>
                        </div>

                        <div class="form-row" class="customerPaymentContainer" style="padding-bottom: 20px; border-bottom: 1px solid black">
                            <div class="form-group col-md-5">
                                <label for="txtMoneyReceivedInDollar">Money received in Dollar</label>
                                <input type="number" step="any" class="form-control" id="txtMoneyReceivedInDollar" placeholder="enter cash in dollars" value="0" onchange="txtMoneyReceivedInDollarChanged()">
                            </div>
                            <div class="form-group col-md-2 text-center">
                                <label>And</label>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="txtMoneyReceivedInRiel">Money received in Riel</label>
                                <input type="number" class="form-control" id="txtMoneyReceivedInRiel" placeholder="enter cash in riel" value="0" onchange="txtMoneyReceivedInRielChanged()">
                            </div>

                            <label id="labelTotalMoneyReceived">Total Money Received: 0 $</label>
                        </div>

                        <div class="form-row mt-4" class="customerPaymentContainer">
                            <div class="form-group col-md-5">
                                <label for="txtMoneyReturnInDollar">Money return in Dollar</label>
                                <input type="text" class="form-control" id="txtMoneyReturnInDollar" value="0" readonly>
                            </div>
                            <div class="form-group col-md-2 text-center">
                                <label>Or</label>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="txtMoneyReturnInRiel">Money return in Riel</label>
                                <input type="text" class="form-control" id="txtMoneyReturnInRiel" value="0" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="payMoreClicked()" style="display: none" id="btnPayNow">Pay Now</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Delete Sold Product Serial Number</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure that you want to delete this record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" onclick="btnDeleteSoldProductSerialNumber()">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="returnProductModal" tabindex="-1" role="dialog" aria-labelledby="returnProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnProductLabel">Return Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" role="alert" id="alertReturnProductSuccessMessage" style="display: none"></div>
                    <div class="alert alert-danger" role="alert" id="alertReturnProductErrorMessage" style="display: none"></div>

                    <form id="formAddReturnProduct">
                        <div class="form-group">
                            <label for="selectProductName">Product Name</label>
                            <select class="form-control" id="selectProductName_ForReturn">
                                @foreach($data2 as $key=>$item)
                                    <option value="{{ $item->product_id }}">
                                        {{ $item->product_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="txtReturnQuantity">Return Quantity</label>
                            <input type="number" class="form-control" id="txtReturnQuantity" placeholder="enter return quantity">
                        </div>
                    </form>

                    <button type="button" class="btn btn-primary w-100" onclick="btnAddReturnProduct()">Add</button>

                    <table class="table mt-3">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Delete</th>
                        </tr>
                        </thead>
                        <tbody id="tbodyReturnProducts">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitReturnProductsOperation()">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('link_js')
    <script>
        function btnShowProductSerialNumberDialog() {
            $('#exampleModal').modal('show');
        }

        function openPayMoreDialog() {
            $('#example2Modal').modal('show');
        }

        let ID = 0;

        function deleteProductSoldSerialNumber(id) {
            ID = id;
            $('#confirmModal').modal('show');
        }

        function btnDeleteSoldProductSerialNumber() {
            try {
                let xhr = new XMLHttpRequest();

                xhr.onload = (format, data) => {
                    let response = JSON.parse(xhr.responseText);

                    if (xhr.status >= 200 && xhr.status < 300) {
                        let route = window.location.pathname + window.location.search;
                        window.location = route;
                    }
                    else {
                        alert('Error:' + response.error)
                    }
                };

                xhr.open("GET", `/admin/delete_sold_product_serial_number/${ID}`);
                xhr.send();
            } catch (e) {
                alert(e)
            }
        }

        function btnAddProductSerialNumber() {
            document.getElementById('addSerialNumberErrorMessage').style.display = 'none';
            document.getElementById('addSerialNumberErrorMessage').style.display = 'none';

            let productId = document.querySelector('#selectSoldProduct').value;
            let productSerialNumber = document.querySelector('#txtProductSerialNumber').value;
            let warranty_period = document.querySelector('#txtWarrantyPeriod').value;
            let invoice_id = document.querySelector('#txtInvoiceID').value;
            let invoice_date =  new Date(document.querySelector('#txtInvoiceDate').value);
            let expired_date = new Date(invoice_date.setMonth( invoice_date.getMonth() + parseInt(warranty_period)));
            let str_expired_date = `${expired_date.getFullYear()}-${expired_date.getMonth() + 1}-${expired_date.getDate()} ${expired_date.getHours()}:${expired_date.getMinutes()}:${expired_date.getSeconds()}`;

            try {
                if(!productSerialNumber) {
                    document.querySelector('#addSerialNumberErrorMessage').innerHTML = "Product Serial Number can be empty.";
                    document.getElementById('addSerialNumberErrorMessage').style.display = 'block';
                    return;
                }

                if(!warranty_period) {
                    document.querySelector('#addSerialNumberErrorMessage').innerHTML = "Warranty Period can be empty.";
                    document.getElementById('addSerialNumberErrorMessage').style.display = 'block';
                    return;
                }

                let xhr = new XMLHttpRequest();
                let formData = new FormData();

                formData.append("invoice_id", invoice_id);
                formData.append("product_id", productId);
                formData.append("serial_number", productSerialNumber);
                formData.append("warranty_period", warranty_period);
                formData.append("expired_date", str_expired_date);

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        let response = JSON.parse(xhr.responseText);
                        document.querySelector('#addSerialNumberSuccessMessage').innerHTML = response.success;
                        document.getElementById('addSerialNumberSuccessMessage').style.display = 'block';
                    }
                    else {
                        const response = JSON.parse(xhr.responseText);
                        document.querySelector('#addSerialNumberErrorMessage').innerHTML = response.error;
                        document.getElementById('addSerialNumberErrorMessage').style.display = 'block';
                    }
                };

                xhr.open("POST", `/admin/add_sold_product_serial_number/`, true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }

        let DEPOSIT = document.querySelector('#txtDeposit').value;
        let SUBTOTAL = document.querySelector('#txtSubtotal').value;
        let BALANCE = roundNumber(SUBTOTAL - DEPOSIT);

        document.querySelector('#labelDeposit').innerHTML = `<strong>${roundNumber(DEPOSIT)}$</strong>`;
        document.querySelector('#labelBalance').innerHTML = `<strong>${roundNumber(BALANCE)}$</strong>`;

        function txtMoneyReceivedInDollarChanged() {
            let dollars = parseFloat(document.querySelector('#txtMoneyReceivedInDollar').value);

            if(dollars < 0 || !dollars) {
                document.querySelector('#txtMoneyReceivedInDollar').value = 0;
                return;
            }

            txtMoneyReceivedChanged();
        }

        function txtMoneyReceivedInRielChanged() {
            let riels = parseFloat(document.querySelector('#txtMoneyReceivedInRiel').value);

            if(riels < 0 || !riels) {
                document.querySelector('#txtMoneyReceivedInRiel').value = 0;
                return;
            }

            txtMoneyReceivedChanged();
        }

        function txtMoneyReceivedChanged() {
            let dollars = parseFloat(document.querySelector('#txtMoneyReceivedInDollar').value);
            let riels = parseInt(document.querySelector('#txtMoneyReceivedInRiel').value);
            let exchangeRateIn = parseInt(document.querySelector('#txtExchangeRateIn').value);
            let exchangeRateOut = parseInt(document.querySelector('#txtExchangeRateOut').value);

            let total = parseFloat(riels / exchangeRateIn);
            total += dollars;
            total = roundNumber(total);

            document.querySelector('#labelTotalMoneyReceived').innerHTML = `Total Money Received: ${total} $.`

            DEPOSIT = parseFloat(DEPOSIT);
            SUBTOTAL = parseFloat(SUBTOTAL);

            if(DEPOSIT + total < SUBTOTAL) {
                document.getElementById("btnPayNow").style.display = "none";

                document.getElementById("txtMoneyReturnInDollar").value = 0;
                document.getElementById("txtMoneyReturnInRiel").value = 0;
            } else {
                document.getElementById("btnPayNow").style.display = "block";

                document.getElementById("txtMoneyReturnInDollar").value = convertNumberToUSCurrency(roundNumber(total - BALANCE));
                document.getElementById("txtMoneyReturnInRiel").value = convertNumberToKHCurrency(exchangeRateOut * roundNumber(total - BALANCE));
            }
        }

        function roundNumber(number) {
            return Math.round(number * 1000) / 1000;
        }

        function convertNumberToKHCurrency(number) {
            return number.toLocaleString('en-US', {
                style: 'currency',
                currency: 'khm',
            });
        }

        function convertNumberToUSCurrency(number) {
            return number.toLocaleString('en-US', {
                style: 'currency',
                currency: 'usd',
            });
        }

        function payMoreClicked() {
            document.getElementById('PayNowSuccessMessage').style.display = 'none';
            document.getElementById('PayNowErrorMessage').style.display = 'none';

            let dollars = parseFloat(document.querySelector('#txtMoneyReceivedInDollar').value);
            let riels = parseInt(document.querySelector('#txtMoneyReceivedInRiel').value);
            let invoiceId = parseInt(document.querySelector('#txtInvoiceID').value);

            try {
                if(dollars === '') {
                    document.querySelector('#PayNowErrorMessage').innerHTML = "Money Received In Dollar can be empty.";
                    document.getElementById('PayNowErrorMessage').style.display = 'block';
                    return;
                }

                if(riels === '') {
                    document.querySelector('#PayNowErrorMessage').innerHTML = "Money Received In Riel can be empty.";
                    document.getElementById('PayNowErrorMessage').style.display = 'block';
                    return;
                }

                let xhr = new XMLHttpRequest();
                let formData = new FormData();

                formData.append("invoice_id", invoiceId);
                formData.append("money_received_in_dollar", dollars);
                formData.append("money_received_in_riel", riels);

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        let response = JSON.parse(xhr.responseText);
                        document.querySelector('#PayNowSuccessMessage').innerHTML = response.message;
                        document.getElementById('PayNowSuccessMessage').style.display = 'block';

                        document.getElementById('btnPayNow').style.display = 'none';
                    }
                    else {
                        const response = JSON.parse(xhr.responseText);
                        document.querySelector('#PayNowErrorMessage').innerHTML = response.error;
                        document.getElementById('PayNowErrorMessage').style.display = 'block';
                    }
                };

                xhr.open("POST", `/admin/invoicedetails/pay_more`, true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }

        let arr_returnProduct = [];
        let arr_soldProduct = [];

        function getInvoiceDetailsByInvoiceId() {
            try {
                let invoice_id = document.querySelector('#txtInvoiceID').value;

                let xhr = new XMLHttpRequest();

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        let response = JSON.parse(xhr.responseText);
                        arr_soldProduct = response.data;

                        console.log(arr_soldProduct);
                    }
                    else {
                        let response = JSON.parse(xhr.responseText);
                        alert(response.error);
                    }
                };

                xhr.open("GET", `/admin/invoices/getInvoiceDetails/` + invoice_id);
                xhr.send();
            } catch (e) {
                alert(e)
            }
        }

        getInvoiceDetailsByInvoiceId();

        function btnAddReturnProduct() {
            document.getElementById('alertReturnProductErrorMessage').style.display = 'none';

            try {
                let product_id = document.getElementById('selectProductName_ForReturn').value;
                let return_qty = parseInt(document.querySelector('#txtReturnQuantity').value);
                let productIndex = arr_soldProduct.findIndex((item) => item.product_id == product_id);
                let product_name = arr_soldProduct[productIndex].product_name;
                let sold_qty = parseInt(arr_soldProduct[productIndex].qty + "");

                if(document.querySelector('#txtReturnQuantity').value == '') {
                    document.querySelector('#alertReturnProductErrorMessage').innerHTML = 'Return Quantity can not be empty.';
                    document.getElementById('alertReturnProductErrorMessage').style.display = 'block';
                    document.querySelector('#txtReturnQuantity').value = 1;
                    return;
                }

                if(return_qty <= 0) {
                    document.querySelector('#alertReturnProductErrorMessage').innerHTML = 'Return Quantity can not be negative or equal zero.';
                    document.getElementById('alertReturnProductErrorMessage').style.display = 'block';
                    document.querySelector('#txtReturnQuantity').value = 1;
                    return;
                }

                let index = arr_returnProduct.findIndex((item) => item.product_id == product_id);

                if(index >= 0) {
                    let old_qty = arr_returnProduct[index].return_qty;

                    if(old_qty + return_qty > sold_qty) {
                        document.querySelector('#alertReturnProductErrorMessage').innerHTML = 'Return Quantity can not greater than number of sold quantity.';
                        document.getElementById('alertReturnProductErrorMessage').style.display = 'block';
                        document.querySelector('#txtReturnQuantity').value = 1;
                        return;
                    } else {
                        arr_returnProduct[index].return_qty = old_qty + return_qty;
                    }
                } else {
                    if(return_qty > sold_qty) {
                        document.querySelector('#alertReturnProductErrorMessage').innerHTML = 'Return Quantity can not greater than number of sold quantity.';
                        document.getElementById('alertReturnProductErrorMessage').style.display = 'block';
                        return;
                    }

                    arr_returnProduct.push({ product_id, product_name, sold_qty, return_qty });
                }

                renderArrReturnProduct();
            } catch (e) {
                alert(e);
            }
        }

        function deleteReturnProduct(productIndex) {
            arr_returnProduct.splice(productIndex, 1);
            renderArrReturnProduct();
        }

        function renderArrReturnProduct() {
            try {
                document.querySelector('#tbodyReturnProducts').innerHTML = '';

                arr_returnProduct.forEach((item, index) => {
                    let dom = document.createElement('tr');

                    dom.innerHTML = `
                        <th scope="row">${index + 1}</th>
                        <td>${item.product_name}</td>
                        <td>${item.return_qty}</td>
                        <td>
                            <button class="btn btn-danger" onclick="deleteReturnProduct(${index})">x</button>
                        </td>
                    `;

                    document.querySelector('#tbodyReturnProducts').appendChild(dom);
                })
            } catch (e) {
                alert(e);
            }
        }

        document.querySelector('#formAddReturnProduct').addEventListener('submit', (e) => {
            e.preventDefault();
        })

        function submitReturnProductsOperation() {
            try {
                if(arr_returnProduct == [])
                    return;

                let invoice_id = document.querySelector('#txtInvoiceID').value;

                let xhr = new XMLHttpRequest();
                let formData = new FormData();

                formData.append("invoice_id", invoice_id);
                formData.append("data", JSON.stringify(arr_returnProduct));

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        let route = window.location.pathname + window.location.search;
                        window.location = route;
                    }
                    else {
                        let response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    }
                };

                xhr.open("POST", `/admin/returnSoldProduct/`, true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }
    </script>
@endsection
