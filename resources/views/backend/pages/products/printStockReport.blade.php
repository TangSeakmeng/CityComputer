<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Report</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hanuman&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('backend/css/pages/products/stock_report.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
    <div class="header">
        <button class="btn btn-warning" onclick="backToProducts()">
            Back to Website
        </button>
        <h1 class="display-4">Stock Report</h1>
        <button class="btn btn-success" onclick="onClickPrint()">
            Print
        </button>
    </div>

    <div class="controllerContainer">
        <form>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="formCreatedDate">From Created Date</label>
                        <input type="date" class="form-control" id="formCreatedDate" name="formCreatedDate" placeholder="from date">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="toCreatedDate">To Created Date</label>
                        <input type="date" class="form-control" id="toCreatedDate" name="toCreatedDate" placeholder="from date">
                    </div>
                </div>
            </div>

            <div class="form-group p-4 overflow-auto" style="background-color: #D3D3D3; border-radius: 10px;">
                <label class="mb-4">Filtered Brands</label>

                <div class="groupBrands">
                    @foreach($data_brand as $key=>$item)
                        <div class="form-check float-left mr-5">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="checkbox{{$key}}"
                                   onchange="checkBoxBrandChanged({{ $item->id }})">
                            <label class="form-check-label" for="checkbox{{$key}}">{{ $item->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group p-4 overflow-auto" style="float:none; clear:both; margin-top: 10px; background-color: #D3D3D3; border-radius: 10px;">
                <label class="mb-4">Filtered Categories</label>

                <div class="groupBrands">
                    @foreach($data_category as $key=>$item)
                        <div class="form-check float-left mr-5">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="checkboxCategories{{$key}}"
                                   onchange="checkBoxCategoryChanged({{ $item->id }})">
                            <label class="form-check-label" for="checkboxCategories{{$key}}">{{ $item->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="float:none; clear:both; margin-top: 10px; text-align: left;">
                <button class="btn btn-primary" type="button" onclick="generateReport()">
                    Generate Report
                </button>
            </div>
        </form>
    </div>

    <div class="reportContainer">
        <div class="reportHeader">
            <h1 class="display-4">City Computer</h1>
            <div>
                <h2>Stock Report</h2>
                <p>Date: <span id="dtDateTimeNow"></span></p>
            </div>
        </div>

        <div class="tableContainer">
            <table class="table">
                <thead>
                    <tr id="theadTable">
                        <th scope="col">ID</th>
                        <th scope="col">Barcode</th>
                        <th scope="col">Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Stock</th>
                        <th scope="col">COS</th>
                        <th scope="col">Price</th>
                        <th scope="col">Discount</th>
                    </tr>
                </thead>
                <tbody id="tbodyTable"></tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="messageBody"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    document.querySelector('#dtDateTimeNow').innerHTML = convertDateTimeToString(new Date());

    function convertDateTimeToString(dt) {
        let dtNow = new Date(dt);
        return `${dtNow.getFullYear()}-${dtNow.getMonth() + 1}-${dtNow.getDate()} ${dtNow.getHours()}:${dtNow.getMinutes()}:${dtNow.getSeconds()}`;
    }

    function convertDateToString(dt) {
        let dtNow = new Date(dt);
        return `${dtNow.getFullYear()}-${dtNow.getMonth() + 1}-${dtNow.getDate()}`;
    }

    function backToProducts() {
        window.location = '/admin/products';
    }

    function onClickPrint() {
        window.print();
    }

    let arr_selectedBrands = [];
    let arr_selectedCategories = [];

    function findSelectedBrandIndex(brandId) {
        return arr_selectedBrands.findIndex((item) => {
            return item == brandId;
        });
    }

    function checkBoxBrandChanged(brandId) {
        let result = findSelectedBrandIndex(brandId);

        if(result == -1)
            arr_selectedBrands.push(brandId);
        else
            arr_selectedBrands.splice(result, 1);
    }

    function findSelectedCategoryIndex(categoryId) {
        return arr_selectedCategories.findIndex((item) => {
            return item == categoryId;
        });
    }

    function checkBoxCategoryChanged(categoryId) {
        let result = findSelectedCategoryIndex(categoryId);

        if(result == -1)
            arr_selectedCategories.push(categoryId);
        else
            arr_selectedCategories.splice(result, 1);
    }

    function generateReport() {
        try {
            let xhr = new XMLHttpRequest();
            let formData = new FormData();

            let fromCreatedDate = document.querySelector("#formCreatedDate").value;
            let toCreatedDate = document.querySelector("#toCreatedDate").value;
            let selectedBrands = arr_selectedBrands;
            let selectedCategories = arr_selectedCategories;

            if(fromCreatedDate != '' && toCreatedDate == '') {
                $('#exampleModal').modal('show');
                document.querySelector('#messageBody').innerHTML = 'Please select To Created Date.';
                return;
            }

            if(fromCreatedDate == '' && toCreatedDate != '') {
                $('#exampleModal').modal('show');
                document.querySelector('#messageBody').innerHTML = 'Please select From Created Date.';
                return;
            }

            formData.append("fromCreatedDate", fromCreatedDate);
            formData.append("toCreatedDate", toCreatedDate);
            formData.append("selectedBrands", JSON.stringify(selectedBrands));
            formData.append("selectedCategories", JSON.stringify(selectedCategories));

            xhr.onload = (format, data) => {
                const response = JSON.parse(xhr.responseText);

                if (xhr.status >= 200 && xhr.status < 300) {
                    let temp_brand = '';
                    document.querySelector('#tbodyTable').innerHTML = '';

                    response.data.forEach((item, index) => {
                        let dom = document.createElement('tr');

                        if(temp_brand == '' || temp_brand != item.brand_name) {
                            let dom2 = document.createElement('tr');
                            dom2.innerHTML = `<td colspan="8">Brand: ${item.brand_name}</td>`;
                            document.querySelector('#tbodyTable').appendChild(dom2);
                        }

                        dom.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${item.product_barcode}</td>
                            <td>${item.product_name}</td>
                            <td>${item.category_name}</td>
                            <td>${item.unit_in_stock}</td>
                            <td>${item.cost_of_sale}</td>
                            <td>${item.price}</td>
                            <td>${item.discount_price}</td>
                        `;

                        temp_brand = item.brand_name;
                        document.querySelector('#tbodyTable').appendChild(dom);
                    })
                }
                else {
                    $('#exampleModal').modal('show');
                    document.querySelector('#messageBody').innerHTML = response.message;
                }
            };

            xhr.open("POST", "/admin/report/getProducts", true);
            xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
            xhr.send(formData);
        } catch (e) {
            alert(e)
        }
    }
</script>
</html>
