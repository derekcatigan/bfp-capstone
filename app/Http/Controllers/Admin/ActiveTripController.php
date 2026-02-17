<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TripTicket;
use Illuminate\Http\Request;

class ActiveTripController extends Controller
{
    public function index()
    {
        $activeTrips = TripTicket::with([
            'driver.profile',
            'vehicle'
        ])
            ->where('status', 'active')
            ->latest()
            ->get();

        return view('pages.admin.active-trip', compact('activeTrips'));
    }
}
