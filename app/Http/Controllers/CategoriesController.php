<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categories = Category::all()->where('id', '<>', 1);
        return view('backend.pages.categories.index', compact('categories'));
    }

    public function create()
    {
        $parent_categories = DB::table('categories')->where('subcategory_id', '=', 1)->get();
        return view('backend.pages.categories.addCategory', compact('parent_categories'));
    }

    public function store(Request $request)
    {
        try {
            $dateNow = date('Y-m-d H:i:s');

            DB::insert('insert into categories (name, subcategory_id, created_at, updated_at) values (?,?,?,?) ', [$request->name, $request->subcategory_id,  $dateNow, $dateNow]);

            return redirect('admin/categories/create')->with('success','Category created successfully!');
        }catch (QueryException $exception) {
            return redirect('admin/categories/create')->with('error',"Category name can't be duplicated");
        }
    }

    public function show($id)
    {
        $category = Category::find($id);
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $parent_categories = DB::table('categories')->where('subcategory_id', '=', 1)->get();
        return view('backend.pages.categories.editCategory', compact('category', 'parent_categories'));
    }

    public function update(Request $request, $id)
    {
        try {
            $dateNow = date('Y-m-d H:i:s');

            DB::update('update categories set name = ?, subcategory_id = ?, updated_at = ? where id = '. $id, [$request->txtCategoryName, $request->selectSubCategoryOf, $dateNow]);

            return redirect('admin/categories/' . $id . '/edit')->with('success','Category updated successfully!');
        }catch (QueryException $exception) {
            return redirect('admin/categories/' . $id . '/edit')->with('error',"Category name can't be duplicated");
        }
    }

    public function destroy($id)
    {
        Category::find($id)->delete();
        return redirect('/admin/categories');
    }
}
