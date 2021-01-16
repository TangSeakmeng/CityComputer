<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ReportController extends Controller
{
    public static function printStockReport() {
        $data_brand = DB::table('brands')->get();
        $data_category = DB::table('categories')->get();

        return view('backend.pages.products.printStockReport', compact('data_brand', 'data_category'));
    }

    public static function getDataForStockReport(Request $request) {
        try {
            $arr_brands = json_decode($request->selectedBrands);
            $arr_categories = json_decode($request->selectedCategories);

            if($arr_brands == [] && $arr_categories == [] && $request->fromCreatedDate == null) {
                $result = DB::table('products')
                    ->join('brands', 'brands.id', '=', 'products.brand_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->orderBy('brand_id')
                    ->orderBy('category_id')
                    ->select('products.id', 'products.name as product_name', 'products.barcode as product_barcode'
                        , 'categories.name as category_name', 'brands.name as brand_name', 'products.cost_of_sale'
                        , 'products.price', 'products.discount_price', 'products.unit_in_stock')
                    ->get();
            } else if($arr_brands != [] && $arr_categories == [] && $request->fromCreatedDate == null) {
                $result = DB::table('products')
                    ->join('brands', 'brands.id', '=', 'products.brand_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->whereIn('brand_id', $arr_brands)
                    ->orderBy('brand_id')
                    ->orderBy('category_id')
                    ->select('products.id', 'products.name as product_name', 'products.barcode as product_barcode'
                        , 'categories.name as category_name', 'brands.name as brand_name', 'products.cost_of_sale'
                        , 'products.price', 'products.discount_price', 'products.unit_in_stock')
                    ->get();
            } else if($arr_brands == [] && $arr_categories != [] && $request->fromCreatedDate == null) {
                $result = DB::table('products')
                    ->join('brands', 'brands.id', '=', 'products.brand_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->whereIn('category_id', $arr_categories)
                    ->orderBy('brand_id')
                    ->orderBy('category_id')
                    ->select('products.id', 'products.name as product_name', 'products.barcode as product_barcode'
                        , 'categories.name as category_name', 'brands.name as brand_name', 'products.cost_of_sale'
                        , 'products.price', 'products.discount_price', 'products.unit_in_stock')
                    ->get();
            } else if($arr_brands == [] && $arr_categories == [] && $request->fromCreatedDate != null) {
                $result = DB::table('products')
                    ->join('brands', 'brands.id', '=', 'products.brand_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->whereBetween('products.created_at', [ $request->fromCreatedDate . ' 23:59:59', $request->toCreatedDate . ' 23:59:59'])
                    ->orderBy('brand_id')
                    ->orderBy('category_id')
                    ->select('products.id', 'products.name as product_name', 'products.barcode as product_barcode'
                        , 'categories.name as category_name', 'brands.name as brand_name', 'products.cost_of_sale'
                        , 'products.price', 'products.discount_price', 'products.unit_in_stock')
                    ->get();
            } else if($arr_brands != [] && $arr_categories != [] && $request->fromCreatedDate == null) {
                $result = DB::table('products')
                    ->join('brands', 'brands.id', '=', 'products.brand_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->whereIn('brand_id', $arr_brands)
                    ->whereIn('category_id', $arr_categories)
                    ->orderBy('brand_id')
                    ->orderBy('category_id')
                    ->select('products.id', 'products.name as product_name', 'products.barcode as product_barcode'
                        , 'categories.name as category_name', 'brands.name as brand_name', 'products.cost_of_sale'
                        , 'products.price', 'products.discount_price', 'products.unit_in_stock')
                    ->get();
            } else if($arr_brands != [] && $arr_categories == [] && $request->fromCreatedDate != null) {
                $result = DB::table('products')
                    ->join('brands', 'brands.id', '=', 'products.brand_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->whereIn('brand_id', $arr_brands)
                    ->whereBetween('products.created_at', [ $request->fromCreatedDate . ' 23:59:59', $request->toCreatedDate . ' 23:59:59'])
                    ->orderBy('brand_id')
                    ->orderBy('category_id')
                    ->select('products.id', 'products.name as product_name', 'products.barcode as product_barcode'
                        , 'categories.name as category_name', 'brands.name as brand_name', 'products.cost_of_sale'
                        , 'products.price', 'products.discount_price', 'products.unit_in_stock')
                    ->get();
            } else if($arr_brands == [] && $arr_categories != [] && $request->fromCreatedDate != null) {
                $result = DB::table('products')
                    ->join('brands', 'brands.id', '=', 'products.brand_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->whereIn('category_id', $arr_categories)
                    ->whereBetween('products.created_at', [ $request->fromCreatedDate . ' 23:59:59', $request->toCreatedDate . ' 23:59:59'])
                    ->orderBy('brand_id')
                    ->orderBy('category_id')
                    ->select('products.id', 'products.name as product_name', 'products.barcode as product_barcode'
                        , 'categories.name as category_name', 'brands.name as brand_name', 'products.cost_of_sale'
                        , 'products.price', 'products.discount_price', 'products.unit_in_stock')
                    ->get();
            } else if($arr_brands != [] && $arr_categories != [] && $request->fromCreatedDate != null) {
                $result = DB::table('products')
                    ->join('brands', 'brands.id', '=', 'products.brand_id')
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->whereIn('brand_id', $arr_brands)
                    ->whereIn('category_id', $arr_categories)
                    ->whereBetween('products.created_at', [ $request->fromCreatedDate . ' 23:59:59', $request->toCreatedDate . ' 23:59:59'])
                    ->orderBy('brand_id')
                    ->orderBy('category_id')
                    ->select('products.id', 'products.name as product_name', 'products.barcode as product_barcode'
                        , 'categories.name as category_name', 'brands.name as brand_name', 'products.cost_of_sale'
                        , 'products.price', 'products.discount_price', 'products.unit_in_stock')
                    ->get();
            }

            return response()->json(['data' => $result], 201);
        } catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }
}
