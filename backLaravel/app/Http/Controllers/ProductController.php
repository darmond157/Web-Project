<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function index()
    {
        return DB::select(
            'SELECT id,name,category,company,price,picture FROM products ORDER BY id asc'
        );
    }
    public function show($id)
    {
        return DB::select(
            'select * from products where id=?',
            [$id]
        );
    }
    public function store(Request $request)
    {
        DB::insert(
            'INSERT INTO products (name,category,company,price,picture) VALUES (?,?,?,?,?)',
            [$request->name, $request->category, $request->company, $request->price, $request->picture]
        );
    }
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        $product->name = $request->name;
        $product->category = $request->category;
        $product->company = $request->company;
        $product->price = $request->price;
        $product->picture = $request->picture;

        $product->save();
    }

    public function destroy($id)
    {
        Product::find($id)->delete();
    }
}
