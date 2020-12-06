<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\exactly;
use File;

class BrandsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $brands = Brand::all();
        return view('backend.pages.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('backend.pages.brands.addBrand');
    }

    public function store(Request $request)
    {
        try {
            $dateNow = date('Y-m-d H:i:s');

            $file = $request->file('fileLogo')->store('uploaded_images/brands','custom');
            $file = str_replace('uploaded_images/brands/', ' ', $file);

            DB::insert('insert into brands (name, imagepath, created_at, updated_at) values (?,?,?,?) ', [$request->txtBrandName, $file,  $dateNow, $dateNow]);


            return redirect('admin/brands/create')->with('success','Brand created successfully!');
        }catch (QueryException $exception) {
            return redirect('admin/brands/create')->with('error',"Brand name can't be duplicated");
        }
    }

    public function show($id)
    {
        return $id;
    }

    public function edit($id)
    {
        $brand = Brand::find($id);
        return view('backend.pages.brands.editBrand', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        try {
            $dateNow = date('Y-m-d H:i:s');
            $file = $request->oldImagePath;

            if($request->fileLogo != null)
            {
                $file = $request->file('fileLogo')->store('uploaded_images/brands','custom');
                $file = str_replace('uploaded_images/brands/', ' ', $file);

                self::removeImage($request->oldImagePath);
            }

            DB::update('update brands set name = ?, imagePath = ?, updated_at = ? where id = ?', [$request->txtBrandName, $file, $dateNow, $id]);

            return redirect('admin/brands/' . $id . '/edit')->with('success','Brand edited successfully!');
        }catch (QueryException $exception) {
            return redirect('admin/brands/' . $id . '/edit')->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        Brand::find($id)->delete();
        return redirect('/admin/brands');
    }

    private function removeImage($imageFileName)
    {
        if(File::exists(public_path('uploaded_images/brands/' . (trim($imageFileName))))){
            File::delete(public_path('uploaded_images/brands/' . (trim($imageFileName))));
        }else{
            dd('File does not exists.');
        }
    }
}
