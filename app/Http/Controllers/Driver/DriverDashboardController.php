<?php

namespace App\Http\Controllers\Driver;

use App\Enum\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\TripTicket;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('profile');

        $stats = [
            'totalUsers' => User::count(),

            'totalDrivers' => User::where('role', RoleEnum::DriverRole)
                ->where('status', 'active')
                ->count(),

            'availableVehicles' => Vehicle::where('status', 'Available')->count(),

            'repairVehicles' => Vehicle::where('status', 'In Repair')->count(),

            'pendingTrips' => TripTicket::where('status', 'pending')->count(),

            'activeTrips' => TripTicket::where('status', 'ongoing')->count(),
        ];
        return view('pages.driver.driver-dashboard', compact('user', 'stats'));
    }
}
