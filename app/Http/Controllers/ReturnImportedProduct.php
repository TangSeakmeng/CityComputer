<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnImportedProduct extends Controller
{
    public static function submitReturnImportedProducts(Request $request) {
        try {
            $dateNow = date('Y-m-d H:i:s');

            $data = json_decode($request->data);

            foreach ($data as $item) {
                $old_import_product_transaction = DB::table('importdetails')
                                                    ->where('import_id', '=', $request->import_id)
                                                    ->where('product_id', '=', $item->product_id)
                                                    ->first();

                $new_import_product_qty = $old_import_product_transaction->import_quantity - $item->return_qty;

                $result = DB::table('products')->select('unit_in_stock')->where('id', '=', $item->product_id)->first();
                $result = intval($result->unit_in_stock) - intval($item->return_qty);

                DB::update("update importdetails set import_quantity = {$new_import_product_qty} where import_id = {$request->import_id} and product_id = {$item->product_id}");

                DB::insert('insert into return_imported_products(import_id, product_id, return_date, return_qty, created_at, updated_at) values (?,?,?,?,?,?)',
                    [$request->import_id, $item->product_id, $dateNow, $item->return_qty, $dateNow, $dateNow]);

                self::recalculateAVGCost($item->product_id, $result);
                self::recalculateImportSubtotal($request->import_id);
            }

            return response()->json(['message' => "Product is Updated Successfully!"], 201);
        } catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }

    public static function recalculateAVGCost($productId, $new_number_in_stock) {
        try {
            $result = DB::table('importdetails')
                ->select(DB::raw('SUM(import_quantity) AS sum_import_quantity'), DB::raw('SUM(import_quantity * import_price) AS sum_import_cost'))
                ->where('product_id', '=', $productId)
                ->first();

            $sum_import_quantity = $result->sum_import_quantity == null ? 0 : $result->sum_import_quantity;
            $sum_import_cost = $result->sum_import_cost == null ? 0 : $result->sum_import_cost;

            $final_average_cost = floatval($sum_import_cost) / floatval($sum_import_quantity);
            $final_average_cost = round($final_average_cost,3);

            DB::update("update products set cost_of_sale = {$final_average_cost}, unit_in_stock={$new_number_in_stock} where id = {$productId}");

            return response()->json(['message' => "Product is Updated Successfully!"], 201);
        } catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }

    public static function recalculateImportSubtotal($importId) {
        try {
            $result = DB::table('importdetails')
                ->select(DB::raw('SUM(import_quantity) AS sum_import_quantity'), DB::raw('SUM(import_quantity * import_price) AS sum_import_cost'))
                ->where('import_id', '=', $importId)
                ->first();

            $result = round($result->sum_import_cost / 1000) * 1000;

            DB::update("update imports set import_total={$result} where id = {$importId}");

            return response()->json(['message' => "Product is Updated Successfully!"], 201);
        } catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }
}
