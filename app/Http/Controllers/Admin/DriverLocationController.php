<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TripTicket;
use Illuminate\Http\Request;

class DriverLocationController extends Controller
{
    public function index(TripTicket $trip)
    {
        // Ensure there's a tracking row
        $tracking = $trip->tracking()->firstOrCreate(
            ['trip_id' => $trip->id],
            ['is_tracking' => true, 'started_at' => now()]
        );

        return view('pages.shared.driver-location', compact('trip', 'tracking'));
    }

    public function toggleTracking(Request $request, TripTicket $trip)
    {
        $tracking = $trip->tracking()->firstOrCreate(
            ['trip_id' => $trip->id],
            ['is_tracking' => true, 'started_at' => now()]
        );

        $tracking->is_tracking = !$tracking->is_tracking;

        if ($tracking->is_tracking) {
            $tracking->started_at = now();
            $tracking->stopped_at = null;
        } else {
            $tracking->stopped_at = now();
        }

        $tracking->save();

        return response()->json([
            'is_tracking' => $tracking->is_tracking,
            'started_at' => $tracking->started_at,
            'stopped_at' => $tracking->stopped_at,
        ]);
    }

    public function updateLocation(Request $request, TripTicket $trip)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $tracking = $trip->tracking()->firstOrCreate(
            ['trip_id' => $trip->id],
            ['is_tracking' => true, 'started_at' => now()]
        );

        if (!$tracking->is_tracking) {
            return response()->json(['status' => 'tracking_disabled'], 403);
        }

        $trip->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        $tracking->update(['last_ping_at' => now()]);

        return response()->json(['status' => 'ok']);
    }
}
