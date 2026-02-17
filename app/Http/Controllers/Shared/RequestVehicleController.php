<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestVehicleController extends Controller
{
    public function index()
    {
        return view('pages.shared.request-vehicle');
    }

    public function store()
    {
        $user = Auth::user();

        // prevent duplicate pending
        $existingRequest = Notification::where('requester_id', $user->id)
            ->where('type', 'vehicle_repair_request')
            ->where('status', true)
            ->exists();

        if ($existingRequest) {
            return back()->with('error', 'You already have a pending vehicle repair request.');
        }

        $fullName = optional($user->profile)->first_name . ' ' .
            optional($user->profile)->last_name;

        $admins = User::where('role', 'admin')->pluck('id');

        foreach ($admins as $adminId) {
            Notification::create([
                'user_id' => $adminId,
                'requester_id' => $user->id,
                'type' => 'vehicle_repair_request',
                'title' => 'Vehicle Repair Request',
                'message' => "{$fullName} requested a vehicle repair.",
                'status' => true,
            ]);
        }

        return back()->with('success', 'Vehicle repair request submitted.');
    }

    public function repairIndex()
    {
        $requests = Notification::with(['requester.profile'])
            ->whereIn('type', [
                'vehicle_repair_request',
                'vehicle_repair_approved',
                'vehicle_repair_rejected'
            ])
            ->latest()
            ->paginate(10);

        return view('pages.shared.vehicle-repair', compact('requests'));
    }
}
