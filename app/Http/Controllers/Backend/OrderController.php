<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng với tìm kiếm và lọc
    public function index(Request $request)
    {
        $search = $request->query('search');
        $filterStatus = $request->query('status'); // Lọc theo tình trạng đơn hàng

        $orders = Order::with('user')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
            })
            ->when($filterStatus, function ($query, $filterStatus) {
                $query->where('status', $filterStatus);
            })
            ->paginate(10)->appends(['search' => $search, 'status' => $filterStatus]);

        return view('admin.orders.index', compact('orders', 'search', 'filterStatus'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.orders.create', compact('users'));
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric',
            'order_date' => 'required|date',
        ]);

        Order::create($request->all());

        $flasher->addSuccess('Đơn hàng đã được tạo thành công!');
        return redirect()->route('orders.index');
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $users = User::all();

        return view('admin.orders.edit', compact('order', 'users'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric',
            'order_date' => 'required|date',
        ]);

        $order->update($request->all());

        $flasher->addSuccess('Đơn hàng đã được cập nhật!');
        return redirect()->route('orders.index');
    }

    public function destroy($id, FlasherInterface $flasher)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        $flasher->addSuccess('Đơn hàng đã được xóa!');
        return redirect()->route('orders.index');
    }
}
