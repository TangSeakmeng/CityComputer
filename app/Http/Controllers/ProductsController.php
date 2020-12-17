<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SaleStatus;
use http\Message;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Psy\Util\Json;
use File;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $saleStatuses = SaleStatus::all();

        $data = DB::table('products')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->join('sale_statuses', 'sale_statuses.id', '=', 'products.sale_status_id')
            ->select('products.id', 'products.barcode', 'products.name', 'brands.name as brand_name',
                'categories.name as category_name', 'products.cost_of_sale', 'products.unit_in_stock', 'products.price',
                'products.discount_price', 'products.image_path', 'sale_statuses.name as sale_name', 'products.published')
            ->orderBy('products.id', 'desc')
            ->paginate(25);

        return view('backend.pages.products.index', compact('data', 'saleStatuses'));
    }

    public function create()
    {
        if(Auth::user()->is_admin != 1)
            return redirect('/admin');

        $categories = Category::all();
        $brands = Brand::all();
        $saleStatuses = SaleStatus::all();

        return view('backend.pages.products.addProduct', compact('categories', 'brands', 'saleStatuses'));
    }

    public function store(Request $request)
    {
        if(Auth::user()->is_admin != 1)
            return redirect('/admin');

        $file = "";

        try {
//            $file = $request->file('fileImage')->store('uploaded_images/products','custom');
//            $file = str_replace('uploaded_images/products/', ' ', $file);

//            $str_arr = explode ("^", $request->txtDescription);
//            $descriptionJSON = json_encode($str_arr);

//            return redirect('admin/products/create')->with('success','Product created successfully!');
//            return redirect('admin/products/create')->with('error', $exception->getMessage());

            $file = Product::uploadImage2($_FILES['thumbnail']);
            $dateNow = date('Y-m-d H:i:s');

            DB::insert('insert into products (barcode, name, category_id, brand_id, description, cost_of_sale, unit_in_stock, price,
                discount_price, sale_status_id, published, image_path, created_at, updated_at) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?) ',
                [$request->barcode, $request->name, $request->category_id, $request->brand_id, $request->description,
                    $request->cost_of_sale, $request->unit_in_stock, $request->sale_price, $request->discount_price, 1,
                    1, $file,  $dateNow, $dateNow]);

            return response()->json(['message' => "Product is Created Successfully!"], 201);
        }catch (QueryException $exception) {
            self::removeImage($file);

            if(strpos($exception->getMessage(), 'Duplicate entry') == true) {
                return response()->json(['message' => 'Barcode is already existed.'], 401);
            } else {
                return response()->json(['message' => $exception->getMessage()], 401);
            }
        }
    }

    public function show($id)
    {
        if(Auth::user()->is_admin != 1)
            return redirect('/admin');

        $data = DB::table('products')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->join('sale_statuses', 'sale_statuses.id', '=', 'products.sale_status_id')
            ->where('products.id', $id)
            ->select('products.id', 'products.barcode', 'products.name', 'brands.name as brand_name',
                'categories.name as category_name', 'products.cost_of_sale', 'products.unit_in_stock', 'products.price',
                'products.discount_price', 'products.image_path', 'sale_statuses.name as sale_name', 'products.created_at',
                'products.updated_at', 'products.description')
            ->first();

        return view('backend.pages.products.viewProduct', compact('data'));
    }

    public function edit($id)
    {
        if(Auth::user()->is_admin != 1)
            return redirect('/admin');

        $categories = Category::all();
        $brands = Brand::all();
        $saleStatuses = SaleStatus::all();

        $data = DB::table('products')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->join('sale_statuses', 'sale_statuses.id', '=', 'products.sale_status_id')
            ->select('products.id as productId', 'products.barcode', 'products.name', 'brands.name as brand_name',
                'categories.name as category_name', 'products.cost_of_sale', 'products.unit_in_stock', 'products.price',
                'products.discount_price', 'products.image_path', 'sale_statuses.name as sale_name', 'products.published', 'products.description')
            ->where('products.id', '=', $id)
            ->first();

        return view('backend.pages.products.editProduct', compact('data', 'categories', 'brands', 'saleStatuses'));
    }

    public function update(Request $request, $id)
    {
        dd($request);
    }

    public function destroy($id)
    {
        if(Auth::user()->is_admin != 1)
            return;

        try {
            $product = DB::table('products')->where('id', $id)->first();
            $this->removeImage($product->image_path);

            Product::where('id', $id)->delete();

            return response()->json(['message' => "Product is Deleted Successfully!"], 201);
        }catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }

    public static function getProductByProductId($id)
    {
        $data = DB::table('products')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->join('sale_statuses', 'sale_statuses.id', '=', 'products.sale_status_id')
            ->where('products.id', '=', $id)
            ->select('products.id', 'products.barcode', 'products.name', 'brands.name as brand_name',
                'categories.name as category_name', 'products.cost_of_sale', 'products.unit_in_stock', 'products.price',
                'products.discount_price', 'products.image_path', 'sale_statuses.name as sale_name', 'products.description')
            ->first();

        return  response()->json(['data'=>$data]); //Json::encode($data);
    }

    public static function updatePublishedStatus($id, $published)
    {
        DB::update('update published = ? where id = ?', [$published, $id]);
        return  response()->json(['message' => 'Published Status Is Updated Successfully!']);
    }

    public static function updateSaleStatus($id, $saleStatus)
    {
        DB::update('update sale_status_id = ? where id = ?', [$saleStatus, $id]);
        return  response()->json(['message' => 'Sale Status Is Updated Successfully!']);
    }

    public static function updateProduct(Request $request, $id) {
        try {
            $dateNow = date('Y-m-d H:i:s');
            $file = $request->old_thumbnail;

            if($_FILES != [])
                $file = Product::updateImage2($_FILES['thumbnail'], $file);

            DB::update('update products set barcode = ?, name = ?, category_id = ?, brand_id = ?, description = ?, cost_of_sale = ?,
                unit_in_stock = ?, price = ?, discount_price = ?, image_path = ?, updated_at = ? where id = ?',
                [$request->barcode, $request->name, $request->category_id, $request->brand_id, $request->description, $request->cost_of_sale,
                    $request->unit_in_stock, $request->sale_price, $request->discount_price, $file, $dateNow, $id]);

            return response()->json(['message' => "Product is Updated Successfully!"], 201);
        }catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }

    public static function updateProductPublished(Request $request, $id) {
        try {
            DB::update('update products set published = ? where id = ?', [$request->published, $id]);

            return response()->json(['message' => "Product is Updated Successfully!"], 201);
        }catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }

    public static function updateProductSaleStatus(Request $request, $id) {
        try {
            DB::update('update products set sale_status_id = ? where id = ?', [$request->sale_status_id, $id]);

            return response()->json(['message' => "Product is Updated Successfully!"], 201);
        }catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }

    public static function GetAllProducts() {
        $saleStatuses = SaleStatus::all();
        try {
            $data = DB::table('products')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->join('sale_statuses', 'sale_statuses.id', '=', 'products.sale_status_id')
                ->select('products.id', 'products.barcode', 'products.name', 'brands.name as brand_name',
                    'categories.name as category_name', 'products.cost_of_sale', 'products.unit_in_stock', 'products.price',
                    'products.discount_price', 'products.image_path', 'sale_statuses.name as sale_name', 'products.published')
                ->orderBy('products.id', 'desc')
                ->paginate(2);

            return response()->json(['data' => $data, 'saleStatuses' => $saleStatuses], 201);
        }catch (QueryException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }
    }

    public function searchProducts(Request $request)
    {
        $saleStatuses = SaleStatus::all();
        $condition = '';

        $selectedOption = $request->selectSearchBy;
        $inputValue = $request->txtKeyword;

        if($request->selectSearchBy == 'category')
            $condition = 'categories.name';
        else if($request->selectSearchBy == 'brand')
            $condition = 'brands.name';
        else
            $condition = 'products.' . $request->selectSearchBy;

        $data = DB::table('products')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->join('sale_statuses', 'sale_statuses.id', '=', 'products.sale_status_id')
            ->select('products.id', 'products.barcode', 'products.name', 'brands.name as brand_name',
                'categories.name as category_name', 'products.cost_of_sale', 'products.unit_in_stock', 'products.price',
                'products.discount_price', 'products.image_path', 'sale_statuses.name as sale_name', 'products.published')
            ->orderBy('products.id', 'desc')
            ->where($condition, 'LIKE', "%{$request->txtKeyword}%")
            ->paginate(10);



        return view('backend.pages.products.index', compact('data', 'saleStatuses', 'selectedOption', 'inputValue'));
    }

    private function removeImage($imageFileName)
    {
        if(File::exists(public_path('uploaded_images/products/' . (trim($imageFileName))))){
            File::delete(public_path('uploaded_images/products/' . (trim($imageFileName))));
        }else{
            dd('File does not exists.');
        }
    }

    public static function getProductIdFromSerialNumber($serialNumber) {
        try {
            $productId = DB::table('productserialnumbers')->select('product_id')->where('serial_number', '=', $serialNumber)->first();

            if($productId == null) {
                return response()->json(['message' => 'Product is not found!'], 201);
            }

            return response()->json(['result' => $productId], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public static function getProductWithSerialNumberByProductId($id, $serialNumber)
    {
        try {
            $data = DB::table('products')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->join('sale_statuses', 'sale_statuses.id', '=', 'products.sale_status_id')
                ->where('products.id', '=', $id)
                ->select('products.id', 'products.barcode', 'products.name', 'brands.name as brand_name',
                    'categories.name as category_name', 'products.cost_of_sale', 'products.unit_in_stock', 'products.price',
                    'products.discount_price', 'products.image_path', 'sale_statuses.name as sale_name', 'products.description',
                    'products.created_at', 'products.updated_at')
                ->first();

            $data2 = DB::table('invoices')
                ->join('users', 'users.id', '=', 'invoices.user_id')
                ->join('invoicedetails', 'invoicedetails.invoice_id', '=', 'invoices.id')
                ->join('productssoldwithserialnumber', 'productssoldwithserialnumber.invoice_id', '=', 'invoices.id')
                ->select('invoices.id', "invoices.invoice_number", 'invoices.invoice_date', 'invoices.customer_name', 'invoices.customer_contact',
                    'invoices.note', 'invoices.discount', 'invoices.subtotal', 'invoices.exchange_rate_in', 'invoices.exchange_rate_out', 'invoices.payment_method',
                    'invoices.money_received_in_dollar', 'invoices.money_received_in_riel', 'invoices.created_at', 'users.name as username',
                    'invoicedetails.price as sale_price')
                ->where('productssoldwithserialnumber.serial_number', '=', $serialNumber)
                ->first();

            $data3 = DB::table('imports')
                ->join('importdetails', 'importdetails.import_id', '=', 'imports.id')
                ->join('productserialnumbers', 'productserialnumbers.import_id', '=', 'imports.id')
                ->join('suppliers', 'suppliers.id', '=', 'imports.supplier_id')
                ->join('users', 'users.id', '=', 'imports.user_id')
                ->select('imports.id', 'imports.import_date', 'imports.invoice_number', 'suppliers.name as supplier_name', 'users.name as username')
                ->where('productserialnumbers.serial_number', '=', $serialNumber)
                ->first();

            return view('backend.pages.checkingInformation.productInformation', compact('data', 'data2', 'data3'));
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
