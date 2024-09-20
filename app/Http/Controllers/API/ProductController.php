<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Trả về danh sách sản phẩm với Resource
        return ProductResource::collection(Product::with('category')->get());
    }

    public function show($id)
    {
        // Trả về chi tiết sản phẩm với Resource
        return new ProductResource(Product::with('category')->findOrFail($id));
    }

    public function store(StoreProductRequest  $request)
    {
        $product = Product::create($request->all());
        return new ProductResource($product);
    }

    public function update(StoreProductRequest  $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return new ProductResource($product);
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(null, 204);
    }
}
