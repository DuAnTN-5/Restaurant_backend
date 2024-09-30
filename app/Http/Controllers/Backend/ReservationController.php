<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Banking\PayPalController;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Flasher\Prime\FlasherInterface;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $startDate = $request->query('start_date');

        $query = Reservation::query();

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($startDate) {
            $query->where('reservation_date', '<=', $startDate);
        }

        $reservations = $query->paginate(10)->appends([
            'search' => $search,
            'status' => $status,
            'start_date' => $startDate,
        ]);

        return view('admin.reservations.index', compact('reservations', 'search', 'status', 'startDate'));
    }

    public function create()
    {
        $users = User::all();
        $tables = Table::where('status', 'available')->get();
        return view('admin.reservations.create', compact('users', 'tables'));
    }

    public function store(Request $request, FlasherInterface $flasher, PayPalController $paypal)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'table_id' => 'required|exists:tables,id',
            'guest_count' => 'required|integer|min:1',
            'reservation_date' => 'required|date|after:today',
        ]);

        // Tạo mới đơn đặt bàn với trạng thái "pending"
        $reservation = Reservation::create(array_merge($request->all(), ['status' => 'pending']));

        // Xử lý thanh toán qua PayPal
        try {
            return $paypal->createPayment($reservation->id, 10000); // Thanh toán 10,000 VND
        } catch (\Exception $e) {
            // Nếu có lỗi trong quá trình thanh toán
            $reservation->delete(); // Xóa đơn đặt bàn nếu thanh toán thất bại
            $flasher->addError('Có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại.');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $users = User::all();
        $tables = Table::all();

        return view('admin.reservations.edit', compact('reservation', 'users', 'tables'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $reservation = Reservation::findOrFail($id);

        // Validate dữ liệu đầu vào
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'table_id' => 'required|exists:tables,id',
            'guest_count' => 'required|integer|min:1',
            'reservation_date' => 'required|date|after:today',
        ]);

        // Cập nhật đơn đặt bàn
        $reservation->update($request->all());

        $flasher->addSuccess('Đặt chỗ đã được cập nhật!');
        return redirect()->route('reservations.index');
    }

    public function destroy($id, FlasherInterface $flasher)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        $flasher->addSuccess('Đặt chỗ đã được xóa!');
        return redirect()->route('reservations.index');
    }
}
