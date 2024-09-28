<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostCategory;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class PostCategoriesController extends Controller
{
    // Hiển thị danh sách các danh mục bài viết
    public function index(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            // Tìm kiếm danh mục theo tên hoặc slug
            $categories = PostCategory::where('name', 'LIKE', "%{$search}%")
                                      ->orWhere('slug', 'LIKE', "%{$search}%")
                                      ->paginate(10);
        } else {
            // Hiển thị toàn bộ danh mục nếu không tìm kiếm
            $categories = PostCategory::paginate(3);
        }

        return view('admin.postcategories.index', compact('categories'));
    }

    // Hiển thị form tạo danh mục mới
    public function create()
    {
        return view('admin.PostCategories.create');
    }

    // Lưu danh mục bài viết vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
        ]);

        // Tạo slug tự động từ tên nếu người dùng không cung cấp
        $slug = $request->input('slug') ? $request->input('slug') : Str::slug($request->name);

        PostCategory::create([
            'name' => $request->name,
            'slug' => $slug, // Sử dụng slug tự động
            'description' => $request->description,
        ]);

        $flasher->addSuccess('Danh mục bài viết đã được thêm thành công!');
        return redirect()->route('PostCategories.index');
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit($id)
    {
        $category = PostCategory::findOrFail($id);
        return view('admin.PostCategories.edit', compact('category'));
    }

    // Cập nhật danh mục bài viết
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $category = PostCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
        ]);

        // Tạo slug tự động từ tên nếu người dùng không cung cấp
        $slug = $request->input('slug') ? $request->input('slug') : Str::slug($request->name);

        $category->update([
            'name' => $request->name,
            'slug' => $slug, // Sử dụng slug tự động
            'description' => $request->description,
        ]);

        $flasher->addSuccess('Danh mục bài viết đã được cập nhật!');
        return redirect()->route('PostCategories.index');
    }

    // Xóa danh mục bài viết
    public function destroy($id, FlasherInterface $flasher)
    {
        $category = PostCategory::findOrFail($id);
        $category->delete();

        $flasher->addSuccess('Danh mục bài viết đã được xóa!');
        return redirect()->route('PostCategories.index');
    }

    // Toggle trạng thái danh mục bài viết
    public function toggleStatus($id)
    {
        $category = PostCategory::find($id);

        if ($category) {
            \Log::info('Trạng thái hiện tại: ' . $category->status);

            // Toggle status
            $category->status = $category->status === 'active' ? 'inactive' : 'active';
            $category->save();

            \Log::info('Trạng thái sau khi thay đổi: ' . $category->status);

            return response()->json(['status' => $category->status]);
        }

        return response()->json(['error' => 'Category not found'], 404);
    }
}
