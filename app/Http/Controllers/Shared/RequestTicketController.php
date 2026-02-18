<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestTicketController extends Controller
{
    public function index()
    {
        return view('pages.shared.request-ticket');
    }

    public function store()
    {
        $user = Auth::user();

        // 🚫 Check if user already has a pending request
        $existingRequest = Notification::where('requester_id', $user->id)
            ->where('type', 'trip_ticket_request')
            ->where('status', true)
            ->exists();

        if ($existingRequest) {
            return back()->with('error', 'You already have a pending trip ticket request. Please wait for admin approval.');
        }

        $fullName = optional($user->profile)->first_name . ' ' .
            optional($user->profile)->last_name;

        // Get all admins
        $admins = User::where('role', 'admin')->pluck('id');

        foreach ($admins as $adminId) {
            Notification::create([
                'user_id' => $adminId,
                'requester_id' => $user->id,
                'type' => 'trip_ticket_request',
                'title' => 'Request Trip Ticket',
                'message' => "{$fullName} requested a trip ticket.",
                'status' => true, // pending
            ]);
        }

        return back()->with('success', 'Trip ticket request submitted successfully.');
    }

    public function ticketIndex()
    {
        $requests = Notification::with(['requester.profile'])
            ->where('user_id', Auth::id())
            ->whereIn('type', [
                'trip_ticket_request',
            ])
            ->latest()
            ->paginate(10);

        return view('pages.shared.manage-request-ticket', compact('requests'));
    }
}
