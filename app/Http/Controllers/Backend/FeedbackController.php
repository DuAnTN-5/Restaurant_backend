<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class FeedbackController extends Controller
{
    // Hiển thị danh sách phản hồi với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $search = $request->query('search'); // Lấy từ khóa tìm kiếm

        // Nếu có từ khóa tìm kiếm
        $query = Feedback::query();
        if ($search) {
            $query->where('message', 'LIKE', "%{$search}%");
        }

        // Phân trang 10 phản hồi
        $feedbacks = $query->paginate(10)->appends(['search' => $search]);

        return view('admin.Feedbacks.index', compact('feedbacks', 'search'));
    }

    // Hiển thị form tạo phản hồi
    public function create()
    {
        return view('admin.Feedbacks.create');
    }

    // Lưu phản hồi vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'message' => 'required|max:1000',
            'status' => 'required',
        ]);

        // Tạo slug tự động (nếu cần thiết, ở đây message không cần slug)
        // $slug = Str::slug($request->message);

        Feedback::create($request->all());

        $flasher->addSuccess('Phản hồi đã được thêm thành công!');
        return redirect()->route('feedbacks.index');
    }

    public function edit($id)
    {
        $feedback = Feedback::findOrFail($id);
        return view('admin.Feedbacks.edit', compact('feedback'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $feedback = Feedback::findOrFail($id);

        $request->validate([
            'message' => 'required|max:1000',
            'status' => 'required',
        ]);

        // Tạo slug tự động nếu cần
        // $slug = Str::slug($request->message);

        $feedback->update($request->all());

        $flasher->addSuccess('Phản hồi đã được cập nhật!');
        return redirect()->route('feedbacks.index');
    }

    public function destroy($id, FlasherInterface $flasher)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        $flasher->addSuccess('Phản hồi đã được xóa!');
        return redirect()->route('feedbacks.index');
    }
}
