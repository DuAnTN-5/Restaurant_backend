<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostCategory;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // Hiển thị danh sách bài viết
    public function index(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            $posts = Post::where('title', 'LIKE', "%{$search}%")
                        ->orWhere('body', 'LIKE', "%{$search}%")
                        ->orWhereHas('category', function($query) use ($search) {
                            $query->where('name', 'LIKE', "%{$search}%");
                        })
                        ->paginate(10)
                        ->appends(['search' => $search]);
        } else {
            $posts = Post::with('category')->paginate(10);
        }

        return view('admin.Posts.index', compact('posts', 'search'));
    }

    // Hiển thị form tạo bài viết mới
    public function create()
    {
        $categories = PostCategory::all();
        return view('admin.Posts.create', compact('categories'));
    }

    // Lưu bài viết vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'category_id' => 'required|exists:post_categories,id',
            'image_url' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Tạo slug tự động từ tiêu đề
        $slug = Str::slug($request->title);

        $post = new Post([
            'title' => $request->title,
            'slug' => $slug,
            'body' => $request->body,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
        ]);

        // Xử lý hình ảnh
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $fileName);
            $post->image_url = 'images/' . $fileName;
        }

        $post->save();

        $flasher->addSuccess('Bài viết đã được thêm thành công!');
        return redirect()->route('posts.index');
    }

    // Hiển thị form chỉnh sửa bài viết
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = PostCategory::all();
        return view('admin.Posts.edit', compact('post', 'categories'));
    }

    // Cập nhật bài viết
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $post = Post::findOrFail($id);

        if (auth()->id() !== $post->user_id) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'category_id' => 'required|exists:post_categories,id',
            'image_url' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Tạo slug tự động từ tiêu đề
        $slug = Str::slug($request->title);

        $post->update([
            'title' => $request->title,
            'slug' => $slug,
            'body' => $request->body,
            'category_id' => $request->category_id,
        ]);

        // Xử lý hình ảnh
        if ($request->hasFile('image_url')) {
            if ($post->image_url && file_exists(public_path($post->image_url))) {
                unlink(public_path($post->image_url));
            }
            $file = $request->file('image_url');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $fileName);
            $post->image_url = 'images/' . $fileName;
        }

        $flasher->addSuccess('Bài viết đã được cập nhật!');
        return redirect()->route('posts.index');
    }

    // Xóa bài viết
    public function destroy($id, FlasherInterface $flasher)
    {
        $post = Post::findOrFail($id);

        if (auth()->id() !== $post->user_id) {
            abort(403, 'Bạn không có quyền xóa bài viết này.');
        }

        // Xóa ảnh nếu có
        if ($post->image_url && file_exists(public_path($post->image_url))) {
            unlink(public_path($post->image_url));
        }

        $post->delete();

        $flasher->addSuccess('Bài viết đã được xóa!');
        return redirect()->route('posts.index');
    }
}
