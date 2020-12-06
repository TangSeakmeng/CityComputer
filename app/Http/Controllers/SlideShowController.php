<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;

class SlideShowController extends Controller
{
    public function index()
    {
        $data = DB::table('slideshow')->get();
        return view('backend.pages.slideshow.index', compact('data'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $dateNow = date('Y-m-d H:i:s');

            $file = $request->file('slideShowImage')->store('uploaded_images/slideshow','custom');
            $file = str_replace('uploaded_images/slideshow/', ' ', $file);

            DB::insert('insert into slideshow (imagePath, created_at, updated_at) values (?,?,?) ', [$file,  $dateNow, $dateNow]);

            return redirect('admin/slideshow')->with('success','Image is uploaded successfully!');
        }catch (QueryException $exception) {
            return redirect('admin/slideshow')->with('error', $exception->getMessage());
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

    }

    public static function deleteSlideShow($id) {
        try {
            $data = DB::table('slideshow')->where('id', '=', $id)->first();
            self::removeImage($data->imagePath);
            DB::delete ('delete from slideshow where id = ' . $id);

            return redirect('admin/slideshow')->with('success','Image is deleted successfully!');
        }catch (QueryException $exception) {
            return redirect('admin/slideshow')->with('error', $exception->getMessage());
        }
    }

    private static function removeImage($imageFileName)
    {
        if(File::exists(public_path('uploaded_images/slideshow/' . (trim($imageFileName))))){
            File::delete(public_path('uploaded_images/slideshow/' . (trim($imageFileName))));
        }else{
            dd('File does not exists.');
        }
    }
}
