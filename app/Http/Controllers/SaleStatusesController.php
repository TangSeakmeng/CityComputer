<?php

namespace App\Http\Controllers;

use App\Models\SaleStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SaleStatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $saleStatuses = SaleStatus::all();
        return view('backend.pages.saleStatuses.index', compact('saleStatuses'));
    }

    public function create()
    {
        return view('backend.pages.saleStatuses.addSaleStatus');
    }

    public function store(Request $request)
    {
        try {
            $file = '';

            if($request->fileLabel) {
                $file = $request->file('fileLabel')->store('uploaded_images/sale_statuses','custom');
                $file = str_replace('uploaded_images/sale_statuses/', ' ', $file);
            }

            $dateNow = date('Y-m-d H:i:s');

            DB::insert('insert into sale_statuses (name, image_path, created_at, updated_at) values (?,?,?,?) ', [$request->txtSaleName, $file,  $dateNow, $dateNow]);

            return redirect('admin/saleStatuses/create')->with('success','Sale Status created successfully!');
        }catch (QueryException $exception) {
            return redirect('admin/saleStatuses/create')->with('error', $exception->getMessage());
        }
    }

    public function show($id)
    {
        return $id;
    }

    public function edit($id)
    {
        $saleStatus = SaleStatus::find($id);
        return view('backend.pages.saleStatuses.editSaleStatus', compact('saleStatus'));
    }

    public function update(Request $request, $id)
    {
        try {
            $dateNow = date('Y-m-d H:i:s');
            $file = $request->oldImagePath;

            if($request->checkboxNoImage) {
                $file = '';

                if($request->oldImagePath != '') {
                    self::removeImage($request->oldImagePath);
                }

            } else {
                if($request->fileLabel != null)
                {
                    $file = $request->file('fileLabel')->store('uploaded_images/sale_statuses','custom');
                    $file = str_replace('uploaded_images/sale_statuses/', ' ', $file);

                    self::removeImage($request->oldImagePath);
                }
            }

            DB::update('update sale_statuses set name = ?, image_path = ?, updated_at = ? where id = ?', [$request->txtSaleName, $file, $dateNow, $id]);

            return redirect('admin/saleStatuses/' . $id . '/edit')->with('success','Sale Status edited successfully!');
        } catch (QueryException $exception) {
            return redirect('admin/saleStatuses/' . $id . '/edit')->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        Brand::find($id)->delete();
        return redirect('/admin/saleStatuses');
    }

    //dd($request->all());

    private function removeImage($imageFileName)
    {
        if(File::exists(public_path('uploaded_images/sale_statuses/' . (trim($imageFileName))))){
            File::delete(public_path('uploaded_images/sale_statuses/' . (trim($imageFileName))));
        }else{
            dd('File does not exists.');
        }
    }
}
