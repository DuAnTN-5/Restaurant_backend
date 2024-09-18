<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Flasher\Prime\FlasherInterface;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('admin.auth.forgotPassword');
    }

    public function sendResetLinkEmail(Request $request, FlasherInterface $flasher)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            $flasher->addSuccess('Liên kết đặt lại mật khẩu đã được gửi!');
            return back();
        }

        $flasher->addError('Không thể gửi liên kết đặt lại mật khẩu.');
        return back();
    }
}
