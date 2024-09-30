<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Notification::query();

        if ($search) {
            $query->where('message', 'LIKE', "%{$search}%");
        }

        $notifications = $query->paginate(10)->appends(['search' => $search]);

        return view('admin.Notifications.index', compact('notifications', 'search'));
    }

    public function create()
    {
        return view('admin.Notifications.create');
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'message' => 'required',
            'status' => 'required',
        ]);

        Notification::create($request->all());

        $flasher->addSuccess('Thông báo đã được thêm thành công!');
        return redirect()->route('notifications.index');
    }

    public function edit($id)
    {
        $notification = Notification::findOrFail($id);
        return view('admin.Notifications.edit', compact('notification'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $notification = Notification::findOrFail($id);

        $request->validate([
            'message' => 'required',
            'status' => 'required',
        ]);

        $notification->update($request->all());

        $flasher->addSuccess('Thông báo đã được cập nhật!');
        return redirect()->route('notifications.index');
    }

    public function destroy($id, FlasherInterface $flasher)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        $flasher->addSuccess('Thông báo đã được xóa!');
        return redirect()->route('notifications.index');
    }
}
