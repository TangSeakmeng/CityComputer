<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        return "Welcome to our homepage";
    }

    public function index()
    {
        $result = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('sale_statuses', 'products.sale_status_id', '=', 'sale_statuses.id')
            ->where('products.published', '=', '1')
            ->where('categories.name', 'like', '%Laptop%')
            ->select('products.id as product_id', 'products.barcode as product_barcode', 'products.name as product_name', 'brands.name as brand_name',
                'categories.name as category_name', 'products.price as sale_price', 'products.discount_price', 'products.description',
                'sale_statuses.name as sale_status', 'products.image_path', 'brands.imagePath as brand_image_path', 'sale_statuses.image_path as sale_status_label')
            ->orderBy('products.brand_id', 'ASC')
            ->orderBy('brands.name', 'DESC')
            ->get();

        $result2 = DB::table('categories AS a')
            ->join('categories AS b', 'a.subcategory_id', '=', 'b.id')
            ->select('a.id as category_id', 'a.name as category_name', 'b.id as parent_id', 'b.name as parent_name')
            ->orderBy('b.name')
            ->get();

        $result3 = DB::table('slideshow')
            ->get();

        return view('frontend.index', compact('result', 'result2', 'result3'));
    }

    public function getCategories() {
        $result = DB::table('categories AS a')
            ->join('categories AS b', 'a.subcategory_id', '=', 'b.id')
            ->select('a.id as category_id', 'a.name as category_name', 'b.id as parent_id', 'b.name as parent_name')
            ->orderBy('b.name')
            ->get();

        return response()->json(['categories' => $result]);
    }

    public static function getProductsByCategory($categoryId) {
        $result = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('sale_statuses', 'products.sale_status_id', '=', 'sale_statuses.id')
            ->where('products.published', '=', '1')
            ->where('categories.id', '=', $categoryId)
            ->orWhere('categories.subcategory_id', '=', $categoryId)
            ->select('products.id as product_id', 'products.barcode as product_barcode', 'products.name as product_name', 'brands.name as brand_name',
                'categories.name as category_name', 'products.price as sale_price', 'products.discount_price', 'products.description',
                'sale_statuses.name as sale_status', 'products.image_path', 'brands.imagePath as brand_image_path', 'sale_statuses.image_path as sale_status_label')
            ->orderBy('products.brand_id', 'ASC')
            ->orderBy('brands.name', 'DESC')
            ->get();

        $result2 = DB::table('categories AS a')
            ->join('categories AS b', 'a.subcategory_id', '=', 'b.id')
            ->select('a.id as category_id', 'a.name as category_name', 'b.id as parent_id', 'b.name as parent_name')
            ->orderBy('b.name')
            ->get();

        $result3 = DB::table('slideshow')
            ->get();

        return view('frontend.pages.productsByCategory', compact('result', 'result2', 'result3'));
    }

    public static function getSearchedProducts($keyword) {
        $result = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('sale_statuses', 'products.sale_status_id', '=', 'sale_statuses.id')
            ->where('products.published', '=', '1')
            ->where('products.name', 'like', "%$keyword%")
            ->select('products.id as product_id', 'products.barcode as product_barcode', 'products.name as product_name', 'brands.name as brand_name',
                'categories.name as category_name', 'products.price as sale_price', 'products.discount_price', 'products.description',
                'sale_statuses.name as sale_status', 'products.image_path', 'brands.imagePath as brand_image_path', 'sale_statuses.image_path as sale_status_label')
            ->orderBy('products.brand_id', 'ASC')
            ->orderBy('brands.name', 'DESC')
            ->get();

        $result2 = DB::table('categories AS a')
            ->join('categories AS b', 'a.subcategory_id', '=', 'b.id')
            ->select('a.id as category_id', 'a.name as category_name', 'b.id as parent_id', 'b.name as parent_name')
            ->orderBy('b.name')
            ->get();

        $result3 = DB::table('slideshow')
            ->get();

        return view('frontend.pages.searchedProducts', compact('result', 'result2', 'result3'));
    }

    public static function contactUsPage() {
        $result2 = DB::table('categories AS a')
            ->join('categories AS b', 'a.subcategory_id', '=', 'b.id')
            ->select('a.id as category_id', 'a.name as category_name', 'b.id as parent_id', 'b.name as parent_name')
            ->orderBy('b.name')
            ->get();

        $result3 = DB::table('slideshow')
            ->get();

        return view('frontend.pages.contact_us', compact('result2', 'result3'));
    }
}
