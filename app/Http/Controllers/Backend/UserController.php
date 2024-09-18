<?php

namespace App\Http\Controllers\Backend;

use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\User;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Hiển thị form tạo người dùng mới
    public function create()
    {
        $provinces = Province::all(); // Lấy danh sách tỉnh/thành phố
        $config = [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',  // JavaScript xử lý location
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
        return view('admin.users.create', compact('provinces', 'config'));
    }

    // Lưu người dùng mới
    public function store(Request $request, FlasherInterface $flasher)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'password' => 'required|confirmed',
            'date_of_birth' => 'nullable|date',
            'sex' => 'nullable|in:Nam,Nữ',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $flasher->addError($error);
            }
            return redirect()->back()->withInput();
        }

        // Xử lý tải lên ảnh (nếu có)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $fileName);
            $imagePath = 'images/' . $fileName;
        }

        // Tạo người dùng mới
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $imagePath,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'sex' => $request->sex,
            'phone' => $request->phone,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id' => $request->ward_id,
            'address' => $request->address,
        ]);

        $flasher->addSuccess('Người dùng đã được thêm thành công');
        return redirect()->route('users.index');
    }

    // Hiển thị form chỉnh sửa người dùng
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $provinces = Province::all();

        // dd($provinces);

        $config = [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',  // JavaScript xử lý location
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
        return view('admin.users.edit', compact('user', 'provinces', 'config'));
    }

    // Cập nhật thông tin người dùng
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date',
            'sex' => 'required|in:Nam,Nữ',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $flasher->addError($error);
            }
            return redirect()->back()->withInput();
        }

        // Cập nhật người dùng
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->date_of_birth = $request->date_of_birth;
        $user->sex = $request->sex;
        $user->province_id = $request->province_id;
        $user->district_id = $request->district_id;
        $user->ward_id = $request->ward_id;
        $user->address = $request->address;

        // Xử lý tải lên ảnh (nếu có)
        if ($request->hasFile('image')) {
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $fileName);
            $user->image = 'images/' . $fileName;
        }

        // Cập nhật mật khẩu (nếu có)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $flasher->addSuccess('Người dùng đã được cập nhật thành công');
        return redirect()->route('users.index');
    }

    // Xóa người dùng
    public function destroy($id, FlasherInterface $flasher)
    {
        $user = User::findOrFail($id);
        if ($user->image && file_exists(public_path($user->image))) {
            unlink(public_path($user->image));
        }
        $user->delete();

        $flasher->addSuccess('Người dùng đã được xóa thành công');
        return redirect()->route('users.index');
    }

    // Hiển thị form quên mật khẩu
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgetPassword');
    }

    // Gửi liên kết đặt lại mật khẩu
    public function sendResetLinkEmail(Request $request, FlasherInterface $flasher)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            $flasher->addSuccess('Liên kết đặt lại mật khẩu đã được gửi!');
            return back();
        }

        $flasher->addError('Không thể gửi liên kết đặt lại mật khẩu.');
        return back();
    }

    // Hiển thị form đặt lại mật khẩu
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwordConfirmation')->with(['token' => $token, 'email' => $request->email]);
    }

    // Xử lý đặt lại mật khẩu
    public function reset(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                Auth::login($user);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $flasher->addSuccess('Mật khẩu đã được đặt lại thành công!');
            return redirect()->route('home');
        }

        $flasher->addError('Không thể đặt lại mật khẩu.');
        return back()->withErrors(['email' => [__($status)]]);
    }

    
}
