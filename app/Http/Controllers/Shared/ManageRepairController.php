<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleExpense;
use Illuminate\Http\Request;

class ManageRepairController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::where('status', 'In Repair')->get();
        return view('pages.shared.manage-repair', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'fuel_total' => 'nullable|numeric|min:0',
            'gear_oil_total' => 'nullable|numeric|min:0',
            'lub_oil_total' => 'nullable|numeric|min:0',
            'grease_total' => 'nullable|numeric|min:0',
            'repair_total' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        $expenses = [
            'fuel' => $request->fuel_total,
            'gear_oil' => $request->gear_oil_total,
            'lub_oil' => $request->lub_oil_total,
            'grease' => $request->grease_total,
            'repair' => $request->repair_total,
        ];

        foreach ($expenses as $type => $total) {
            if ($total && $total > 0) {
                $typeLabel = ucfirst(str_replace('_', ' ', $type));
                VehicleExpense::create([
                    'vehicle_id' => $vehicle->id,
                    'type' => $type,
                    'total_cost' => $total,
                    'expense_date' => now(),
                    'description'  => $request->description
                        ? $typeLabel . ' - ' . $request->description
                        : $typeLabel . ' expense',
                ]);
            }
        }

        $vehicle->update(['status' => 'In Repair']);

        return response()->json(['success' => true, 'message' => 'Vehicle repair recorded successfully.']);
    }
}
