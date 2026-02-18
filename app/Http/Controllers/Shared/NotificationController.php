<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->inbox()
            ->latest()
            ->paginate(15);

        return view('pages.shared.notification-list', compact('notifications'));
    }
}
