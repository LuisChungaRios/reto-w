<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CategoryForm;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        if (request()->ajax()) {
            $categories = Category::orderby('id','desc')->paginate(5);

            return response()->json([
                'pagination' => [
                    'total'        => $categories->total(),
                    'current_page' => $categories->currentPage(),
                    'per_page'     => $categories->perPage(),
                    'last_page'    => $categories->lastPage(),
                    'from'         => $categories->firstItem(),
                    'to'           => $categories->lastItem(),
                ],
                'data' => $categories
            ]);
        }
        return view('category');
    }


    public function store(CategoryForm $request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'success' => 'ok'
        ]);
    }


    public function show(Category $category)
    {
        return response()->json([
            'success' => 'ok',
            'data' => $category
        ]);
    }


    public function edit(Category $category)
    {
        //
    }


    public function update(Request $request, Category $category)
    {
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'success' => 'ok'
        ]);
    }


    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'success' => 'ok'
        ]);
    }
}
