<?php

namespace App\Http\Controllers\Admin;

use App\Enum\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\TripTicket;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleExpense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashbaordController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user()->load('profile');

        $stats = [
            'totalUsers' => User::count(),
            'totalDrivers' => User::where('role', RoleEnum::DriverRole)->where('status', 'active')->count(),
            'availableVehicles' => Vehicle::where('status', 'Available')->count(),
            'repairVehicles' => Vehicle::where('status', 'In Repair')->count(),
            'pendingTrips' => TripTicket::where('status', 'pending')->count(),
            'activeTrips' => TripTicket::where('status', 'ongoing')->count(),
        ];

        $month = $request->query('month');
        $year = $request->query('year');

        // Aggregate monthly expenses for line chart
        $expensesQuery = VehicleExpense::query();
        if ($month) $expensesQuery->whereMonth('expense_date', $month);
        if ($year) $expensesQuery->whereYear('expense_date', $year);

        $monthlyExpenses = $expensesQuery
            ->selectRaw('MONTH(expense_date) as month, SUM(total_cost) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $lineLabels = [];
        $lineValues = [];
        for ($i = 1; $i <= 12; $i++) {
            $lineLabels[] = Carbon::create(null, $i)->format('M');
            $lineValues[] = $monthlyExpenses[$i] ?? 0;
        }

        // Expense by type (bar chart)
        $expenseData = VehicleExpense::selectRaw('type, SUM(total_cost) as total')->groupBy('type')->pluck('total', 'type');
        $chartLabels = $expenseData->keys();
        $chartValues = $expenseData->values();

        return view('pages.admin.admin-dashbaord', compact(
            'user',
            'stats',
            'chartLabels',
            'chartValues',
            'lineLabels',
            'lineValues',
            'month',
            'year'
        ));
    }

    public function chartData(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        $expensesQuery = VehicleExpense::query();
        if ($month) $expensesQuery->whereMonth('expense_date', $month);
        if ($year) $expensesQuery->whereYear('expense_date', $year);

        $monthlyExpenses = $expensesQuery
            ->selectRaw('MONTH(expense_date) as month, SUM(total_cost) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $labels = [];
        $values = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create(null, $i)->format('M');
            $values[] = $monthlyExpenses[$i] ?? 0;
        }

        return response()->json(['labels' => $labels, 'values' => $values]);
    }
}
