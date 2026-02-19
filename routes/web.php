<?php

use App\Enum\RoleEnum;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ActiveTripController;
use App\Http\Controllers\Admin\AdminDashbaordController;
use App\Http\Controllers\Admin\DriverLocationController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Analytics\VehicleExpenseAnalyticsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Driver\DriverDashboardController;
use App\Http\Controllers\Shared\FuelStorageController;
use App\Http\Controllers\Shared\ManageRepairController;
use App\Http\Controllers\Shared\NotificationController;
use App\Http\Controllers\Shared\ProfileController;
use App\Http\Controllers\Shared\RequestTicketController;
use App\Http\Controllers\Shared\RequestVehicleController;
use App\Http\Controllers\Shared\VehicleController;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // AuthController
    Route::controller(AuthController::class)->group(function () {
        Route::get('/', 'index')->name('login');
        Route::post('/login', 'authenticate')->name('authenticate');
    });
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function (Request $request) {
        return match (Auth::user()->role) {
            RoleEnum::AdminRole => redirect()->route('admin.dashboard'),
            RoleEnum::DriverRole => redirect()->route('driver.dashboard'),
            default => redirect()->route('driver.dashboard'),
        };
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Force Logout
    Route::get('/force-logout', function (Request $request) {
        Auth::logout();

        $request->session()->regenerateToken();
        $request->session()->invalidate();

        return redirect()->route('login');
    })->name('force.logout');

    // Admin Role Route Access
    Route::middleware('role:admin')->group(function () {
        // AdminDashboardController 
        Route::controller(AdminDashbaordController::class)->group(function () {
            Route::get('/admin/dashboard', 'index')->name('admin.dashboard');
            Route::get('/admin/dashboard/chart', 'chartData')->name('admin.dashboard.chart');
        });

        // AccountController
        Route::controller(AccountController::class)->group(function () {
            Route::get('/account/list', 'index')->name('account.index');
            Route::get('/account/create', 'create')->name('account.create');
            Route::post('/account/store', 'store')->name('account.store');
            Route::get('/account/{user}', 'show')->name('account.show');
            Route::put('/account/{user}', 'update')->name('account.update');
            Route::delete('/account/{user}', 'destroy')->name('account.destroy');
        });

        // TicketController
        Route::controller(TicketController::class)->group(function () {
            Route::get('/ticket/issue-ticket', 'create')->name('ticket.create');
            Route::post('/ticket/store', 'store')->name('ticket.store');
            Route::delete('/tickets/{ticket}/delete', 'destroy')->name('ticket.destroy');
        });

        // Approve Request Trip Ticket
        Route::post('/ticket/request/{notification}/approve', function (Notification $notification) {

            abort_unless($notification->type === 'trip_ticket_request', 403);

            // Mark admin notification as read
            $notification->update([
                'status' => false,
                'type' => 'trip_ticket_approved',
            ]);

            // Notify the requester
            Notification::create([
                'user_id' => $notification->requester_id,
                'type' => 'trip_ticket_approved',
                'title' => 'Trip Ticket Approved',
                'message' => 'Your trip ticket request has been approved by the admin.',
                'status' => true,
            ]);

            return redirect()->route('ticket.create');
        })->name('ticket.request.approve');

        // Reject Request Trip Ticket
        Route::post('/ticket/request/{notification}/reject', function (Notification $notification) {

            abort_unless($notification->type === 'trip_ticket_request', 403);

            // Mark admin notification as processed
            $notification->update([
                'status' => false,
                'type' => 'trip_ticket_rejected',
            ]);

            // Notify the requester
            Notification::create([
                'user_id' => $notification->requester_id,
                'type' => 'trip_ticket_rejected',
                'title' => 'Trip Ticket Rejected',
                'message' => 'Your trip ticket request has been rejected by the admin.',
                'status' => true,
            ]);

            return back();
        })->name('ticket.request.reject');

        // Approve vehicle repair
        Route::post('/vehicle/request/{notification}/approve', function (Notification $notification) {

            abort_unless($notification->type === 'vehicle_repair_request', 403);

            // Mark admin notification as processed
            $notification->update([
                'status' => false,
                'type' => 'vehicle_repair_approved',
            ]);

            // Notify DRIVER
            Notification::create([
                'user_id' => $notification->requester_id,
                'type' => 'vehicle_repair_approved',
                'title' => 'Vehicle Repair Approved',
                'message' => 'Your vehicle repair request has been approved.',
                'status' => true,
            ]);

            return back();
        })->name('vehicle.request.approve');

        // Reject vehicle repair
        Route::post('/vehicle/request/{notification}/reject', function (Notification $notification) {

            abort_unless($notification->type === 'vehicle_repair_request', 403);

            $notification->update([
                'status' => false,
                'type' => 'vehicle_repair_rejected',
            ]);

            Notification::create([
                'user_id' => $notification->requester_id,
                'type' => 'vehicle_repair_rejected',
                'title' => 'Vehicle Repair Rejected',
                'message' => 'Your vehicle repair request has been rejected.',
                'status' => true,
            ]);

            return back();
        })->name('vehicle.request.reject');

        // RequestTicketController
        Route::controller(RequestTicketController::class)->group(function () {
            Route::get('/ticket/manage-ticket', 'ticketIndex')->name('request.ticket.index');
        });
    });

    // Driver Role Route Access
    Route::middleware(['role:driver'])->group(function () {
        // DriverDashboardController 
        Route::controller(DriverDashboardController::class)->group(function () {
            Route::get('/driver/dashboard', 'index')->name('driver.dashboard');
        });

        // RequestTicketController
        Route::controller(RequestTicketController::class)->group(function () {
            Route::get('/ticket/request-ticket', 'index')->name('ticket.request.index');
            Route::post('/ticket/request-trip', 'store')->name('ticket.request.store');
        });

        // RequestVehicleController
        Route::controller(RequestVehicleController::class)->group(function () {
            Route::get('/vehicle/request-repair', 'index')->name('request.vehicle.index');
            Route::post('/vehicle/request-repair/store', 'store')->name('request.vehicle.store');
            Route::get('/vehicles/available', 'availableVehicles')->name('vehicles.available');
        });
    });

    // Admin and Driver Role Access
    Route::middleware(['role:admin,driver'])->group(function () {
        // ActiveTripController
        Route::controller(ActiveTripController::class)->group(function () {
            Route::get('/trips', 'index')->name('trip.index');
            Route::get('/admin/active-trip/{trip}/location', 'location')->name('active-trip.location');
        });

        // RequestVehicleController
        Route::controller(RequestVehicleController::class)->group(function () {
            Route::get('/vehicle/manage-repair', 'repairIndex')->name('manage.vehicle.index');
        });

        // VehicleController
        Route::controller(VehicleController::class)->group(function () {
            Route::get('/vehicle/manage-vehicle', 'index')->name('vehicle.index');
            Route::get('/vehicle/register', 'create')->name('vehicle.create');
            Route::post('/vehicle/store', 'store')->name('vehicle.store');
            Route::get('/vehicle/{vehicle}', 'show')->name('vehicle.show');
            Route::get('vehicle/{vehicle}/edit', 'edit')->name('vehicle.edit');
            Route::put('/vehicle/{vehicle}/update', 'update')->name('vehicle.update');
        });

        // TicketController
        Route::controller(TicketController::class)->group(function () {
            Route::get('/ticket', 'index')->name('ticket.index');
            Route::patch('/ticket/{ticket}/activate', 'activate')->name('ticket.activate');
            Route::get('/tickets/{ticket}', 'show')->name('ticket.show');
            Route::get('/ticket/{ticket}/edit', 'edit')->name('ticket.edit');
            Route::put('/ticket/{ticket}/update', 'update')->name('ticket.update');
            Route::patch('/ticket/{ticket}/submit', 'submit')->name('ticket.submit');
        });

        // DriverLocationController
        Route::get('/driver/{trip}/location', [DriverLocationController::class, 'index'])
            ->name('driver.location.index');
        Route::post('/driver/{trip}/toggle-tracking', [DriverLocationController::class, 'toggleTracking'])
            ->name('driver.toggle-tracking');
        Route::post('/driver/{trip}/update-location', [DriverLocationController::class, 'updateLocation'])
            ->name('driver.update-location');

        // ManageRepairController
        Route::controller(ManageRepairController::class)->group(function () {
            Route::get('/repair/vehicle', 'index')->name('manage.repair.vehicle');
            Route::post('/repair/vehicle/store', 'store')->name('manage.repair.store');
        });

        // VehicleExpenseAnalyticsController
        Route::controller(VehicleExpenseAnalyticsController::class)->group(function () {
            Route::get('/vehicle-analytics', 'index')->name('analytics.vehicle.index');
        });

        // FuelStorageController
        Route::controller(FuelStorageController::class)->group(function () {
            Route::get('/fuel-storage', 'index')->name('fuel.storage.index');
            Route::get('/fuel-storage/list', 'list');
            Route::post('/fuel-storage/store', 'store')->name('fuel.storage.store');
        });
    });

    // All Role Access
    Route::middleware(['role:admin,user,driver'])->group(function () {
        // ProfileController
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'index')->name('profile.index');
            Route::put('/profile/update',  'update');
            Route::put('/profile/password', 'changePassword');
        });

        // Notification
        Route::get('/notifications/{notification}/read', function (Notification $notification) {
            abort_unless($notification->user_id === Auth::id(), 403);

            $notification->markAsRead();

            return back();
        })->name('notifications.read');

        // NotificationController
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notifications', 'index')->name('notification.index');
        });

        // Document Trip Ticket
        Route::get('/document/ticket', function () {
            return view('print.document-ticket');
        })->name('document.ticket');
    });
});
