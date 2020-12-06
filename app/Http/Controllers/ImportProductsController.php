<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;

class ImportProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('backend.pages.import_products.index');
    }

    public function getDataTableImportsData(Request $request)
    {
        if($request->ajax()) {
//            $data = DB::table('imports')
//                ->join('suppliers', 'suppliers.id', '=', 'imports.supplier_id')
//                ->join('users', 'users.id', '=', 'imports.user_id')
//                ->select('imports.id', 'imports.invoice_number', 'imports.import_date', 'imports.import_total',
//                    'suppliers.name as supplier_name', 'users.name as username', 'imports.created_at')
//                ->orderBy('imports.id', 'desc');

            $data = DB::table('viewimportproducts')->get();

            return DataTables::of($data)->addColumn('action', function ($data) {
               $button = "<a href='/admin/import_products/getImportDetailsDataByImportId/{$data->id}'><button class='btn btn-success'>View</button></a>";
               return $button;
            })->rawColumns(['action'])->make(true);
        }

        return view('backend.pages.import_products.index');
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $categories = Category::all();
        $brands = Brand::all();
        return view('backend.pages.import_products.addTransaction', compact('suppliers', 'categories', 'brands'));
    }

    public function store(Request $request)
    {
        //
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

    public static function getProductByBarcode(Request $request) {
        try {
            $data = DB::table('products')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->select('products.id', 'products.barcode', 'products.name', 'brands.name as brand_name',
                    'categories.name as category_name', 'products.cost_of_sale', 'products.unit_in_stock', 'products.price',
                    'products.discount_price', 'products.image_path')
                ->where('products.barcode', '=', $request->barcode)
                ->first();

            return response()->json(['data' => $data], 201);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public static function addImportMaster(Request $request) {
        try {
            $dateNow = date('Y-m-d H:i:s');
            $importId = 0;

            DB::insert('insert into imports (import_date, invoice_number, import_total, supplier_id, user_id, created_at, updated_at)
                        values (?,?,?,?,?,?,?) ', [$request->import_date, $request->invoice_number, $request->import_total, $request->supplier_id,
                        Auth::id(),  $dateNow, $dateNow]);

            $importId = DB::getPdo()->lastInsertId();

            return response()->json(['importId' => $importId], 201);
        }catch (QueryException $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public static function addImportDetails(Request $request) {
        try {
            $list_importedProducts = json_decode($request->list_importedProducts);
            $importId = $request->import_id;
            $dateNow = date('Y-m-d H:i:s');

            foreach ($list_importedProducts as $item) {
                $productId = $item->productId;

                $result = DB::table('importdetails')
                    ->select(DB::raw('SUM(import_quantity) AS sum_import_quantity'), DB::raw('SUM(import_quantity * import_price) AS sum_import_cost'))
                    ->where('product_id', '=', $productId)
                    ->first();

                $sum_import_quantity = $result->sum_import_quantity == null ? 0 : $result->sum_import_quantity;
                $sum_import_cost = $result->sum_import_cost == null ? 0 : $result->sum_import_cost;

                $avg_cost_upper = $sum_import_cost + ($item->quantity * $item->cost_of_sale);
                $avg_cost_lower = $sum_import_quantity + $item->quantity;
                $final_average_cost = $avg_cost_upper / $avg_cost_lower;
                $final_average_cost = round($final_average_cost,3);

                $result_2 = DB::table('products')->where('id', '=', $productId)->first();

                $totalUnitInStockAfterImport = $result_2->unit_in_stock + $item->quantity;

                DB::update('update products set unit_in_stock = ?, cost_of_sale = ? where id = ?', [$totalUnitInStockAfterImport, $final_average_cost, $productId]);
                DB::insert('insert into importdetails (import_id, product_id, import_price, import_quantity, created_at, updated_at)
                        values (?,?,?,?,?,?) ', [$importId, $item->productId, $item->cost_of_sale, $item->quantity, $dateNow, $dateNow]);
            }

            return response()->json(['message' => 'Import Details are added successfully.'], 201);
        }catch (QueryException $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public static function addImportProductSerialNumbers(Request $request) {
        try {
            $list_productSerialNumbers = json_decode($request->list_productSerialNumbers);
            $importId = $request->import_id;
            $dateNow = date('Y-m-d H:i:s');

            foreach ($list_productSerialNumbers as $item) {
                DB::insert('insert into productserialnumbers (import_id, product_id, serial_number, note, status, created_at, updated_at)
                        values (?,?,?,?,?,?,?) ', [$importId, $item->productId, $item->serialNumber, 'none', 'Available', $dateNow, $dateNow]);
            }

            return response()->json(['message' => 'Product SerialNumbers are added successfully.'], 201);
        } catch (QueryException $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    public function getImportDetailsDataByImportId($id)
    {
        try {
            $data = DB::table('imports')
                ->join('users', 'users.id', '=', 'imports.user_id')
                ->join('suppliers', 'suppliers.id', '=', 'imports.supplier_id')
                ->where('imports.id', '=', $id)
                ->select('imports.id', 'imports.import_date', 'imports.invoice_number', 'imports.import_total',
                    'suppliers.name as supplier_name', 'users.name as username', 'imports.created_at')
                ->first();

            $data_2 = DB::table('importdetails')
                ->join('products', 'products.id', '=', 'importdetails.product_id')
                ->where('import_id', '=', $id)
                ->select('products.id as product_id', 'products.name as product_name', 'products.barcode as product_barcode',
                'importdetails.import_quantity', 'importdetails.import_price')
                ->get();

            $data_3 = DB::table('productserialnumbers')
                ->join('products', 'products.id', '=', 'productserialnumbers.product_id')
                ->where('import_id', '=', $id)
                ->select('products.id as product_id', 'products.name as product_name',
                    'products.barcode as product_barcode', 'productserialnumbers.serial_number', 'productserialnumbers.status')
                ->get();

//            return redirect('admin/products/create')->with('success','Product created successfully!');
            return view('backend.pages.import_products.viewImportDetails', compact('data', 'data_2', 'data_3'));
        } catch (Exception $exception) {
            return view('backend.pages.import_products.viewImportDetails', compact('exception'));
        }
    }
}
