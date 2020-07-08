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
            $products = Product::with('category')->orderby('id','desc')->paginate(10);

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
        $product->description = is_null($request->description)  || $request->description == 'null'? '' : $request->description;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $name = Storage::disk('public')->put('images',$file);
            $product->image = $name;
        }
        $product->save();

        return response()->json([
            'success' => 'ok'
        ]);
    }


    public function show(Product $product)
    {
        return response()->json([
            'success' => 'ok',
            'data' => $product
        ]);
    }



    public function update(Product $product, ProductForm $request)
    {

        $product->name = $request->name;
        $product->description = is_null($request->description)  || $request->description == 'null'? '' : $request->description;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $name = Storage::disk('public')->put('images',$file);
            $product->image = $name;
        }

        $product->save();
        return response()->json([
            'success' => 'ok'
        ]);
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'success' => 'ok',
        ]);
    }
}
