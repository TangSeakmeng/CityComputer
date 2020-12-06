<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $data = DB::table('exchange_rate')->get();
        return view('backend.pages.exchange_rate.index', compact('data'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            DB::update('update exchange_rate set value = ? where id = ?', [$request->txtExchangeRateIn, 1]);
            DB::update('update exchange_rate set value = ? where id = ?', [$request->txtExchangeRateOut, 2]);

            return redirect('admin/exchange_rate/')->with('success', 'Exchange Rate is updated successfully!');
        } catch (\Exception $exception) {
            return redirect('admin/exchange_rate/')->with('error', $exception->getMessage());
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
