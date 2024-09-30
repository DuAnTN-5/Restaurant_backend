<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use Flasher\Prime\FlasherInterface;

class PaymentController extends Controller
{
    // Hiển thị danh sách thanh toán với tìm kiếm
    public function index(Request $request)
    {
        $search = $request->query('search');

        $payments = Payment::with('order')
            ->when($search, function ($query, $search) {
                $query->where('transaction_id', 'LIKE', "%{$search}%");
            })
            ->paginate(10)->appends(['search' => $search]);

        return view('admin.payments.index', compact('payments', 'search'));
    }

    public function create()
    {
        $orders = Order::all();
        return view('admin.payments.create', compact('orders'));
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'method' => 'required|string',
            'transaction_id' => 'nullable|string',
        ]);

        Payment::create($request->all());

        $flasher->addSuccess('Thanh toán đã được tạo thành công!');
        return redirect()->route('payments.index');
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $orders = Order::all();

        return view('admin.payments.edit', compact('payment', 'orders'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'method' => 'required|string',
            'transaction_id' => 'nullable|string',
        ]);

        $payment->update($request->all());

        $flasher->addSuccess('Thanh toán đã được cập nhật!');
        return redirect()->route('payments.index');
    }

    public function destroy($id, FlasherInterface $flasher)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        $flasher->addSuccess('Thanh toán đã được xóa!');
        return redirect()->route('payments.index');
    }
}
