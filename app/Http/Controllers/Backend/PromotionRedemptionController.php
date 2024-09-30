<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PromotionRedemption;
use App\Models\Promotion;
use App\Models\Order;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;

class PromotionRedemptionController extends Controller
{
    // Hiển thị danh sách các lần đổi khuyến mãi với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Query cơ bản
        $query = PromotionRedemption::query();

        // Nếu có từ khóa tìm kiếm
        if ($search) {
            $query->whereHas('promotion', function($promotionQuery) use ($search) {
                $promotionQuery->where('code', 'LIKE', "%{$search}%");
            })->orWhereHas('order', function($orderQuery) use ($search) {
                $orderQuery->where('id', 'LIKE', "%{$search}%");
            });
        }

        // Phân trang và giữ tham số tìm kiếm
        $redemptions = $query->paginate(10)->appends(['search' => $search]);

        return view('admin.PromotionRedemptions.index', compact('redemptions', 'search'));
    }

    // Hiển thị form tạo đổi khuyến mãi mới
    public function create()
    {
        $promotions = Promotion::all(); // Lấy danh sách khuyến mãi để chọn
        $orders = Order::all(); // Lấy danh sách đơn hàng

        return view('admin.PromotionRedemptions.create', compact('promotions', 'orders'));
    }

    // Lưu đổi khuyến mãi vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'promotion_id' => 'required|exists:promotions,id',
            'order_id' => 'required|exists:orders,id',
        ]);

        // Tạo mới một đổi khuyến mãi
        PromotionRedemption::create([
            'promotion_id' => $request->promotion_id,
            'order_id' => $request->order_id,
            'redemption_date' => now(),
        ]);

        $flasher->addSuccess('Đổi khuyến mãi đã được lưu thành công!');
        return redirect()->route('promotion-redemptions.index');
    }

    // Hiển thị form chỉnh sửa đổi khuyến mãi
    public function edit($id)
    {
        $redemption = PromotionRedemption::findOrFail($id);
        $promotions = Promotion::all();
        $orders = Order::all();

        return view('admin.PromotionRedemptions.edit', compact('redemption', 'promotions', 'orders'));
    }

    // Cập nhật đổi khuyến mãi trong cơ sở dữ liệu
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $redemption = PromotionRedemption::findOrFail($id);

        $request->validate([
            'promotion_id' => 'required|exists:promotions,id',
            'order_id' => 'required|exists:orders,id',
        ]);

        // Cập nhật đổi khuyến mãi
        $redemption->update([
            'promotion_id' => $request->promotion_id,
            'order_id' => $request->order_id,
        ]);

        $flasher->addSuccess('Đổi khuyến mãi đã được cập nhật thành công!');
        return redirect()->route('promotion-redemptions.index');
    }

    // Xóa đổi khuyến mãi khỏi cơ sở dữ liệu
    public function destroy($id, FlasherInterface $flasher)
    {
        $redemption = PromotionRedemption::findOrFail($id);
        $redemption->delete();

        $flasher->addSuccess('Đổi khuyến mãi đã được xóa thành công!');
        return redirect()->route('promotion-redemptions.index');
    }
}
