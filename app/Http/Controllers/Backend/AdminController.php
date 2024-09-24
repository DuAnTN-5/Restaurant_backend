<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        return view('admin.index', compact('totalUsers'));
    }
}
