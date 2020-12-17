<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceDetailsController extends Controller
{
    public static function addInvoiceDetails(Request $request) {
        try {
            $dateNow = date('Y-m-d H:i:s');
            $arr_invoiceDetails = json_decode($request->data);

            foreach ($arr_invoiceDetails as $item) {
                $result = DB::table('products')->where('id', '=', $item->product_id)->first();
                $unit_in_stock_after_adjust = $result->unit_in_stock - $item->qty;

                DB::update('update products set unit_in_stock = ? where id = ? ', [$unit_in_stock_after_adjust, $item->product_id]);

                DB::insert('insert into invoicedetails (invoice_id, product_id, qty, price, discount, created_at, updated_at)
                    values (?,?,?,?,?,?,?) ', [$request->invoiceId, $item->product_id, $item->qty, $item->price, $item->discount, $dateNow, $dateNow]);
            }

            return response()->json(['message' => 'Invoice Details are added successfully.'], 201);
        }catch (QueryException $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public static function getInvoiceDetails($invoiceId) {
        try {
            $data = DB::table('invoices')
                ->join('users', 'invoices.user_id', '=', 'users.id')
                ->select('invoices.id', "invoices.invoice_number", 'invoices.invoice_date', 'invoices.customer_name', 'invoices.customer_contact',
                'invoices.note', 'invoices.discount', 'invoices.subtotal', 'invoices.exchange_rate_in', 'invoices.exchange_rate_out', 'invoices.payment_method',
                'invoices.money_received_in_dollar', 'invoices.money_received_in_riel', 'invoices.created_at', 'users.name as username')
                ->where('invoices.id', '=', $invoiceId)
                ->first();

            $data2 = DB::table('invoicedetails')
                ->join('products', 'products.id', '=', 'invoicedetails.product_id')
                ->select('invoicedetails.invoice_id', 'invoicedetails.product_id', 'products.barcode as product_barcode', 'products.name as product_name',
                    'invoicedetails.qty', 'invoicedetails.price', 'invoicedetails.discount')
                ->where('invoicedetails.invoice_id', '=', $invoiceId)
                ->get();

            $data3 = DB::table('productssoldwithserialnumber')
                ->join('products', 'products.id', '=', 'productssoldwithserialnumber.product_id')
                ->select('productssoldwithserialnumber.id', 'productssoldwithserialnumber.product_id', 'products.barcode as product_barcode', 'products.name as product_name',
                    'productssoldwithserialnumber.serial_number', 'productssoldwithserialnumber.warranty_period', 'productssoldwithserialnumber.expired_date')
                ->where('productssoldwithserialnumber.invoice_id', '=', $invoiceId)
                ->get();

            $data_4 = DB::table('productssoldwithserialnumber')
                ->select(DB::raw('count(*) as num_of_serial_number'), 'product_id')
                ->where('invoice_id', '=', $invoiceId)
                ->groupBy(['invoice_id', 'product_id'])
                ->get();

            $arr_alertMessageToUser = [];
            foreach ($data_4 as $item) {
                $num_of_serial_number = $item->num_of_serial_number;
                $productId = $item->product_id;

                $sold_quantity = 0;
                $productName = '';
                foreach ($data2 as $jtem) {
                    if($jtem->product_id == $productId) {
                        $sold_quantity = $jtem->qty;
                        $productName = $jtem->product_name;
                    }
                }

                if($num_of_serial_number > $sold_quantity) {
                    $str = "<div class=\"alert alert-danger\" role=\"alert\">
                               {$productName} has {$num_of_serial_number} serial number(s) while it was sold {$sold_quantity} unit(s).
                            </div>";
                } else if ($num_of_serial_number < $sold_quantity) {
                    $str = "<div class=\"alert alert-warning\" role=\"alert\">
                               {$productName} has {$num_of_serial_number} serial number(s) while it was sold {$sold_quantity} unit(s).
                            </div>";
                } else {
                    $str = "<div class=\"alert alert-success\" role=\"alert\">
                               {$productName} has {$num_of_serial_number} serial number(s) while it was sold {$sold_quantity} unit(s).
                            </div>";
                }

                array_push($arr_alertMessageToUser, $str);
            }

            $data_5 = DB::table('return_sold_products')
                ->select('return_sold_products.return_id', 'return_sold_products.product_id',
                    'products.name as product_name', 'return_sold_products.return_qty', 'return_sold_products.return_date')
                ->join('products', 'products.id', '=', 'return_sold_products.product_id')
                ->where('return_sold_products.invoice_id', '=', $invoiceId)
                ->get();

            return view('backend.pages.sell_operation.invoiceDetails', compact('data', 'data2', 'data3', 'arr_alertMessageToUser', 'data_5'));
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public static function getInvoiceDetailsByInvoiceId($id) {
        try {
            $data = DB::table('invoicedetails')
                ->join('products', 'products.id', '=', 'invoicedetails.product_id')
                ->select('invoicedetails.invoice_id', 'invoicedetails.product_id', 'products.barcode as product_barcode', 'products.name as product_name',
                    'invoicedetails.qty', 'invoicedetails.price', 'invoicedetails.discount')
                ->where('invoicedetails.invoice_id', '=', $id)
                ->get();

            return response()->json(['data' => $data], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
