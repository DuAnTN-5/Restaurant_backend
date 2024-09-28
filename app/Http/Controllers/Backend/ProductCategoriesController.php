<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class ProductCategoriesController extends Controller
{
    // Hiển thị danh sách các danh mục sản phẩm với tìm kiếm và phân trang
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ request (nếu có)
        $search = $request->query('search');

        // Tìm kiếm theo tên hoặc slug, phân trang 10 mục mỗi trang
        $categories = ProductCategory::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('slug', 'LIKE', "%{$search}%");
        })->paginate(10)->appends(['search' => $search]); // Giữ tham số tìm kiếm khi phân trang

        return view('admin.ProductCategories.index', compact('categories', 'search'));
    }

    // Hiển thị form tạo danh mục sản phẩm mới
    public function create()
    {
        // Lấy tất cả các danh mục cha để hiển thị trong select box
        $categories = ProductCategory::whereNull('parent_id')->get();
        return view('admin.ProductCategories.create', compact('categories'));
    }

    // Lưu danh mục sản phẩm vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        // Validate input
        $request->validate([
            'name' => 'required|max:255|unique:product_categories,name',
            'description' => 'nullable|max:1000',
            'parent_id' => 'nullable|exists:product_categories,id', // Parent ID validation
        ]);

        // Tạo slug tự động từ tên nếu người dùng không cung cấp
        $slug = Str::slug($request->name);

        // Tạo danh mục
        ProductCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id, // Lưu parent_id nếu có
        ]);

        // Thông báo thêm thành công
        $flasher->addSuccess('Danh mục sản phẩm đã được thêm thành công!');

        return redirect()->route('product-categories.index');
    }

    // Hiển thị form chỉnh sửa danh mục sản phẩm
    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        // Lấy tất cả các danh mục cha trừ chính danh mục đang chỉnh sửa
        $categories = ProductCategory::whereNull('parent_id')->where('id', '!=', $id)->get();
        return view('admin.ProductCategories.edit', compact('category', 'categories'));
    }

    // Cập nhật danh mục sản phẩm
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $category = ProductCategory::findOrFail($id);

        // Validate input
        $request->validate([
            'name' => 'required|max:255|unique:product_categories,name,' . $category->id,
            'description' => 'nullable|max:1000',
            'parent_id' => 'nullable|exists:product_categories,id|not_in:' . $id, // Parent ID validation
        ]);

        // Tạo slug tự động từ tên nếu người dùng không cung cấp
        $slug = Str::slug($request->name);

        // Cập nhật danh mục
        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id, // Cập nhật parent_id nếu có
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
