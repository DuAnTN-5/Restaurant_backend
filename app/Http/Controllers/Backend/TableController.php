<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use App\Models\User; // Thêm use User nếu bạn cần lấy danh sách người dùng

class TableController extends Controller
{
    // Hiển thị danh sách bàn với tìm kiếm và phân trang
    public function index(Request $request)
    {
        // Lấy các thông tin tìm kiếm và lọc từ request
        $search = $request->input('search');
        $status = $request->input('status'); // Lấy giá trị lọc theo tình trạng bàn

        // Query để lấy danh sách bàn
        $query = Table::query();
        $users = User::all();
        // Nếu có tìm kiếm theo số bàn
        if ($search) {
            $query->where('number', 'LIKE', "%{$search}%");
        }

        // Nếu có lọc theo tình trạng bàn
        if ($status) {
            $query->where('status', $status);
        }

        // Phân trang kết quả
        $tables = $query->paginate(10)->appends($request->except('page'));

        return view('admin.tables.index', compact('tables', 'search', 'status','users'));
    }

    // Hiển thị form tạo bàn mới
    public function create()
    {
        // Nếu bạn muốn có danh sách người dùng, sử dụng câu lệnh dưới
        $users = User::all();
        return view('admin.Tables.create', compact('users')); // Truyền danh sách người dùng (nếu cần)
    }

    // Lưu thông tin bàn vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        // Kiểm tra xem số lượng bàn hiện có đã đạt 20 hay chưa
        $tableCount = Table::count();
        
        if ($tableCount >= 20) {
            // Nếu đã đủ 20 bàn, hiển thị thông báo lỗi và quay về trang trước
            $flasher->addError('Số lượng bàn đã đạt tối đa 20. Không thể thêm bàn mới.');
            return redirect()->back();
        }

        // Validate thông tin bàn
        $request->validate([
            'number' => 'required|max:10|unique:tables,number',
            'seats' => 'required|integer|min:1',
            'location' => 'required|max:255',
            'status' => 'required|in:available,reserved,occupied', // Thêm validation cho tình trạng bàn
        ]);

        // Tạo bàn mới
        Table::create([
            'number' => $request->number,
            'seats' => $request->seats,
            'location' => $request->location,
            'status' => $request->status, // Lưu tình trạng bàn
        ]);

        // Thông báo thêm bàn thành công
        $flasher->addSuccess('Bàn đã được thêm thành công!');
        return redirect()->route('tables.index');
    }

    // Hiển thị form chỉnh sửa bàn
    public function edit($id)
    {
        $table = Table::findOrFail($id);
        return view('admin.Tables.edit', compact('table'));
    }

    // Cập nhật thông tin bàn
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $table = Table::findOrFail($id);

        $request->validate([
            'number' => 'required|max:10|unique:tables,number,' . $table->id,
            'seats' => 'required|integer|min:1',
            'location' => 'required|max:255',
            'status' => 'required|in:available,reserved,occupied', // Thêm validation cho tình trạng bàn
        ]);

        // Cập nhật thông tin bàn
        $table->update([
            'number' => $request->number,
            'seats' => $request->seats,
            'location' => $request->location,
            'status' => $request->status, // Cập nhật tình trạng bàn
        ]);

        $flasher->addSuccess('Thông tin bàn đã được cập nhật!');
        return redirect()->route('tables.index');
    }

    // Xóa bàn
    public function destroy($id, FlasherInterface $flasher)
    {
        $table = Table::findOrFail($id);
        $table->delete();

        $flasher->addSuccess('Bàn đã được xóa thành công!');
        return redirect()->route('tables.index');
    }
}
