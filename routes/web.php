<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\UserController;

// Route chuyển hướng trang gốc đến trang đăng nhập
Route::get('/', function () {
    return redirect()->route('login');
});

// Định nghĩa route cho Auth (Đăng nhập, Đăng ký, Đặt lại mật khẩu)
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login'); // Hiển thị form đăng nhập
    Route::post('/login', 'login')->name('login');        // Xử lý đăng nhập
    Route::post('/logout', 'logout')->name('logout');     // Xử lý đăng xuất
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register'); // Hiển thị form đăng ký
    Route::post('/register', 'register')->name('register');            // Xử lý đăng ký
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'showLinkRequestForm')->name('forgotPassword'); // Hiển thị form yêu cầu đặt lại mật khẩu
    Route::post('/forgot-password', 'sendResetLinkEmail')->name('password.email'); // Xử lý gửi email đặt lại mật khẩu
});

// Định nghĩa route cho Admin Dashboard và Quản lý người dùng
Route::middleware('auth')->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('admin');

    // User Management Routes
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index'); // List users
        Route::get('/users/create', 'create')->name('users.create'); // Form to create a new user
        Route::post('/users/store', 'store')->name('users.store'); // Store a new user
        Route::get('/users/{user}/edit', 'edit')->name('users.edit'); // Form to edit a user
        Route::put('/users/{user}', 'update')->name('users.update'); // Update a user
        Route::delete('/users/{user}', 'destroy')->name('users.destroy'); // Delete a user
    });
});

// Định nghĩa route cho Facebook Auth
Route::controller(FacebookController::class)->group(function () {
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});
