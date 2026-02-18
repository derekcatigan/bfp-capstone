<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestVehicleController extends Controller
{
    public function index()
    {
        return view('pages.shared.request-vehicle');
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'notes' => 'required|string|max:1000'
        ]);

        $user = Auth::user();

        $pendingRequest = Notification::where('requester_id', $user->id)->where('type', 'vehicle_repair_request')->where('status', true)->exists();

        if ($pendingRequest) {
            return response()->json(['message' => 'You already have a pending repair request. Please wait for admin approval.'], 422);
        }

        $vehicle = Vehicle::where('id', $request->vehicle_id)->where('status', 'Available')->whereDoesntHave('tripTickets', function ($query) {
            $query->where('status', 'active');
        })->first();

        if (!$vehicle) {
            return response()->json(['message' => 'Vehicle is no longer available.'], 422);
        }

        $admins = User::where('role', 'admin')->pluck('id');

        $message = "{$user->profile->first_name} requested repair for {$vehicle->plate_number}.\n\n";
        $message .= "Reported Issue:\n{$request->notes}";

        foreach ($admins as $adminId) {
            Notification::create([
                'user_id' => $adminId,
                'requester_id' => $user->id,
                'type' => 'vehicle_repair_request',
                'title' => 'Vehicle Repair Request',
                'message' => $message,
                'status' => true,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Request submitted successfully!']);
    }
    public function availableVehicles()
    {
        $vehicles = Vehicle::where('status', 'Available')->whereDoesntHave('tripTickets', function ($query) {
            $query->where('status', 'active');
        })->get(['id', 'plate_number', 'model']);

        return response()->json($vehicles);
    }

    public function repairIndex()
    {
        $requests = Notification::with(['requester.profile'])
            ->where('user_id', Auth::id())
            ->whereIn('type', [
                'vehicle_repair_request',
            ])
            ->latest()
            ->paginate(10);

        return view('pages.shared.vehicle-repair', compact('requests'));
    }
}
