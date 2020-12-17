<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnSoldProduct extends Controller
{
    public static function submitReturnSoldProducts(Request $request) {
        try {
            $dateNow = date('Y-m-d H:i:s');

            $data = json_decode($request->data);

            foreach ($data as $item) {
                $old_sold_product_transaction = DB::table('invoicedetails')
                    ->where('invoice_id', '=', $request->invoice_id)
                    ->where('product_id', '=', $item->product_id)
                    ->first();

                $new_sold_product_qty = $old_sold_product_transaction->qty - $item->return_qty;

                $result = DB::table('products')->select('unit_in_stock')->where('id', '=', $item->product_id)->first();
                $result = intval($result->unit_in_stock) + intval($item->return_qty);

                DB::update("update invoicedetails set qty = {$new_sold_product_qty} where invoice_id = {$request->invoice_id} and product_id = {$item->product_id}");

                DB::insert('insert into return_sold_products(invoice_id, product_id, return_date, return_qty, created_at, updated_at) values (?,?,?,?,?,?)',
                    [$request->invoice_id, $item->product_id, $dateNow, $item->return_qty, $dateNow, $dateNow]);

                self::adjustStock($item->product_id, $result);
                self::recalculateInvoiceSubtotal($request->invoice_id);
            }

            return response()->json(['message' => "Product is Updated Successfully!"], 201);
        } catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }

    public static function adjustStock($productId, $new_number_in_stock) {
        try {
            DB::update("update products set unit_in_stock={$new_number_in_stock} where id = {$productId}");

            return response()->json(['message' => "Product is Updated Successfully!"], 201);
        } catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }

    public static function recalculateInvoiceSubtotal($invoiceId) {
        try {
            $result = DB::table('invoicedetails')
                ->select(DB::raw('SUM(qty * price) AS sum_import_cost'), DB::raw('SUM(qty * (price * (discount / 100))) AS sum_discount_amount'))
                ->where('invoice_id', '=', $invoiceId)
                ->first();

            $result_1 = floatval($result->sum_import_cost);
            $result_2 = floatval($result->sum_discount_amount);

            $result_3 = $result_1 - $result_2;
            $result_3 = round($result_3, 3);
            $result_2 = round($result_2, 3);

            DB::update("update invoices set discount={$result_2}, subtotal={$result_3} where id = {$invoiceId}");

            return response()->json(['message' => "Product is Updated Successfully!"], 201);
        } catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }
}
