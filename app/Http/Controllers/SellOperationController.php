<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellOperationController extends Controller
{
    public static function index() {
        return view('backend.pages.sell_operation.index');
    }

    public static function  getDefaultProductsForSellPreview() {
        try {
            $data = DB::table('products')
                ->join('brands', 'products.brand_id', '=', 'brands.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.id as product_id', 'products.barcode as product_barcode', 'products.name as product_name', 'categories.name as category_name', 'brands.name as brand_name',
                'products.price', 'products.image_path', 'products.unit_in_stock')
                ->where('products.unit_in_stock', '>', '0')
                ->orderBy('products.id', 'desc')
                ->limit(20)
                ->get();

            $data2 = DB::table('exchange_rate')->get();

            return response()->json(['data' => $data, 'data2' => $data2], 201);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception], 401);
        }
    }

    public static function  searchProductsForSellPreviewByOption(Request $request) {
        try {
            $keyword = $request->keyword;
            $option = $request->option;

            if($keyword == '') {
                $data = DB::table('products')
                    ->join('brands', 'products.brand_id', '=', 'brands.id')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->select('products.id as product_id', 'products.barcode as product_barcode', 'products.name as product_name', 'categories.name as category_name', 'brands.name as brand_name',
                        'products.price', 'products.image_path', 'products.unit_in_stock')
                    ->where('products.unit_in_stock', '>', '0')
                    ->orderBy('products.id', 'desc')
                    ->limit(20)
                    ->get();
            } else {
                $data = DB::table('products')
                    ->join('brands', 'products.brand_id', '=', 'brands.id')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->select('products.id as product_id', 'products.barcode as product_barcode', 'products.name as product_name', 'categories.name as category_name', 'brands.name as brand_name',
                        'products.price', 'products.image_path', 'products.unit_in_stock')
                    ->orderBy('products.id', 'desc')
                    ->where($option, 'LIKE', "%{$keyword}%")
                    ->where('products.unit_in_stock', '>', '0')
                    ->limit(20)
                    ->get();
            }

            $data2 = DB::table('exchange_rate')->get();

            return response()->json(['data' => $data, 'data2' => $data2], 201);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception], 401);
        }
    }
}
