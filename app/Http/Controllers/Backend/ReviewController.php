<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Flasher\Prime\FlasherInterface;

class ReviewController extends Controller
{
    // Hiển thị danh sách đánh giá với tìm kiếm và lọc
    public function index(Request $request)
    {
        $search = $request->query('search');
        $filterStatus = $request->query('status'); // Lọc theo tình trạng đánh giá

        $reviews = Review::with('user', 'product')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
            })
            ->when($filterStatus, function ($query, $filterStatus) {
                $query->where('status', $filterStatus);
            })
            ->paginate(10)->appends(['search' => $search, 'status' => $filterStatus]);

        return view('admin.reviews.index', compact('reviews', 'search', 'filterStatus'));
    }

    public function create()
    {
        $users = User::all();
        $products = Product::all();
        return view('admin.reviews.create', compact('users', 'products'));
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required',
        ]);

        Review::create($request->all());

        $flasher->addSuccess('Đánh giá đã được tạo thành công!');
        return redirect()->route('reviews.index');
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        $users = User::all();
        $products = Product::all();

        return view('admin.reviews.edit', compact('review', 'users', 'products'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $review = Review::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required',
        ]);

        $review->update($request->all());

        $flasher->addSuccess('Đánh giá đã được cập nhật!');
        return redirect()->route('reviews.index');
    }

    public function destroy($id, FlasherInterface $flasher)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        $flasher->addSuccess('Đánh giá đã được xóa!');
        return redirect()->route('reviews.index');
    }
}
