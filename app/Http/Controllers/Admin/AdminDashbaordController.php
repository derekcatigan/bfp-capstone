<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashbaordController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('profile');

        return view('pages.admin.admin-dashbaord', compact('user'));
    }
}
