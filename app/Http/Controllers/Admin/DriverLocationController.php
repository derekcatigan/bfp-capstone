<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TripTicket;
use Illuminate\Http\Request;

class DriverLocationController extends Controller
{
    public function index(TripTicket $trip)
    {
        return view('pages.shared.driver-location', compact('trip'));
    }

    public function updateLocation(Request $request, TripTicket $trip)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $trip->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['status' => 'ok']);
    }
}
