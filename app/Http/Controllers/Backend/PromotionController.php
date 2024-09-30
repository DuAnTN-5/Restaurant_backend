<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use Flasher\Prime\FlasherInterface;

class PromotionController extends Controller
{
    // Hiển thị danh sách khuyến mãi với tìm kiếm
    public function index(Request $request)
    {
        $search = $request->query('search');

        $promotions = Promotion::when($search, function ($query, $search) {
            $query->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('type', 'LIKE', "%{$search}%");
        })->paginate(10)->appends(['search' => $search]);

        return view('admin.promotions.index', compact('promotions', 'search'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'code' => 'required|unique:promotions|max:50',
            'type' => 'required|string',
            'value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|string',
        ]);

        Promotion::create($request->all());

        $flasher->addSuccess('Khuyến mãi đã được tạo thành công!');
        return redirect()->route('promotions.index');
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $promotion = Promotion::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:promotions,code,' . $promotion->id,
            'type' => 'required|string',
            'value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|string',
        ]);

        $promotion->update($request->all());

        $flasher->addSuccess('Khuyến mãi đã được cập nhật!');
        return redirect()->route('promotions.index');
    }

    public function destroy($id, FlasherInterface $flasher)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        $flasher->addSuccess('Khuyến mãi đã được xóa!');
        return redirect()->route('promotions.index');
    }
}
