<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Flasher\Prime\FlasherInterface;

class PostController extends Controller
{
    // Hiển thị danh sách bài viết
    public function index()
    {
        $posts = Post::all();
        return view('admin.Posts.index', compact('posts'));
    }

    // Hiển thị form tạo bài viết mới
    public function create()
    {
        return view('admin.Posts.create');
    }

    // Lưu bài viết vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts,slug',
            'body' => 'required',
        ]);

        Post::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'body' => $request->body,
        ]);

        $flasher->addSuccess('Bài viết đã được thêm thành công!');
        return redirect()->route('posts.index');
    }

    // Hiển thị form chỉnh sửa bài viết
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.Posts.edit', compact('post'));
    }

    // Cập nhật bài viết
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts,slug,' . $post->id,
            'body' => 'required',
        ]);

        $post->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'body' => $request->body,
        ]);

        $flasher->addSuccess('Bài viết đã được cập nhật!');
        return redirect()->route('posts.index');
    }

    // Xóa bài viết
    public function destroy($id, FlasherInterface $flasher)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        $flasher->addSuccess('Bài viết đã được xóa!');
        return redirect()->route('posts.index');
    }
}
