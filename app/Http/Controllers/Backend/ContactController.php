<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    // Hiển thị danh sách liên hệ với tìm kiếm
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Query tìm kiếm liên hệ theo tên hoặc email
        $contacts = Contact::when($search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
        })->paginate(10)->appends(['search' => $search]);

        return view('admin.contacts.index', compact('contacts', 'search'));
    }

    public function create()
    {
        return view('admin.contacts.create');
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|max:20',
            'message' => 'required',
        ]);

        Contact::create($request->all());

        $flasher->addSuccess('Liên hệ đã được tạo thành công!');
        return redirect()->route('contacts.index');
    }

    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin.contacts.edit', compact('contact'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|max:20',
            'message' => 'required',
        ]);

        $contact = Contact::findOrFail($id);
        $contact->update($request->all());

        $flasher->addSuccess('Liên hệ đã được cập nhật!');
        return redirect()->route('contacts.index');
    }

    public function destroy($id, FlasherInterface $flasher)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        $flasher->addSuccess('Liên hệ đã được xóa!');
        return redirect()->route('contacts.index');
    }
}
