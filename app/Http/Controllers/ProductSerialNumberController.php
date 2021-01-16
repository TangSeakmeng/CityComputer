<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductSerialNumberController extends Controller
{
    public static function getDataByProductIdAndSerialNumber($serial_number, $id) {
        try {
            $data = DB::table('productserialnumbers')
                ->where([
                    ['serial_number', '=', $serial_number],
                    ['product_id', '=', $id],
                ])
                ->first();

            return response()->json(['data' => $data], 201);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }

    public static function updateNoteByProductIdAndSerialNumber(Request $request, $serial_number, $id) {
        try {
            DB::update('update productserialnumbers set note = ? where product_id = ? and serial_number = ?', [$request->note, $id, $serial_number]);
            return response()->json(['success' => 'Note updated successfully.'], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public static function addSoldProductSerialNumber(Request $request) {
        try {
            $dateNow = date('Y-m-d H:i:s');

            $result1 = DB::table('invoicedetails')
                ->select('*')
                ->where('invoice_id', '=', $request->invoice_id)
                ->where('product_id', '=', $request->product_id)
                ->first();

            $result2 = DB::table('productssoldwithserialnumber')
                ->select(DB::raw('count(*) as count_product_serial_number'))
                ->where('invoice_id', '=', $request->invoice_id)
                ->where('product_id', '=', $request->product_id)
                ->first();

            if($result2->count_product_serial_number >= $result1->qty) {
                return response()->json(['error' => "You have inserted {$result2->count_product_serial_number} serial number(s) which is equal to number of sold quantity ({$result1->qty} units)."], 401);
            }

            DB::insert('insert into productssoldwithserialnumber(invoice_id, product_id, serial_number, warranty_period, expired_date, created_at, updated_at) values(?,?,?,?,?,?,?)',
            [$request->invoice_id, $request->product_id, $request->serial_number, $request->warranty_period, $request->expired_date, $dateNow, $dateNow]);

            DB::update("update productserialnumbers set status = 'Sold' where serial_number = '{$request->serial_number}' and product_id = '{$request->product_id}'");

            return response()->json(['success' => 'Product Serial Number is added successfully.'], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public static function deleteSoldProductSerialNumber($id) {
        try {
            $result = DB::table('productssoldwithserialnumber')
                ->where('id', '=', $id)
                ->first();

            DB::update("update productserialnumbers set status = 'Available' where serial_number = '{$result->serial_number}' and product_id = '{$result->product_id}'");
            DB::DELETE ('delete from productssoldwithserialnumber where id = ' . $id);

            return response()->json(['success' => 'Product Serial Number is deleted successfully.'], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public static function deleteAndReturnSoldProductSerialNumber(Request $request) {
        try {
            ReturnSoldProduct::submitReturnSoldProducts($request);

            $result = DB::table('productssoldwithserialnumber')
                ->where('id', '=', $request->return_id)
                ->first();

            DB::update("update productserialnumbers set status = 'Available' where serial_number = '{$result->serial_number}' and product_id = '{$result->product_id}'");
            DB::DELETE ('delete from productssoldwithserialnumber where id = ' . $request->return_id);

            return response()->json(['success' => 'Product Serial Number is deleted successfully.'], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
