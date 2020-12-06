<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;

class InvoicesController extends Controller
{
    public static function addInvoice(Request $request) {
        try {
            $dateNow = date('Y-m-d H:i:s');
            $max_invoice_id = Invoice::max('id');
            $invoice_number = 1000000 + $max_invoice_id + 1;

            DB::insert('insert into invoices (invoice_number, invoice_date, customer_name, customer_contact, note,
                discount, subtotal, exchange_rate_in, exchange_rate_out, payment_method, money_received_in_dollar,
                money_received_in_riel, user_id, created_at, updated_at) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ',
                [$invoice_number, $dateNow, $request->customer_name, $request->customer_contact, $request->note, $request->discount,
                    $request->subtotal, $request->exchange_rate_in, $request->exchange_rate_out, $request->payment_method,
                    $request->money_received_in_dollar, $request->money_received_in_riel, Auth::id(), $dateNow, $dateNow]);

            $invoice_id = DB::getPdo()->lastInsertId();

            return response()->json(['invoiceId' => $invoice_id], 201);
        }catch (QueryException $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public static function printInvoice($invoiceId) {
        try {
            $data1 = DB::table('invoices')
                ->join('users', 'users.id', '=', 'invoices.user_id')
                ->select('invoices.invoice_number', 'invoices.invoice_date', 'invoices.customer_name','invoices.customer_contact',
                    'invoices.note', 'invoices.discount', 'invoices.subtotal', 'invoices.exchange_rate_in', 'invoices.exchange_rate_out',
                    'invoices.payment_method', 'invoices.money_received_in_dollar', 'invoices.money_received_in_riel', 'users.name',
                    'invoices.created_at', 'invoices.updated_at')
                ->where("invoices.id", '=', $invoiceId)
                ->first();

            $data2 = DB::table('invoicedetails')
                ->join('products', 'products.id', '=', 'invoicedetails.product_id')
                ->select('invoicedetails.invoice_id', 'invoicedetails.product_id', 'products.name','invoicedetails.qty',
                    'invoicedetails.price', 'invoicedetails.discount')
                ->where("invoicedetails.invoice_id", '=', $invoiceId)
                ->get();

            return view('backend.pages.sell_operation.invoice_2', compact('data1', 'data2'));
        } catch (QueryException $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public static function index() {
        return view('backend.pages.sell_operation.sellTransactions');
    }

    public static function getDataTableInvoicesData(Request $request) {
        if($request->ajax()) {
            $data = DB::table('viewinvoice')->orderBy('id', 'desc')->get();

            return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $button = "<a href='/admin/invoicedetails/{$data->id}'><button class='btn btn-success'>View</button></a>";
                return $button;
            })
            ->addColumn('status', function ($data) {
                $result1 = $data->money_received_in_dollar + ($data->money_received_in_riel / $data->exchange_rate_in);
                $result2 = $data->subtotal <= $result1;

                if($result2) {
                    $label = "<div style='text-align: center'><img src='/assets/icons/check.png' style='width: 30px' /></div>";
                }
                else {
                    $label = "<div style='text-align: center'><img src='/assets/icons/close.png' style='width: 30px' /></div>";
                }

                return $label;
            })->rawColumns(['status', 'action'])
            ->make(true);
        }

        return view('backend.pages.sell_operation.sellTransactions');
    }

    public static function payMore(Request $request) {
        try {
            $result = DB::table('invoices')
                ->select('money_received_in_dollar', 'money_received_in_riel')
                ->where('id', '=', $request->invoice_id)
                ->first();

            $original_money_received_in_dollar = $result->money_received_in_dollar;
            $original_money_received_in_riel = $result->money_received_in_riel;

            $original_money_received_in_dollar += $request->money_received_in_dollar;
            $original_money_received_in_riel += $request->money_received_in_riel;

            DB::update('update invoices set money_received_in_dollar = ?, money_received_in_riel = ? where id = ?',
                [$original_money_received_in_dollar, $original_money_received_in_riel, $request->invoice_id]);

            return response()->json(['message' => 'Payment is successful.'], 201);
        }catch (QueryException $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
