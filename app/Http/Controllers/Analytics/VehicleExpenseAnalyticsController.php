<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\VehicleExpense;
use Illuminate\Http\Request;

class VehicleExpenseAnalyticsController extends Controller
{
    public function index()
    {
        // Aggregate total expenses by type
        $expenseTotals = VehicleExpense::selectRaw('type, SUM(total_cost) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        // Paginate recent expenses, 10 per page
        $recentExpenses = VehicleExpense::with('vehicle')
            ->orderBy('expense_date', 'desc')
            ->paginate(10);

        return view('pages.analytics.vehicle-analytics', compact('expenseTotals', 'recentExpenses'));
    }
}
