<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductForm;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index()
    {
        if (request()->ajax()) {
            $products = Product::with('categories')->orderby('id','desc')->paginate(10);

            return response()->json([
                'pagination' => [
                    'total'        => $products->total(),
                    'current_page' => $products->currentPage(),
                    'per_page'     => $products->perPage(),
                    'last_page'    => $products->lastPage(),
                    'from'         => $products->firstItem(),
                    'to'           => $products->lastItem(),
                ],
                'data' => $products
            ]);
        }
        return view('product');
    }


    public function store(ProductForm $request)
    {
        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->category_id = $request->category_id;

        if ($request->file('image')) {
            Storage::disk('local')->put("/images/{$request->file('image')->getClientOriginalName()}",'Contents');
        }

        $product->save();
    }


    public function show(Product $product)
    {
        return response()->json([
            'success' => 'ok',
            'data' => $product
        ]);
    }



    public function update(ProductForm $request, Product $product)
    {
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->category_id = $request->category_id;

        if ($request->file('image')) {
            Storage::disk('local')->put("/images/{$request->file('image')->getClientOriginalName()}",'Contents');
        }

        $product->save();

    }


    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'success' => 'ok',
        ]);
    }
}
