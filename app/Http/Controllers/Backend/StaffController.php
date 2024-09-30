<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    // Hiển thị danh sách nhân viên với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Query cơ bản
        $query = Staff::query();

        // Nếu có từ khóa tìm kiếm
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('position', 'LIKE', "%{$search}%");
        }

        // Phân trang và giữ tham số tìm kiếm
        $staff = $query->paginate(10)->appends(['search' => $search]);

        return view('admin.Staff.index', compact('staff', 'search'));
    }

    // Hiển thị form tạo nhân viên mới
    public function create()
    {
        return view('admin.Staff.create');
    }

    // Lưu thông tin nhân viên vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'name' => 'required|max:255',
            'position' => 'required|max:255',
            'salary' => 'required|numeric',
        ]);

        // Tạo slug tự động từ tên
        $slug = Str::slug($request->name);

        // Tạo nhân viên
        Staff::create([
            'name' => $request->name,
            'position' => $request->position,
            'salary' => $request->salary,
            'slug' => $slug,
        ]);

        $flasher->addSuccess('Nhân viên đã được thêm thành công!');
        return redirect()->route('staff.index');
    }

    // Hiển thị form chỉnh sửa nhân viên
    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.Staff.edit', compact('staff'));
    }

    // Cập nhật thông tin nhân viên
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'position' => 'required|max:255',
            'salary' => 'required|numeric',
        ]);

        // Cập nhật thông tin nhân viên
        $staff->update([
            'name' => $request->name,
            'position' => $request->position,
            'salary' => $request->salary,
        ]);

        $flasher->addSuccess('Thông tin nhân viên đã được cập nhật!');
        return redirect()->route('staff.index');
    }

    // Xóa nhân viên
    public function destroy($id, FlasherInterface $flasher)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        $flasher->addSuccess('Nhân viên đã được xóa thành công!');
        return redirect()->route('staff.index');
    }
}
