<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\VehicleExpense;
use Illuminate\Http\Request;

class VehicleExpenseAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | BASE QUERY
    |--------------------------------------------------------------------------
    */
        $query = VehicleExpense::with('vehicle');

        /*
    |--------------------------------------------------------------------------
    | SEARCH
    | plate number / description / type
    |--------------------------------------------------------------------------
    */
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('type', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhereHas('vehicle', function ($v) use ($request) {
                        $v->where('plate_number', 'like', "%{$request->search}%");
                    });
            });
        }

        /*
    |--------------------------------------------------------------------------
    | MONTH FILTER
    |--------------------------------------------------------------------------
    */
        if ($request->month) {
            $query->whereMonth('expense_date', $request->month);
        }

        /*
    |--------------------------------------------------------------------------
    | YEAR FILTER
    |--------------------------------------------------------------------------
    */
        if ($request->year) {
            $query->whereYear('expense_date', $request->year);
        }

        /*
    |--------------------------------------------------------------------------
    | TOTALS (IMPORTANT: clone query so totals follow filter)
    |--------------------------------------------------------------------------
    */
        $expenseTotals = (clone $query)
            ->selectRaw('type, SUM(total_cost) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        /*
    |--------------------------------------------------------------------------
    | PAGINATED DATA
    |--------------------------------------------------------------------------
    */
        $recentExpenses = $query
            ->orderBy('expense_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.analytics.vehicle-analytics', compact('expenseTotals', 'recentExpenses'));
    }
}
