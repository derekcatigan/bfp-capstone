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

    public function location(TripTicket $trip)
    {
        if ($trip->status !== 'active') {
            return response()->json(['error' => 'Trip not active'], 403);
        }

        return response()->json([
            'latitude' => $trip->latitude,
            'longitude' => $trip->longitude,
            'control_no' => $trip->control_no,
            'driver_name' => $trip->driver?->profile?->first_name . ' ' . $trip->driver?->profile?->last_name,
        ]);
    }
}
