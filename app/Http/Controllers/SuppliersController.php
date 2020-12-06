<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuppliersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $suppliers = Supplier::all()->where('id', '<>', 1);
        return view('backend.pages.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('backend.pages.suppliers.addSupplier');
    }

    public function store(Request $request)
    {
        try {
            $dateNow = date('Y-m-d H:i:s');

            DB::insert('insert into suppliers (name, address, contact, email, note, created_at, updated_at) values (?,?,?,?,?,?,?) ',
                [$request->name, $request->address,  $request->contact, $request->email, $request->note, $dateNow, $dateNow]);

            return redirect('admin/suppliers/create')->with('success','Supplier created successfully!');
        }catch (QueryException $exception) {
            return redirect('admin/suppliers/create')->with('error', $exception->getMessage());
        }
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);
        return view('backend.pages.suppliers.editSupplier', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        try {
            $dateNow = date('Y-m-d H:i:s');

            DB::update('update suppliers set name = ?, address = ?, contact = ?, email = ?, note = ?, updated_at = ? where id = '. $id,
                [$request->name, $request->address, $request->contact, $request->email, $request->note, $dateNow]);

            return redirect('admin/suppliers/' . $id . '/edit')->with('success','Supplier updated successfully!');
        }catch (QueryException $exception) {
            return redirect('admin/suppliers/' . $id . '/edit')->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        Supplier::find($id)->delete();
        return redirect('/admin/suppliers');
    }
}
