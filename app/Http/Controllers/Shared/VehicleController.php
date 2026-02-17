<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | MAIN QUERY
    |--------------------------------------------------------------------------
    */
        $query = Vehicle::query();

        /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

        // STATUS FILTER (case insensitive)
        if ($request->status) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
        }

        // SEARCH PLATE NUMBER
        if ($request->search) {
            $query->where('plate_number', 'like', '%' . $request->search . '%');
        }

        // MONTH FILTER
        if ($request->month) {
            $query->whereMonth('created_at', $request->month);
        }

        // YEAR FILTER
        if ($request->year) {
            $query->whereYear('created_at', $request->year);
        }

        $vehicles = $query->latest()->paginate(9)->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | COUNTS (SECURED LIKE TICKETS)
    |--------------------------------------------------------------------------
    */

        $baseCountQuery = Vehicle::query();

        $counts = [
            'total' => (clone $baseCountQuery)->count(),

            'available' => (clone $baseCountQuery)
                ->whereRaw("LOWER(status) = 'available'")
                ->count(),

            'deployed' => (clone $baseCountQuery)
                ->whereRaw("LOWER(status) = 'deployed'")
                ->count(),

            'repair' => (clone $baseCountQuery)
                ->whereRaw("LOWER(status) = 'in repair'")
                ->count(),

            'inactive' => (clone $baseCountQuery)
                ->whereRaw("LOWER(status) = 'inactive'")
                ->count(),
        ];

        return view('pages.shared.manage-vehicle', compact('vehicles', 'counts'));
    }

    public function create()
    {
        return view('pages.shared.vehicles-create');
    }

    public function store(Request $request)
    {
        $validated = [];
        $uploadedImage = null;

        try {

            // VALIDATE
            $validated = $request->validate([
                'plate_number' => 'required|string|max:50|unique:vehicles,plate_number',
                'vehicle_type' => 'nullable|string|max:100',
                'make' => 'nullable|string|max:100',
                'model' => 'nullable|string|max:100',
                'year' => 'nullable|integer|min:1980|max:' . date('Y'),
                'color' => 'nullable|string|max:50',
                'engine_number' => 'nullable|string|max:100',
                'chassis_number' => 'nullable|string|max:100',
                'fuel_type' => 'nullable|string|max:50',
                'fuel_tank_capacity' => 'nullable|numeric|min:0',
                'current_fuel_level' => 'nullable|numeric|min:0',
                'status' => 'required|string|max:50',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'description' => 'nullable|string|max:1000',
            ]);

            DB::beginTransaction();

            // IMAGE UPLOAD
            if ($request->hasFile('image')) {
                $uploadedImage = $request->file('image')->store('vehicles', 'public');
                $validated['image'] = $uploadedImage;
            }

            // FORMAT STRINGS
            $validated['vehicle_type'] = !empty($validated['vehicle_type']) ? Str::title($validated['vehicle_type']) : null;
            $validated['make'] = !empty($validated['make']) ? Str::title($validated['make']) : null;
            $validated['model'] = !empty($validated['model']) ? Str::title($validated['model']) : null;
            $validated['color'] = !empty($validated['color']) ? Str::title($validated['color']) : null;

            // CREATE
            $vehicle = Vehicle::create($validated);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Vehicle registered successfully!',
                'vehicle' => $vehicle
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        } catch (Throwable $e) {

            DB::rollBack();

            Log::error($e);

            if ($uploadedImage) {
                Storage::disk('public')->delete($uploadedImage);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save vehicle.',
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Vehicle $vehicle)
    {
        return response()->json([
            'vehicle' => $vehicle,
            'fuelPercentage' => $vehicle->fuelPercentage(),
        ]);
    }

    public function edit(Vehicle $vehicle)
    {
        return view('pages.shared.vehicles-edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'plate_number' => "required|string|max:50|unique:vehicles,plate_number,{$vehicle->id}",
            'vehicle_type' => 'nullable|string|max:100',
            'make' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1980|max:' . date('Y'),
            'color' => 'nullable|string|max:50',
            'engine_number' => 'nullable|string|max:100',
            'chassis_number' => 'nullable|string|max:100',
            'fuel_type' => 'nullable|string|max:50',
            'fuel_tank_capacity' => 'nullable|numeric|min:0',
            'current_fuel_level' => 'nullable|numeric|min:0',
            'status' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('image')) {
            if ($vehicle->image) {
                Storage::disk('public')->delete($vehicle->image);
            }
            $validated['image'] = $request->file('image')->store('vehicles', 'public');
        }

        // Format strings
        $validated['vehicle_type'] = !empty($validated['vehicle_type']) ? Str::title($validated['vehicle_type']) : null;
        $validated['make'] = !empty($validated['make']) ? Str::title($validated['make']) : null;
        $validated['model'] = !empty($validated['model']) ? Str::title($validated['model']) : null;
        $validated['color'] = !empty($validated['color']) ? Str::title($validated['color']) : null;

        $vehicle->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle updated successfully!',
            'vehicle' => $vehicle,
        ]);
    }
}
