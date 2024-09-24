<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostCategory;
use Flasher\Prime\FlasherInterface;

class PostCategoriesController extends Controller
{
    // Hiển thị danh sách các danh mục bài viết
    public function index()
    {
        $categories = PostCategory::all();
        return view('admin.PostCategories.index', compact('categories'));
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
            'slug' => 'required|unique:post_categories,slug',
            'description' => 'nullable|max:1000',
        ]);

        PostCategory::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        $flasher->addSuccess('Danh mục bài viết đã được thêm thành công!');
        return redirect()->route('post-categories.index');
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
            'slug' => 'required|unique:post_categories,slug,' . $category->id,
            'description' => 'nullable|max:1000',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        $flasher->addSuccess('Danh mục bài viết đã được cập nhật!');
        return redirect()->route('post-categories.index');
    }

    // Xóa danh mục bài viết
    public function destroy($id, FlasherInterface $flasher)
    {
        $category = PostCategory::findOrFail($id);
        $category->delete();

        $flasher->addSuccess('Danh mục bài viết đã được xóa!');
        return redirect()->route('post-categories.index');
    }
}
