<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportProductsController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductSerialNumberController;
use App\Http\Controllers\SaleStatusesController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\SellOperationController;
use App\Http\Controllers\SlideShowController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\UsersController;

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Psy\Util\Json;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('/frontend/index');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/admin', AdminController::class);

Route::resource('/admin/brands', BrandsController::class);
Route::resource('/admin/categories', CategoriesController::class);
Route::resource('/admin/import_products', ImportProductsController::class);
Route::resource('/admin/saleStatuses', SaleStatusesController::class);
Route::resource('/admin/suppliers', SuppliersController::class);
Route::resource('/admin/products', ProductsController::class);
Route::resource('/admin/users', UsersController::class);
Route::resource('/admin/exchange_rate', ExchangeRateController::class);
Route::resource('/admin/slideshow', SlideShowController::class);

Route::get('/admin/products/getAllProducts', [ProductsController::class, 'getAllProducts']);
Route::get('/admin/products/getProductByProductId/{id}', [ProductsController::class, 'getProductByProductId']);
Route::get('/admin/products/updatePublishedStatus/{id}/{published}', [ProductsController::class, 'updatePublishedStatus']);
Route::get('/admin/products/updateSaleStatus/{id}/saleStatus', [ProductsController::class, 'updateSaleStatus']);
Route::post('/admin/products/searchProducts', [ProductsController::class, 'searchProducts']);
Route::post('/admin/products/updateProduct/{id}', [ProductsController::class, 'updateProduct']);
Route::post('/admin/products/updateProductPublished/{id}', [ProductsController::class, 'updateProductPublished']);
Route::post('/admin/products/updateProductSaleStatus/{id}', [ProductsController::class, 'updateProductSaleStatus']);

Route::post('/admin/users/updateUserActivate/{id}', [UsersController::class, 'updateUserActivate']);

Route::get('/admin/import_products/getImportDetailsDataByImportId/{id}', [ImportProductsController::class, 'getImportDetailsDataByImportId']);
Route::post('/admin/import_products/getDataTableImportsData', [ImportProductsController::class, 'getDataTableImportsData']);
Route::post('/admin/import_products/getProductByBarcode', [ImportProductsController::class, 'getProductByBarcode']);
Route::post('/admin/import_products/addImportMaster', [ImportProductsController::class, 'addImportMaster']);
Route::post('/admin/import_products/addImportDetails', [ImportProductsController::class, 'addImportDetails']);
Route::post('/admin/import_products/addImportProductSerialNumbers', [ImportProductsController::class, 'addImportProductSerialNumbers']);

Route::get('/admin/serial_number/getDataByProductIdAndSerialNumber/{serial_number}/{id}', [ProductSerialNumberController::class, 'getDataByProductIdAndSerialNumber']);
Route::get('/admin/delete_sold_product_serial_number/{id}', [ProductSerialNumberController::class, 'deleteSoldProductSerialNumber']);
Route::post('/admin/serial_number/updateNote/{serial_number}/{id}', [ProductSerialNumberController::class, 'updateNoteByProductIdAndSerialNumber']);
Route::post('/admin/add_sold_product_serial_number/', [ProductSerialNumberController::class, 'addSoldProductSerialNumber']);

Route::get('/admin/sell_operation/', [SellOperationController::class, 'index']);
Route::get('/admin/getDefaultProductsForSellPreview/', [SellOperationController::class, 'getDefaultProductsForSellPreview']);
Route::post('/admin/searchProductsForSellPreviewByOption/', [SellOperationController::class, 'searchProductsForSellPreviewByOption']);

Route::post('/admin/invoicedetails/pay_more', [InvoicesController::class, 'payMore']);
Route::get('/admin/invoices', [InvoicesController::class, 'index']);
Route::get('/admin/invoice/{invoiceId}', [InvoicesController::class, 'printInvoice']);
Route::get('/admin/invoicedetails/{invoiceId}', [InvoiceDetailsController::class, 'getInvoiceDetails']);
Route::post('/admin/addInvoice', [InvoicesController::class, 'addInvoice']);
Route::post('/admin/addInvoiceDetails', [InvoiceDetailsController::class, 'addInvoiceDetails']);
Route::post('/admin/invoices/getDataTableInvoicesData', [InvoicesController::class, 'getDataTableInvoicesData']);

Route::get('/admin/delete_slideshow/{id}', [SlideShowController::class, 'deleteSlideShow']);

Auth::routes();
