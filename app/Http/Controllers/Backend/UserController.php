<?php
namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            $users = User::where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone_number', 'LIKE', "%{$search}%")
                ->paginate(10);
        } else {
            $users = User::paginate(10);
        }

        return view('admin.users.index', compact('users'));
    }

    // Cập nhật trạng thái người dùng qua AJAX
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $user = User::find($request->id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => true, 'status' => $user->status]);
    }

    // Hiển thị form tạo người dùng mới
    public function create()
    {
        $config = [
            'js' => ['https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'],
            'css' => ['https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css']
        ];
        return view('admin.users.create', compact('config'));
    }

    // Lưu người dùng mới
    public function store(Request $request, FlasherInterface $flasher)
    {
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

        $imagePath = null;
        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $fileName);
            $imagePath = 'images/' . $fileName;
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $imagePath,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'sex' => $request->sex,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'status' => 'active',
        ]);

        $flasher->addSuccess('Người dùng đã được thêm thành công');
        return redirect()->route('users.index');
    }

    // Hiển thị form chỉnh sửa người dùng
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $config = [
            'js' => ['https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'],
            'css' => ['https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css']
        ];
        return view('admin.users.edit', compact('user', 'config'));
    }

    // Cập nhật thông tin người dùng
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:15',
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

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->date_of_birth = $request->date_of_birth;
        $user->sex = $request->sex;
        $user->address = $request->address;

        if ($request->hasFile('image')) {
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $fileName);
            $user->image = 'images/' . $fileName;
        }

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
                $user->forceFill(['password' => Hash::make($password)])->save();
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
