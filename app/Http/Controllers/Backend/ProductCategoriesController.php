<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductCategoriesController extends Controller
{
    // Hiển thị danh sách các danh mục sản phẩm với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Query cơ bản
        $query = ProductCategory::query();

        // Nếu có tìm kiếm theo từ khóa
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%");
        }

        // Lấy kết quả phân trang 10 danh mục
        $categories = $query->paginate(10)->appends(['search' => $search]);

        return view('admin.ProductCategories.index', compact('categories', 'search'));
    }

    // Thêm phương thức toggleStatus
    public function toggleStatus($id)
    {
        $category = ProductCategory::findOrFail($id);
        $newStatus = ($category->status == 'active') ? 'inactive' : 'active';

        // Cập nhật trạng thái
        $category->update(['status' => $newStatus]);

        return response()->json(['status' => $newStatus]);
    }

    // Lưu danh mục sản phẩm vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        DB::transaction(function () use ($request, $flasher) {
            // Validate input
            $request->validate([
                'name' => 'required|max:255|unique:product_categories,name',
                'description' => 'nullable|max:1000',
            ]);

            // Tạo slug tự động từ tên và kiểm tra nếu đã tồn tại slug trùng lặp
            $slug = Str::slug($request->name);
            $slugExists = ProductCategory::where('slug', $slug)->exists();
            if ($slugExists) {
                $slug = $slug . '-' . time(); // Thêm thời gian vào cuối slug để đảm bảo không trùng lặp
            }

            // Tạo danh mục
            ProductCategory::create([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
            ]);

            // Thông báo thêm thành công
            $flasher->addSuccess('Danh mục sản phẩm đã được thêm thành công!');
        });

        return redirect()->route('ProductCategories.index');
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
        DB::transaction(function () use ($request, $id, $flasher) {
            $category = ProductCategory::findOrFail($id);

            // Validate input
            $request->validate([
                'name' => 'required|max:255|unique:product_categories,name,' . $category->id,
                'description' => 'nullable|max:1000',
            ]);

            // Tạo slug tự động từ tên và kiểm tra nếu đã tồn tại slug trùng lặp
            $slug = Str::slug($request->name);
            $slugExists = ProductCategory::where('slug', $slug)->where('id', '!=', $category->id)->exists();
            if ($slugExists) {
                $slug = $slug . '-' . time(); // Thêm thời gian vào cuối slug để đảm bảo không trùng lặp
            }

            // Cập nhật danh mục
            $category->update([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
            ]);

            // Thông báo cập nhật thành công
            $flasher->addSuccess('Danh mục sản phẩm đã được cập nhật!');
        });

        return redirect()->route('ProductCategories.index');
    }

    // Xóa danh mục sản phẩm
    public function destroy($id, FlasherInterface $flasher)
    {
        DB::transaction(function () use ($id, $flasher) {
            $category = ProductCategory::findOrFail($id);

            $category->delete();

            // Thông báo xóa thành công
            $flasher->addSuccess('Danh mục sản phẩm đã được xóa!');
        });

        return redirect()->route('ProductCategories.index');
    }
}
