<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Lấy danh sách tất cả sản phẩm
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => true,
            'data' => ProductResource::collection($products),
        ]);
    }

    // Lấy chi tiết sản phẩm
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Sản phẩm không tìm thấy.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => new ProductResource($product),
        ]);
    }

    // Thêm sản phẩm mới
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sản phẩm đã được tạo thành công.',
            'data' => new ProductResource($product),
        ], 201);
    }

    // Cập nhật sản phẩm
    public function update(StoreProductRequest $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Sản phẩm không tìm thấy.',
            ], 404);
        }

        $product->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sản phẩm đã được cập nhật thành công.',
            'data' => new ProductResource($product),
        ]);
    }

    // Xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Sản phẩm không tìm thấy.',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sản phẩm đã được xóa thành công.',
        ], 204);
    }
}
