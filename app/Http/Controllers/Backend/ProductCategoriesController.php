<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Flasher\Prime\FlasherInterface;

class ProductCategoriesController extends Controller
{
    // Hiển thị danh sách các danh mục sản phẩm
    public function index()
    {
        // Lấy tất cả các danh mục sản phẩm
        $categories = ProductCategory::all();
        return view('admin.ProductCategories.index', compact('categories'));
    }

    // Hiển thị form tạo danh mục sản phẩm mới
    public function create()
    {
        return view('admin.ProductCategories.create');
    }

    // Lưu danh mục sản phẩm vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        // Validate input
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:product_categories,slug',
            'description' => 'nullable|max:1000',
        ]);

        // Tạo danh mục
        ProductCategory::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        // Thông báo thêm thành công
        $flasher->addSuccess('Danh mục sản phẩm đã được thêm thành công!');

        return redirect()->route('product-categories.index');
    }

    // Hiển thị form chỉnh sửa danh mục sản phẩm
    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        return view('admin.ProductCategories.edit', compact('category'));
    }

    // Cập nhật danh mục sản phẩm
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $category = ProductCategory::findOrFail($id);

        // Validate input
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:product_categories,slug,' . $category->id,
            'description' => 'nullable|max:1000',
        ]);

        // Cập nhật danh mục
        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        // Thông báo cập nhật thành công
        $flasher->addSuccess('Danh mục sản phẩm đã được cập nhật!');

        return redirect()->route('product-categories.index');
    }

    // Xóa danh mục sản phẩm
    public function destroy($id, FlasherInterface $flasher)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();

        // Thông báo xóa thành công
        $flasher->addSuccess('Danh mục sản phẩm đã được xóa!');

        return redirect()->route('product-categories.index');
    }
}
