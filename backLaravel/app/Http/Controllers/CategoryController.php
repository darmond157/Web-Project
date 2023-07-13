<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function index()
    {
        return DB::select(
            'SELECT id,name FROM categories ORDER BY id asc'
        );
    }
    public function show($id)
    {
        return DB::select(
            'select * from categories where id=?',
            [$id]
        );
    }
    public function store(Request $request)
    {
        DB::insert(
            'INSERT INTO categories (name) VALUES (?)',
            [$request->name]
        );
    }
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        $category->name = $request->name;

        $category->save();
    }

    public function destroy($id)
    {
        Category::find($id)->delete();
    }
}
