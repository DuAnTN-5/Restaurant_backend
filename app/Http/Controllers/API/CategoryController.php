<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Trả về danh sách danh mục với Resource
        return CategoryResource::collection(Category::all());
    }

    public function products($id)
    {
        $category = Category::with('products')->findOrFail($id);
        return ProductResource::collection($category->products);
    }


    public function show($id)
    {
        // Trả về chi tiết danh mục với Resource
        return new CategoryResource(Category::findOrFail($id));
    }

    public function store(StoreCategoryRequest  $request)
    {
        $category = Category::create($request->all());
        return new CategoryResource($category);
    }

    public function update(StoreCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return new CategoryResource($category);
    }

    public function destroy($id)
    {
        Category::destroy($id);
        return response()->json(null, 204);
    }
}
