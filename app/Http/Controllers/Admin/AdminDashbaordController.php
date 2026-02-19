<?php

namespace App\Http\Controllers\Admin;

use App\Enum\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\FuelStorage;
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
            'activeTrips' => TripTicket::where('status', 'active')->count(),
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

        // Fuel Summary
        $fuelSummary = FuelStorage::selectRaw("transaction_type, SUM(amount) as total")
            ->groupBy('transaction_type')
            ->pluck('total', 'transaction_type');

        $fuelTypeLabels = ['Added', 'Removed'];
        $fuelTypeValues = [
            $fuelSummary['added'] ?? 0,
            $fuelSummary['removed'] ?? 0,
        ];

        // Monthly Fuel Movement
        $fuelMonthly = FuelStorage::selectRaw("
        MONTH(transaction_datetime) as month,
        SUM(CASE WHEN transaction_type = 'added' THEN amount ELSE 0 END) as added,
        SUM(CASE WHEN transaction_type = 'removed' THEN amount ELSE 0 END) as removed
    ")
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $fuelLineLabels = [];
        $fuelAddedValues = [];
        $fuelRemovedValues = [];

        for ($i = 1; $i <= 12; $i++) {
            $fuelLineLabels[] = Carbon::create(null, $i)->format('M');
            $fuelAddedValues[] = $fuelMonthly[$i]->added ?? 0;
            $fuelRemovedValues[] = $fuelMonthly[$i]->removed ?? 0;
        }



        return view('pages.admin.admin-dashbaord', compact(
            'user',
            'stats',
            'chartLabels',
            'chartValues',
            'lineLabels',
            'lineValues',
            'month',
            'year',
            'fuelTypeLabels',
            'fuelTypeValues',
            'fuelLineLabels',
            'fuelAddedValues',
            'fuelRemovedValues',
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

        $fuelSummaryQuery = FuelStorage::query();

        if ($month) $fuelSummaryQuery->whereMonth('transaction_datetime', $month);
        if ($year) $fuelSummaryQuery->whereYear('transaction_datetime', $year);

        $fuelSummary = $fuelSummaryQuery
            ->selectRaw("transaction_type, SUM(amount) as total")
            ->groupBy('transaction_type')
            ->pluck('total', 'transaction_type');

        $fuelTypeValues = [
            $fuelSummary['added'] ?? 0,
            $fuelSummary['removed'] ?? 0,
        ];

        $fuelMonthlyQuery = FuelStorage::query();

        if ($month) $fuelMonthlyQuery->whereMonth('transaction_datetime', $month);
        if ($year) $fuelMonthlyQuery->whereYear('transaction_datetime', $year);

        $fuelMonthly = $fuelMonthlyQuery
            ->selectRaw("
        MONTH(transaction_datetime) as month,
        SUM(CASE WHEN transaction_type = 'added' THEN amount ELSE 0 END) as added,
        SUM(CASE WHEN transaction_type = 'removed' THEN amount ELSE 0 END) as removed
    ")
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $fuelLineLabels = [];
        $fuelAddedValues = [];
        $fuelRemovedValues = [];

        for ($i = 1; $i <= 12; $i++) {
            $fuelLineLabels[] = Carbon::create(null, $i)->format('M');
            $fuelAddedValues[] = $fuelMonthly[$i]->added ?? 0;
            $fuelRemovedValues[] = $fuelMonthly[$i]->removed ?? 0;
        }



        $labels = [];
        $values = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create(null, $i)->format('M');
            $values[] = $monthlyExpenses[$i] ?? 0;
        }

        return response()->json([
            'expense' => [
                'labels' => $labels,
                'values' => $values,
            ],
            'fuelSummary' => [
                'values' => $fuelTypeValues,
            ],
            'fuelMonthly' => [
                'labels' => $fuelLineLabels,
                'added' => $fuelAddedValues,
                'removed' => $fuelRemovedValues,
            ],
        ]);
    }
}
