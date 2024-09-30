<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;

class OrderItemController extends Controller
{
    // Hiển thị danh sách các mặt hàng trong đơn hàng với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Query cơ bản
        $query = OrderItem::query();

        // Nếu có từ khóa tìm kiếm
        if ($search) {
            $query->whereHas('product', function($productQuery) use ($search) {
                $productQuery->where('name', 'LIKE', "%{$search}%");
            });
        }

        // Phân trang và giữ tham số tìm kiếm
        $orderItems = $query->paginate(10)->appends(['search' => $search]);

        return view('admin.OrderItems.index', compact('orderItems', 'search'));
    }

    // Hiển thị form tạo mặt hàng mới cho đơn hàng
    public function create()
    {
        $orders = Order::all(); // Lấy danh sách đơn hàng để chọn
        $products = Product::all(); // Lấy danh sách sản phẩm

        return view('admin.OrderItems.create', compact('orders', 'products'));
    }

    // Lưu mặt hàng vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // Tạo mới một mặt hàng đơn hàng
        OrderItem::create($request->all());

        $flasher->addSuccess('Mặt hàng đã được thêm vào đơn hàng thành công!');
        return redirect()->route('order-items.index');
    }

    // Hiển thị form chỉnh sửa mặt hàng
    public function edit($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orders = Order::all();
        $products = Product::all();

        return view('admin.OrderItems.edit', compact('orderItem', 'orders', 'products'));
    }

    // Cập nhật mặt hàng trong cơ sở dữ liệu
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $orderItem = OrderItem::findOrFail($id);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // Cập nhật mặt hàng
        $orderItem->update($request->all());

        $flasher->addSuccess('Mặt hàng đã được cập nhật thành công!');
        return redirect()->route('order-items.index');
    }

    // Xóa mặt hàng khỏi đơn hàng
    public function destroy($id, FlasherInterface $flasher)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->delete();

        $flasher->addSuccess('Mặt hàng đã được xóa khỏi đơn hàng!');
        return redirect()->route('order-items.index');
    }
}
