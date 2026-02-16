<?php

namespace App\Http\Controllers\Admin;

use App\Enum\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\TripTicket;
use App\Models\User;
use App\Models\Vehicle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        $userId = $user->id;

        /*
    |--------------------------------------------------------------------------
    | MAIN QUERY (tickets list)
    |--------------------------------------------------------------------------
    */
        $query = TripTicket::with([
            'user.profile',
            'driver.profile',
        ]);

        // ===== ROLE RESTRICTIONS =====
        if ($role === RoleEnum::AdminRole) {
            // Admin sees all tickets
        } elseif ($role === RoleEnum::DriverRole) {
            // Driver sees only assigned tickets
            $query->where('driver_id', $userId);
        } else {
            // Regular user sees only created tickets
            $query->where('user_id', $userId);
        }

        /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('control_no', 'like', '%' . $request->search . '%');
        }

        if ($request->month) {
            $query->whereMonth('created_at', $request->month);
        }

        if ($request->year) {
            $query->whereYear('created_at', $request->year);
        }

        $tickets = $query->latest()->paginate(8)->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | COUNTS (SECURED — SAME ROLE FILTER)
    |--------------------------------------------------------------------------
    */
        $baseCountQuery = TripTicket::query();

        if ($role === RoleEnum::DriverRole) {
            $baseCountQuery->where('driver_id', $userId);
        } elseif ($role !== RoleEnum::AdminRole) {
            $baseCountQuery->where('user_id', $userId);
        }

        $counts = [
            'total' => (clone $baseCountQuery)->count(),
            'pending' => (clone $baseCountQuery)->where('status', 'Pending')->count(),
            'active' => (clone $baseCountQuery)->where('status', 'Active')->count(),
            'submitted' => (clone $baseCountQuery)->where('status', 'Submitted')->count(),
        ];

        return view('pages.shared.manage-tickets', compact('tickets', 'counts'));
    }

    public function create()
    {
        $drivers = User::with('profile')
            ->where('role', RoleEnum::DriverRole)
            ->get();

        $vehicles = Vehicle::where('status', 'Available')->get();

        return view('pages.admin.issue-ticket', compact('drivers', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // ================= ADMIN SECTION =================
            'control_no' => ['required', 'string', 'max:50', 'unique:trip_tickets,control_no'],
            'ticket_date' => ['required', 'date', 'before_or_equal:today'],

            'driver_id' => [
                'required',
                'uuid',
                Rule::exists('users', 'id')->where('role', 'driver'),
            ],

            'vehicle_id' => [
                'required',
                'uuid',
                Rule::exists('vehicles', 'id')->where('status', 'Available'),
            ],

            'authorized_passenger' => ['required', 'string', 'max:255'],
            'places_visit' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:500'],

            // ================= TIME =================
            'time_departed' => ['required', 'date_format:H:i'],
            'time_arrival_destination' => ['required', 'date_format:H:i'],
            'time_departure_destination' => ['required', 'date_format:H:i'],
            'time_arrival_garage' => ['required', 'date_format:H:i'],

            // ================= NUMERIC TRIP DATA =================
            'distance' => ['required', 'numeric', 'min:0', 'max:2000'],
            'balance_tank' => ['required', 'numeric', 'min:0'],
            'issued_stock' => ['required', 'numeric', 'min:0'],
            'purchased_trip' => ['required', 'numeric', 'min:0'],
            'deduct_trip' => ['required', 'numeric', 'min:0'],

            'gear_oil' => ['required', 'numeric', 'min:0'],
            'lub_oil' => ['required', 'numeric', 'min:0'],
            'grease_issued' => ['required', 'numeric', 'min:0'],

            // ================= SPEEDOMETER =================
            'speedometer_start' => ['required', 'numeric', 'min:0'],
            'speedometer_end' => ['required', 'numeric', 'gt:speedometer_start'],

            'remarks' => ['nullable', 'string', 'max:1000'],

            // ================= PASSENGERS =================
            'passenger_name1' => ['nullable', 'string', 'max:255', 'required_with:passenger_date1'],
            'passenger_date1' => ['nullable', 'date', 'required_with:passenger_name1'],

            'passenger_name2' => ['nullable', 'string', 'max:255', 'required_with:passenger_date2'],
            'passenger_date2' => ['nullable', 'date', 'required_with:passenger_name2'],

            'passenger_name3' => ['nullable', 'string', 'max:255', 'required_with:passenger_date3'],
            'passenger_date3' => ['nullable', 'date', 'required_with:passenger_name3'],
        ]);

        try {
            DB::beginTransaction();

            $userId = Auth::id();

            TripTicket::create([
                'user_id' => $userId,
                'control_no' => $validated['control_no'],
                'ticket_date' => $validated['ticket_date'],
                'driver_id' => $validated['driver_id'],
                'vehicle_id' => $validated['vehicle_id'],
                'authorized_passenger' => $validated['authorized_passenger'],
                'place' => $validated['places_visit'],
                'purpose' => $validated['purpose'],
                'time_departed_garage' => $validated['time_departed'],
                'time_arrival_destination' => $validated['time_arrival_destination'],
                'time_departure_destination' => $validated['time_departure_destination'],
                'time_arrival_garage' => $validated['time_arrival_garage'],
                'approx_distance' => $validated['distance'],
                'balance_tank' => $validated['balance_tank'],
                'issued_stock' => $validated['issued_stock'],
                'purchased_trip' => $validated['purchased_trip'],
                'deduct_trip' => $validated['deduct_trip'],
                'gear_oil_issued' => $validated['gear_oil'],
                'lub_oil_issued' => $validated['lub_oil'],
                'grease_issued' => $validated['grease_issued'],
                'speedometer_start' => $validated['speedometer_start'],
                'speedometer_end' => $validated['speedometer_end'],
                'remarks' => $validated['remarks'],
                'passenger_name1' => $validated['passenger_name1'],
                'passenger_date1' => $validated['passenger_date1'],
                'passenger_name2' => $validated['passenger_name2'],
                'passenger_date2' => $validated['passenger_date2'],
                'passenger_name3' => $validated['passenger_name3'],
                'passenger_date3' => $validated['passenger_date3'],
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Trip ticket created successfully.',
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error($e);

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function show(TripTicket $ticket)
    {
        $ticket->load([
            'driver.profile',
            'vehicle'
        ]);

        return response()->json($ticket);
    }

    public function edit(TripTicket $ticket)
    {
        $drivers = User::with('profile')
            ->where('role', RoleEnum::DriverRole)
            ->get();

        $vehicles = Vehicle::where('status', 'Available')->get();

        return view('pages.shared.edit-ticket', compact('ticket', 'drivers', 'vehicles'));
    }

    public function update(Request $request, TripTicket $ticket)
    {
        $validated = $request->validate([
            'control_no' => 'required|string|max:255',
            'ticket_date' => 'required|date',
            'driver_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'authorized_passenger' => 'nullable|string|max:255',
            'places_visit' => 'nullable|string|max:255',
            'purpose' => 'nullable|string',
            'time_departed' => 'nullable',
            'time_arrival_destination' => 'nullable',
            'time_departure_destination' => 'nullable',
            'time_arrival_garage' => 'nullable',
            'distance' => 'nullable|numeric',
            'balance_tank' => 'nullable|numeric',
            'issued_stock' => 'nullable|numeric',
            'purchased_trip' => 'nullable|numeric',
            'deduct_trip' => 'nullable|numeric',
            'gear_oil' => 'nullable|numeric',
            'lub_oil' => 'nullable|numeric',
            'grease_issued' => 'nullable|numeric',
            'speedometer_start' => 'nullable|numeric',
            'speedometer_end' => 'nullable|numeric',
            'remarks' => 'nullable|string',
            'passenger_name1' => 'nullable|string|max:255',
            'passenger_date1' => 'nullable|date',
            'passenger_name2' => 'nullable|string|max:255',
            'passenger_date2' => 'nullable|date',
            'passenger_name3' => 'nullable|string|max:255',
            'passenger_date3' => 'nullable|date',
        ]);

        $ticket->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Trip Ticket Updated Successfully!'
        ]);
    }

    public function submit(TripTicket $ticket)
    {
        if (Auth::user()->role !== RoleEnum::DriverRole) {
            abort(403);
        }

        if ($ticket->status !== 'active') {
            return response()->json([
                'message' => 'Ticket already processed.'
            ], 422);
        }

        $ticket->update(['status' => 'Submitted']);

        return response()->json([
            'success' => true,
            'message' => 'Trip ticket submitted successfully.'
        ]);
    }

    public function activate(TripTicket $ticket)
    {
        if (Auth::user()->role !== RoleEnum::AdminRole) {
            abort(403);
        }

        if ($ticket->status !== 'pending') {
            return response()->json([
                'message' => 'Ticket must be pending first.'
            ], 422);
        }

        $ticket->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => 'Trip ticket active.'
        ]);
    }
}
