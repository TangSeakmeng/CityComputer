@extends('backend.layout.layout')

@section('link_css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('backend/css/pages/sell_operation/style.css') }}">
@endsection

@section('content')
    <div id="sellContainer">
        <div class="sellHeader">
            <h1 class="display-4">Sell</h1>
            <a href="/admin/invoices">
                <button class="btn btn-primary">View Sell Transactions</button>
            </a>
        </div>

        <div class="sellContent">
            <div class="leftSide_container pr-4">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="txtKeyword">Keyword</label>
                        <input type="text" class="form-control" id="txtKeyword" placeholder="Enter keyword">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="selectOption">Option</label>
                        <select class="form-control" id="selectOption">
                            <option value="products.barcode">Barcode</option>
                            <option value="products.name">Product Name</option>
                            <option value="categories.name">Product Category</option>
                            <option value="brands.name">Product Brand</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2" style="margin-top: 30px">
                        <button class="btn btn-primary w-100" onclick="btnSearchProduct()">Go</button>
                    </div>
                </div>

                <div id="containerProductsPreview">

                </div>
            </div>
            <div class="rightSide_container">
                <table class="table mt-4">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Barcode</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Price</th>
                        <th scope="col">Total</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="tbodyAddToCardTable">
                        <tr style="text-align: center"><td colspan="7">No Any Product in Cart.</td></tr>
                    </tbody>
                </table>
                <div class="footerTableContainer">
                    <div class="totalQuantityContainer" id="totalQuantityContainer">Total Quantity: 0 Unit(s).</div>
                    <div class="subTotalContainer" id="subTotalContainer">Subtotal: 0 $.</div>
                </div>
                <button type="button" class="btn btn-primary w-100 mt-3" data-toggle="modal" data-target="#checkOutModal" id="btnCheckOutModal" disabled onclick="openCheckOutModal()">
                    Check Out Now!
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkOutModal" tabindex="-1" role="dialog" aria-labelledby="checkOutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkOutModalLabel">Check Out</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert" id="alertMessageContainer" style="display: none"></div>

                    <div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="txtCustomerName">Customer Name</label>
                                <input type="text" class="form-control" id="txtCustomerName" placeholder="enter customer name" value="General">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="txtCustomerContact">Customer Contact</label>
                                <input type="text" class="form-control" id="txtCustomerContact" placeholder="enter customer contact" value="General">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="txtNote">Note</label>
                            <input type="email" class="form-control" id="txtNote" placeholder="enter note">
                            <small id="txtNote" class="form-text text-muted">You can note about the sell operation.</small>
                        </div>
                    </div>

                    <table class="table mt-4">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Qty * Price</th>
                            <th scope="col">Total($)</th>
                            <th scope="col">Discount(%)</th>
                            <th scope="col">Subtotal($)</th>
                        </tr>
                        </thead>
                        <tbody id="tbodyCheckOutTable">
                            <tr style="text-align: center"><td colspan="7">No Any Product in Cart.</td></tr>
                        </tbody>
                    </table>

                    <div class="footerCheckOutTableContainer">
                        <div id="totalCheckOutQuantityContainer">Total Quantity: 0 Unit(s).</div>
                        <div id="discountCheckOutContainer">Discount: 0 $.</div>
                    </div>

                    <div class="footerCheckOutTableContainer" style="border-top: none">
                        <div id="subTotalCheckOutContainer_dollar">Subtotal ($): 0 $.</div>
                        <div id="subTotalCheckOutContainer_riel">Subtotal (R): 0 R.</div>
                    </div>

                    <div class="mt-4">
                        <div class="form-group">
                            <label for="selectPaymentMethod">Payment Method</label>
                            <select class="form-control" id="selectPaymentMethod">
                                <option value="Cash">Cash</option>
                                <option value="Online Transfer">Online Transfer</option>
                            </select>
                        </div>

                        <div class="form-row" class="customerPaymentContainer" style="padding-bottom: 20px; border-bottom: 1px solid black">
                            <div class="form-group col-md-6">
                                <label for="txtExchangeRateIn">Exchange Rate In</label>
                                <input type="number" class="form-control" id="txtExchangeRateIn" placeholder="enter exchange rate in" value="0">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="txtExchangeRateOut">Exchange Rate Out</label>
                                <input type="number" class="form-control" id="txtExchangeRateOut" placeholder="enter exchange rate out" value="0">
                            </div>
                        </div>

                        <div class="form-row mt-4" class="customerPaymentContainer" style="padding-bottom: 20px; border-bottom: 1px solid black">
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
                    <button type="button" class="btn btn-primary" id="btnPayNow" onclick="submitPayment()" style="display: none">Pay Now</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel"><div id="messageModalTitle"></div></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="messageModalBody"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('link_js')
    <script>
        let arr_resultSearchedProduct = [];
        let arr_addToCartProduct = [];

        document.getElementById("txtKeyword").focus();

        function getDefaultProductsForSellPreview() {
            document.getElementById('containerProductsPreview').innerHTML = '';

            try {
                let xhr = new XMLHttpRequest();

                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        const response = JSON.parse(xhr.responseText);
                        arr_resultSearchedProduct = response.data;
                        response.data.forEach((item, index) => {
                            let dom = document.createElement('div');
                            dom.setAttribute('class', 'productContainer');
                            dom.setAttribute('onclick', `addProductToCart(${index})`)
                            dom.innerHTML = `
                                <div class="barcodeContainer"><p>Code: ${item.product_barcode}</p></div>
                                <div class="imageContainer"><img src="{{ asset("/uploaded_images/products/") }}/${item.image_path}"></div>
                                <div class="textContainer"><p>${item.product_name}</p></div>
                                `;
                            document.getElementById('containerProductsPreview').appendChild(dom);
                        });

                        document.getElementById('txtExchangeRateIn').value = response.data2[0].value;
                        document.getElementById('txtExchangeRateOut').value = response.data2[1].value;
                    }
                    else {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    }
                };

                xhr.open("GET", `/admin/getDefaultProductsForSellPreview/`);
                xhr.send();
            } catch (e) {
                alert(e)
            }
        }

        getDefaultProductsForSellPreview();

        function btnSearchProduct() {
            let keyword = document.querySelector('#txtKeyword').value;
            let option = document.querySelector('#selectOption').value;

            document.getElementById('containerProductsPreview').innerHTML = '';

            try {
                let xhr = new XMLHttpRequest();
                let formData = new FormData();

                formData.append("keyword", keyword);
                formData.append("option", option);

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        const response = JSON.parse(xhr.responseText);
                        arr_resultSearchedProduct = response.data;

                        response.data.forEach((item, index) => {
                            let dom = document.createElement('div');
                            dom.setAttribute('class', 'productContainer');
                            dom.setAttribute('onclick', `addProductToCart(${index})`)
                            dom.innerHTML = `
                                <div class="barcodeContainer"><p>Code: ${item.product_barcode}</p></div>
                                <div class="imageContainer"><img src="{{ asset("/uploaded_images/products/") }}/${item.image_path}"></div>
                                <div class="textContainer"><p>${item.product_name}</p></div>
                                `;
                            document.getElementById('containerProductsPreview').appendChild(dom);
                        });

                        document.getElementById("txtKeyword").value = '';
                        document.getElementById("txtKeyword").focus();
                    }
                    else {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    }
                };

                xhr.open("POST", "/admin/searchProductsForSellPreviewByOption/", true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }

        function addProductToCart(productIndex) {
            let product = arr_resultSearchedProduct[productIndex];
            addProductToCart2(product);
        }

        function addProductToCart2(product) {
            try {
                let index = arr_addToCartProduct.findIndex((item) => item.product_id == product.product_id);
                let temp_product = "";

                if(index < 0) {
                    temp_product = {
                        product_id: product.product_id,
                        product_barcode: product.product_barcode,
                        product_name: product.product_name,
                        qty: 1,
                        unit_in_stock: product.unit_in_stock,
                        price: product.price,
                        discount: 0,
                    }

                    arr_addToCartProduct.push(temp_product);
                } else {
                    let old_qty = arr_addToCartProduct[index].qty;

                    if(arr_addToCartProduct[index].unit_in_stock <= old_qty) {
                        document.querySelector('#messageModalTitle').innerHTML = 'Error';
                        document.querySelector('#messageModalBody').innerHTML = '<p>This product is out of unit in stock.</p>';
                        $('#messageModal').modal('show');

                        document.getElementById("txtKeyword").value = '';
                        document.getElementById("txtKeyword").focus();
                    }
                    else {
                        arr_addToCartProduct[index].qty = ++old_qty;
                    }
                }

                renderArr_addToCartProductToTable();
            } catch(e) {
                alert(e);
            }
        }

        document.querySelector('#txtKeyword').addEventListener('keyup', (e) => {
            if(e.keyCode === 13) {
                let keyword = document.querySelector('#txtKeyword').value;
                let option = 'products.barcode';

                document.getElementById('containerProductsPreview').innerHTML = '';

                try {
                    let xhr = new XMLHttpRequest();
                    let formData = new FormData();

                    formData.append("keyword", keyword);
                    formData.append("option", option);

                    xhr.onload = (format, data) => {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            const response = JSON.parse(xhr.responseText);
                            arr_resultSearchedProduct = response.data;

                            response.data.forEach((item, index) => {
                                let dom = document.createElement('div');
                                dom.setAttribute('class', 'productContainer');
                                dom.setAttribute('onclick', `addProductToCart(${index})`)
                                dom.innerHTML = `
                                <div class="barcodeContainer"><p>Code: ${item.product_barcode}</p></div>
                                <div class="imageContainer"><img src="{{ asset("/uploaded_images/products/") }}/${item.image_path}"></div>
                                <div class="textContainer"><p>${item.product_name}</p></div>
                                `;
                                document.getElementById('containerProductsPreview').appendChild(dom);
                            });

                            document.getElementById("txtKeyword").value = '';
                            document.getElementById("txtKeyword").focus();

                            if(response.data.length == 1) {
                                addProductToCart2(response.data[0]);
                            }
                        }
                        else {
                            const response = JSON.parse(xhr.responseText);
                            alert(response.message);
                        }
                    };

                    xhr.open("POST", "/admin/searchProductsForSellPreviewByOption/", true);
                    xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                    xhr.send(formData);
                } catch (e) {
                    alert(e)
                }
            }
        })

        function renderArr_addToCartProductToTable() {
            try {
                document.querySelector('#tbodyAddToCardTable').innerHTML = "";
                calculateTotalQtyAndSubtotalAndRenderFooterTable();

                if(arr_addToCartProduct.length == 0) {
                    let dom = document.createElement('tr');
                    dom.setAttribute('style', 'text-align: center');
                    dom.innerHTML = '<td colspan="7">No Any Product in Cart.</td>';
                    document.querySelector('#tbodyAddToCardTable').appendChild(dom);
                    document.getElementById('btnCheckOutModal').disabled = true;
                    return;
                }

                arr_addToCartProduct.forEach((item, index) => {
                    let dom = document.createElement('tr');
                    let total = item.price * item.qty;

                    // onkeyup="productInCartQtyChanged(${index})"
                    // oninput="productInCartPriceChanged(${index})"

                    dom.innerHTML = `
                        <th scope="row">${item.product_id}</th>
                        <td>${item.product_barcode}</td>
                        <td>${item.product_name}</td>
                        <td>
                            <input type="number"
                                    class="form-control"
                                    id="productInCartQty${index}"
                                    onchange="productInCartQtyChanged(${index})"
                                    value="${item.qty}"
                                    style="width: 100px;"
                            >
                        </td>
                        <td>
                            <input type="number"
                                    class="form-control"
                                    id="productInCartPrice${index}"
                                    onchange="productInCartPriceChanged(${index})"
                                    value="${item.price}"
                                    style="width: 100px;"
                                    step="any"
                            >
                        </td>
                        <td>
                            <p style="margin-top: 8px">${total}$</p>
                        </td>
                        <td>
                            <button class="btn btn-danger" onclick="deleteRowFromArr_addToCartProductToTable(${index})">x</button>
                        </td>
                    `;

                    document.querySelector('#tbodyAddToCardTable').appendChild(dom);
                });

                document.getElementById('btnCheckOutModal').disabled = false;
            } catch(e) {
                alert(e);
            }
        }

        function deleteRowFromArr_addToCartProductToTable(rowIndex) {
            try {
                arr_addToCartProduct.splice(rowIndex, 1);
                renderArr_addToCartProductToTable();
            } catch(e) {
                alert(e);
            }
        }

        function calculateTotalQtyAndSubtotalAndRenderFooterTable() {
            try {
                let total_qty = 0;
                let subtotal = 0;

                arr_addToCartProduct.forEach((item) => {
                   total_qty += item.qty;
                   subtotal += (item.price * item.qty);
                });

                document.querySelector('#totalQuantityContainer').innerHTML = `Total Quantity: ${total_qty} Unit(s).`;
                document.querySelector('#subTotalContainer').innerHTML = `Subtotal: ${subtotal} $.`;
            } catch(e) {
                alert(e);
            }
        }

        function productInCartQtyChanged(productIndex) {
            let updated_qty = document.querySelector(`#productInCartQty${productIndex}`).value;
            updated_qty = updated_qty == '' ? 0 : updated_qty;

            if(arr_addToCartProduct[productIndex].unit_in_stock < updated_qty) {
                document.querySelector('#messageModalTitle').innerHTML = 'Error';
                document.querySelector('#messageModalBody').innerHTML = '<p>This product is out of unit in stock.</p>';
                $('#messageModal').modal('show');

                document.querySelector(`#productInCartQty${productIndex}`).value = arr_addToCartProduct[productIndex].unit_in_stock;
                renderArr_addToCartProductToTable();
                return;
            } else if(updated_qty <= 0) {
                document.querySelector('#messageModalTitle').innerHTML = 'Error';
                document.querySelector('#messageModalBody').innerHTML = '<p>Quantity can not be zero or negative.</p>';
                $('#messageModal').modal('show');

                document.querySelector(`#productInCartQty${productIndex}`).value = 1;
                renderArr_addToCartProductToTable();
                return;
            }

            arr_addToCartProduct[productIndex].qty = parseInt(updated_qty);
            renderArr_addToCartProductToTable();
        }

        function productInCartPriceChanged(productIndex) {
            let updated_price = document.querySelector(`#productInCartPrice${productIndex}`).value;
            updated_price = updated_price == '' ? 0 : updated_price;
            arr_addToCartProduct[productIndex].price = parseFloat(updated_price);
            renderArr_addToCartProductToTable()
        }

        function renderArr_ProductsCheckoutPreviewToTable() {
            try {
                document.querySelector('#tbodyCheckOutTable').innerHTML = "";
                calculateTotalQtyAndSubtotalAndRenderFooterTable();

                if(arr_addToCartProduct.length == 0) {
                    let dom = document.createElement('tr');
                    dom.setAttribute('style', 'text-align: center');
                    dom.innerHTML = '<td colspan="7">No Any Product in Cart.</td>';
                    document.querySelector('#tbodyAddToCardTable').appendChild(dom);
                    document.getElementById('btnPayNow').disabled = true;
                    return;
                }

                arr_addToCartProduct.forEach((item, index) => {
                    let dom = document.createElement('tr');
                    let total = item.price * item.qty;

                    let discount_amount = total * (item.discount / 100);
                    discount_amount = roundNumber(discount_amount);
                    let subtotal = total - discount_amount;
                    subtotal = roundNumber(subtotal);

                    dom.innerHTML = `
                        <th scope="row">${item.product_id}</th>
                        <td>${item.product_name}</td>
                        <td style="width: 120px">
                            ${item.qty} * ${item.price}
                        </td>
                        <td>
                            <p style="margin-top: 8px">${total}$</p>
                        </td>
                        <td>
                            <input type="number"
                                    class="form-control"
                                    id="productInCartDiscount${index}"
                                    onchange="productInCartDiscountChanged(${index})"
                                    value="${item.discount}"
                                    style="width: 100px;"
                            >
                        </td>
                        <td>
                            <p style="margin-top: 8px">${subtotal}$</p>
                        </td>
                    `;

                    document.querySelector('#tbodyCheckOutTable').appendChild(dom);
                });

                document.getElementById('btnPayNow').disabled = false;
            } catch(e) {
                alert(e);
            }
        }

        function openCheckOutModal() {
            renderArr_ProductsCheckoutPreviewToTable();
            calculateTotalQtyAndSubtotalAndRenderCheckOutFooterTable();
            txtMoneyReceivedChanged();
        }

        function productInCartDiscountChanged(productIndex) {
            let discount_pct = document.querySelector(`#productInCartDiscount${productIndex}`).value;

            if(discount_pct < 0) {
                document.querySelector('#messageModalTitle').innerHTML = 'Error';
                document.querySelector('#messageModalBody').innerHTML = '<p>This product is out of unit in stock.</p>';
                $('#messageModal').modal('show');

                document.querySelector(`#productInCartDiscount${productIndex}`).value = 0;
            } else {
                arr_addToCartProduct[productIndex].discount = discount_pct;
            }

            calculateTotalQtyAndSubtotalAndRenderCheckOutFooterTable();
            renderArr_ProductsCheckoutPreviewToTable();
        }

        let FINAL_SUBTOTAL = 0;
        let FINAL_DISCOUNT = 0;

        function calculateTotalQtyAndSubtotalAndRenderCheckOutFooterTable() {
            try {
                let total_qty = 0;
                let subtotal = 0;
                let discount_amount = 0;

                arr_addToCartProduct.forEach((item) => {
                    total_qty += item.qty;

                    let total = item.price * item.qty;
                    subtotal += total;

                    let temp_discount_amount = total * (item.discount / 100);
                    temp_discount_amount = Math.round(temp_discount_amount * 1000) / 1000;
                    discount_amount += temp_discount_amount;
                });

                FINAL_SUBTOTAL = subtotal - discount_amount;
                FINAL_DISCOUNT = discount_amount;

                let exchangeRateIn = parseInt(document.querySelector('#txtExchangeRateIn').value);
                let totalRielExchangeIn = FINAL_SUBTOTAL * exchangeRateIn;

                totalRielExchangeIn = totalRielExchangeIn.toLocaleString('en-US', {
                    style: 'currency',
                    currency: 'khm',
                });

                document.querySelector('#totalCheckOutQuantityContainer').innerHTML = `Total Quantity: ${total_qty} Unit(s).`;
                document.querySelector('#discountCheckOutContainer').innerHTML = `Discount: ${roundNumber(discount_amount)} $.`;
                document.querySelector('#subTotalCheckOutContainer_dollar').innerHTML = `Subtotal ($): ${roundNumber(subtotal - discount_amount)} $.`;
                document.querySelector('#subTotalCheckOutContainer_riel').innerHTML = `Subtotal (R): ${totalRielExchangeIn} R.`;
            } catch(e) {
                alert(e);
            }
        }

        function submitPayment() {
            try {
                let customer_name = document.querySelector('#txtCustomerName').value;
                let customer_contact = document.querySelector('#txtCustomerContact').value;
                let note = document.querySelector('#txtNote').value == '' ? 'N/A' : document.querySelector('#txtNote').value;

                if(!customer_name || !customer_contact) {
                    document.getElementById('alertMessageContainer').style.display = 'block';
                    document.querySelector('#alertMessageContainer').innerHTML = '<p style="margin: 0;">Please fill the customer info.</p>';
                    return;
                }

                document.getElementById('alertMessageContainer').style.display = 'none';

                let xhr = new XMLHttpRequest();
                let formData = new FormData();

                let money_received_in_dollar = parseFloat(document.querySelector('#txtMoneyReceivedInDollar').value);
                let money_received_in_riel = parseInt(document.querySelector('#txtMoneyReceivedInRiel').value);
                let payment_method = document.querySelector('#selectPaymentMethod').value;
                let exchangeRateIn = parseInt(document.querySelector('#txtExchangeRateIn').value);
                let exchangeRateOut = parseInt(document.querySelector('#txtExchangeRateOut').value);

                formData.append("customer_name", customer_name);
                formData.append("customer_contact", customer_contact);
                formData.append("note", note);
                formData.append("discount", FINAL_DISCOUNT);
                formData.append("subtotal", FINAL_SUBTOTAL);
                formData.append("exchange_rate_in", exchangeRateIn);
                formData.append("exchange_rate_out", exchangeRateOut);
                formData.append("payment_method", payment_method);
                formData.append("exchange_rate_out", exchangeRateOut);
                formData.append("money_received_in_dollar", money_received_in_dollar);
                formData.append("money_received_in_riel", money_received_in_riel);

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        const response = JSON.parse(xhr.responseText);
                        let invoiceId = response.invoiceId;

                        submitPaymentDetails(invoiceId);
                    }
                    else {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    }
                };

                xhr.open("POST", "/admin/addInvoice", true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }

        function submitPaymentDetails(invoiceId) {
            try {
                let xhr = new XMLHttpRequest();
                let formData = new FormData();

                formData.append("invoiceId", invoiceId);
                formData.append("data", JSON.stringify(arr_addToCartProduct));

                xhr.onload = (format, data) => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        const response = JSON.parse(xhr.responseText);
                        console.log(response);
                        window.location = '/admin/invoice/' + invoiceId;
                    }
                    else {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    }
                };

                xhr.open("POST", "/admin/addInvoiceDetails", true);
                xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
                xhr.send(formData);
            } catch (e) {
                alert(e)
            }
        }


        function txtMoneyReceivedInDollarChanged() {
            let dollars = parseFloat(document.querySelector('#txtMoneyReceivedInDollar').value);

            if(dollars < 0) {
                document.querySelector('#txtMoneyReceivedInDollar').value = 0;
                return;
            }

            txtMoneyReceivedChanged();
        }

        function txtMoneyReceivedInRielChanged() {
            let dollars = parseFloat(document.querySelector('#txtMoneyReceivedInRiel').value);

            if(dollars < 0) {
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

            if(total <= 0) {
                document.getElementById("btnPayNow").style.display = "none";

                document.getElementById("txtMoneyReturnInDollar").value = 0;
                document.getElementById("txtMoneyReturnInRiel").value = 0;
            } else {
                document.getElementById("btnPayNow").style.display = "block";

                document.getElementById("txtMoneyReturnInDollar").value = convertNumberToUSCurrency(roundNumber(total - FINAL_SUBTOTAL));
                document.getElementById("txtMoneyReturnInRiel").value = convertNumberToKHCurrency(exchangeRateOut * roundNumber(total - FINAL_SUBTOTAL));
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
    </script>
@endsection
