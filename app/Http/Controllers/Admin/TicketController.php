<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vehicle;
use App\Enum\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\FuelStorage;
use App\Models\TripTicket;
use App\Models\User;
use Carbon\Carbon;
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
            'place_lat' => ['required', 'numeric', 'between:-90,90'],
            'place_lng' => ['required', 'numeric', 'between:-180,180'],
            'purpose' => ['required', 'string', 'max:500'],

            // ================= TIME =================
            'time_departed' => ['nullable', 'date_format:H:i'],
            'time_arrival_destination' => ['nullable', 'date_format:H:i'],
            'time_departure_destination' => ['nullable', 'date_format:H:i'],
            'time_arrival_garage' => ['nullable', 'date_format:H:i'],

            // ================= NUMERIC TRIP DATA =================
            'distance' => ['nullable', 'numeric', 'min:0', 'max:2000'],
            'balance_tank' => ['nullable', 'numeric', 'min:0'],
            'issued_stock' => ['nullable', 'numeric', 'min:0'],
            'purchased_trip' => ['nullable', 'numeric', 'min:0'],
            'deduct_trip' => ['nullable', 'numeric', 'min:0'],

            'gear_oil' => ['nullable', 'numeric', 'min:0'],
            'lub_oil' => ['nullable', 'numeric', 'min:0'],
            'grease_issued' => ['nullable', 'numeric', 'min:0'],

            // ================= SPEEDOMETER =================
            'speedometer_start' => ['nullable', 'numeric', 'min:0'],
            'speedometer_end' => ['nullable', 'numeric', 'gt:speedometer_start'],

            'remarks' => ['nullable', 'string', 'max:1000'],

            // ================= PASSENGERS =================
            'passenger_name1' => ['nullable', 'string', 'max:255', 'nullable_with:passenger_date1'],
            'passenger_date1' => ['nullable', 'date', 'nullable_with:passenger_name1'],

            'passenger_name2' => ['nullable', 'string', 'max:255', 'nullable_with:passenger_date2'],
            'passenger_date2' => ['nullable', 'date', 'nullable_with:passenger_name2'],

            'passenger_name3' => ['nullable', 'string', 'max:255', 'nullable_with:passenger_date3'],
            'passenger_date3' => ['nullable', 'date', 'nullable_with:passenger_name3'],
        ]);

        try {
            DB::beginTransaction();

            $userId = Auth::id();

            // Check if driver already has an active ticket
            $existingActiveTicket = TripTicket::where('driver_id', $validated['driver_id'])
                ->where('status', 'active')
                ->first();

            if ($existingActiveTicket) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This driver already has an active trip ticket.',
                ], 422);
            }

            // Check if vehicle is already deployed
            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
            if ($vehicle->status === 'Deployed') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This vehicle is currently deployed. Please select another one.',
                ], 422);
            }

            // ===== Calculate new fuel level =====
            $balanceTank = $validated['balance_tank'] ?? 0;
            $issuedStock = $validated['issued_stock'] ?? 0;
            $purchasedTrip = $validated['purchased_trip'] ?? 0;
            $deductTrip = $validated['deduct_trip'] ?? 0;

            // Check FuelStorage for issued stock
            if ($issuedStock > 0) {
                $latestBalance = FuelStorage::latest('transaction_datetime')->value('running_balance') ?? 0;
                if ($issuedStock > $latestBalance) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Cannot issue {$issuedStock}L: only {$latestBalance}L available in storage.",
                    ], 422);
                }
            }

            // Calculate new fuel level
            $newFuelLevel = $balanceTank + $issuedStock + $purchasedTrip - $deductTrip;

            // Prevent exceeding vehicle tank capacity
            if ($vehicle->fuel_tank_capacity !== null && $newFuelLevel > $vehicle->fuel_tank_capacity) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Cannot create trip: calculated fuel level ({$newFuelLevel}L) exceeds vehicle tank capacity ({$vehicle->fuel_tank_capacity}L).",
                ], 422);
            }

            // ===== Create Trip Ticket =====
            $ticket = TripTicket::create([
                'user_id' => $userId,
                'control_no' => $validated['control_no'],
                'ticket_date' => $validated['ticket_date'],
                'driver_id' => $validated['driver_id'],
                'vehicle_id' => $validated['vehicle_id'],
                'authorized_passenger' => $validated['authorized_passenger'],
                'place' => $validated['places_visit'],
                'latitude' => $validated['place_lat'],
                'longitude' => $validated['place_lng'],
                'purpose' => $validated['purpose'],
                'time_departed_garage' => $validated['time_departed'],
                'time_arrival_destination' => $validated['time_arrival_destination'],
                'time_departure_destination' => $validated['time_departure_destination'],
                'time_arrival_garage' => $validated['time_arrival_garage'],
                'approx_distance' => $validated['distance'],
                'balance_tank' => $balanceTank,
                'issued_stock' => $issuedStock,
                'purchased_trip' => $purchasedTrip,
                'deduct_trip' => $deductTrip,
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

            // ===== Update vehicle fuel level =====
            $vehicle->update([
                'current_fuel_level' => $newFuelLevel
            ]);

            // ===== Deduct issued stock from FuelStorage =====
            if ($issuedStock > 0) {
                $latestBalance = FuelStorage::latest('transaction_datetime')->value('running_balance') ?? 0;
                FuelStorage::create([
                    'transaction_datetime' => Carbon::now(),
                    'container_type' => 'Main Tank',
                    'transaction_type' => 'removed',
                    'amount' => $issuedStock,
                    'running_balance' => max($latestBalance - $issuedStock, 0),
                    'note' => "Issued for trip ticket: {$ticket->control_no}"
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Trip ticket created successfully and vehicle fuel level updated.',
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
        $ticket->load('vehicle');

        $drivers = User::with('profile')
            ->where('role', RoleEnum::DriverRole)
            ->get();

        $vehicles = Vehicle::where('status', 'Available')
            ->orWhere('id', $ticket->vehicle_id)
            ->get();

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
            'time_departed_garage' => 'nullable',
            'time_arrival_destination' => 'nullable',
            'time_departure_destination' => 'nullable',
            'time_arrival_garage' => 'nullable',
            'approx_distance' => 'nullable|numeric',
            'balance_tank' => 'nullable|numeric',
            'issued_stock' => 'nullable|numeric',
            'purchased_trip' => 'nullable|numeric',
            'deduct_trip' => 'nullable|numeric',
            'gear_oil_issued' => 'nullable|numeric',
            'lub_oil_issued' => 'nullable|numeric',
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

        DB::transaction(function () use ($ticket, $validated) {

            $vehicle = $ticket->vehicle;

            /** ------------------------------------------------
             *  COMPUTE NEW FUEL LEVEL
             * ------------------------------------------------*/
            $newFuelLevel =
                ($validated['balance_tank'] ?? 0)
                + ($validated['issued_stock'] ?? 0)
                + ($validated['purchased_trip'] ?? 0)
                - ($validated['deduct_trip'] ?? 0);

            if ($vehicle->fuel_tank_capacity !== null && $newFuelLevel > $vehicle->fuel_tank_capacity) {
                throw new Exception("Fuel exceeds tank capacity.");
            }

            /** ------------------------------------------------
             * HANDLE STORAGE DIFFERENCE (THE FIX)
             * ------------------------------------------------*/
            $oldIssued = $ticket->issued_stock ?? 0;
            $newIssued = $validated['issued_stock'] ?? 0;

            $difference = $newIssued - $oldIssued;

            if ($difference != 0) {

                $latestBalance = FuelStorage::latest('transaction_datetime')->value('running_balance') ?? 0;

                // If additional deduction
                if ($difference > 0) {

                    if ($difference > $latestBalance) {
                        throw new Exception("Not enough fuel in storage. Available: {$latestBalance}L");
                    }

                    $newBalance = $latestBalance - $difference;
                    $type = 'removed';
                    $note = "Adjustment: additional issued for ticket {$ticket->control_no}";
                }
                // If returned fuel
                else {

                    $difference = abs($difference);
                    $newBalance = $latestBalance + $difference;
                    $type = 'added';
                    $note = "Adjustment: returned fuel for ticket {$ticket->control_no}";
                }

                FuelStorage::create([
                    'transaction_datetime' => now(),
                    'container_type' => 'Main Tank',
                    'transaction_type' => $type,
                    'amount' => $difference,
                    'running_balance' => $newBalance,
                    'note' => $note
                ]);
            }

            /** ------------------------------------------------
             * UPDATE TICKET
             * ------------------------------------------------*/
            $ticket->update($validated);

            /** ------------------------------------------------
             * UPDATE VEHICLE TANK LEVEL
             * ------------------------------------------------*/
            $vehicle->update([
                'current_fuel_level' => $newFuelLevel
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Trip Ticket Updated Successfully!'
        ]);
    }


    public function submit(TripTicket $ticket)
    {
        if ($ticket->status !== 'active') {
            return response()->json([
                'message' => 'Ticket already processed.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update ticket status
            $ticket->update(['status' => 'submitted']);

            // Update vehicle status back to Available
            $ticket->vehicle()->update(['status' => 'Available']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trip ticket submitted successfully and vehicle status updated.'
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function activate(TripTicket $ticket)
    {
        if ($ticket->status !== 'pending') {
            return response()->json([
                'message' => 'Ticket must be pending first.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update ticket status
            $ticket->update(['status' => 'active']);

            // Update vehicle status to Deployed
            $ticket->vehicle()->update(['status' => 'Deployed']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trip ticket active and vehicle status updated.'
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function destroy(TripTicket $ticket)
    {
        if (Auth::user()->role !== RoleEnum::AdminRole) {
            abort(403);
        }

        DB::beginTransaction();

        try {

            /*
        |--------------------------------------------------------------------------
        | Restore vehicle state depending on ticket status
        |--------------------------------------------------------------------------
        */

            if ($ticket->status === 'active') {
                // vehicle currently deployed
                $ticket->vehicle()->update(['status' => 'Available']);
            }

            if ($ticket->status === 'submitted') {
                // already returned — keep available
                $ticket->vehicle()->update(['status' => 'Available']);
            }

            // delete ticket
            $ticket->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trip ticket deleted successfully.'
            ]);
        } catch (Throwable $e) {

            DB::rollBack();
            Log::error($e);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete ticket.'
            ], 500);
        }
    }
}
