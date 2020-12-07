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

            DB::insert('insert into productssoldwithserialnumber(invoice_id, product_id, serial_number, warranty_period, expired_date, created_at, updated_at) values(?,?,?,?,?,?,?)',
            [$request->invoice_id, $request->product_id, $request->serial_number, $request->warranty_period, $request->expired_date, $dateNow, $dateNow]);

            DB::update('update productserialnumbers set status = "Sold" where serial_number = ' . $request->serial_number . ' and product_id = ' . $request->product_id);

            return response()->json(['success' => 'Product Serial Number is added successfully.'], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public static function deleteSoldProductSerialNumber($id) {
        try {
            DB::DELETE ('delete from productssoldwithserialnumber where id = ' . $id);

            return response()->json(['success' => 'Product Serial Number is deleted successfully.'], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
