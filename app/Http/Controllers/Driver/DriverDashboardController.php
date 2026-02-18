<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('profile');
        return view('pages.driver.driver-dashboard', compact('user'));
    }
}
