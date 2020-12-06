<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Image;

class Product extends Model
{
    use HasFactory;

    const IMAGE_PATH='uploaded_images/products/';

    public static function uploadImage($request) {

        $image = $request->file('thumbnail');

        if (isset($image)){
            $input['image_name'] = time(). '.' .$image->extension();
            $destinationPath = public_path(self::IMAGE_PATH);
            $img = Image::make($image->path());
            $img->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['image_name']);

            return $input['image_name'];
        }

        return null;
    }

    public static function uploadImage2($image) {
        if (isset($image)){
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $input['image_name'] = time(). '.' .$ext;
            $destinationPath = public_path(self::IMAGE_PATH);
            $img = Image::make($image['tmp_name']);
            $img->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['image_name']);

            return $input['image_name'];
        }

        return null;
    }

    public static function updateImage($request) {

        $image = $request->file('thumbnail');
        $old_image = $request->old_thumbnail;

        if (isset($image) && $old_image==null) {
            $input['image_name'] = time().'.'.$image->extension();
            $destinationPath = public_path(self::IMAGE_PATH);
            $img = Image::make($image->path());
            $img->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['image_name']);

            return $input['image_name'];
        } else if(isset($image) && $old_image!=null){
            $input['image_name'] = $old_image;
            $destinationPath = public_path(self::IMAGE_PATH);
            $img = Image::make($image->path());
            $img->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['image_name']);

            return $input['image_name'];
        }

        return $old_image;
    }

    public static function updateImage2($image, $oldImagePath) {
        $old_image = $oldImagePath;
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);

        if (isset($image) && $old_image==null) {
            $input['image_name'] = time().'.'.$ext;
            $destinationPath = public_path(self::IMAGE_PATH);
            $img = Image::make($image['tmp_name']);
            $img->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['image_name']);

            return $input['image_name'];
        } else if(isset($image) && $old_image!=null){
            $input['image_name'] = $old_image;
            $destinationPath = public_path(self::IMAGE_PATH);
            $img = Image::make($image['tmp_name']);
            $img->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['image_name']);

            return $input['image_name'];
        }

        return $old_image;
    }
}
