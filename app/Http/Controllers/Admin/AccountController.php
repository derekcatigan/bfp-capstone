<?php

namespace App\Http\Controllers\Admin;

use App\Enum\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Services\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('profile');

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($q2) use ($search) {
                        $q2->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by position
        if ($request->filled('position')) {
            $position = $request->position;
            $query->whereHas('profile', function ($q) use ($position) {
                $q->where('position', $position);
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('pages.admin.account-list', compact('users'));
    }


    public function create()
    {
        return view('pages.admin.account-create');
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'lastname' => 'required|string',
            'suffix' => 'nullable|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'role' => ['required', 'string'],
            'password' => 'required|min:8|confirmed',
            'status' => 'required|string',
            'driver_code' => 'nullable|string|unique:profiles,driver_code',
            'license' => 'nullable|string|unique:profiles,license',
            'department' => 'nullable|string',
        ]);

        // Prevent duplicate fullname
        $exists = Profile::where('first_name', $validated['firstname'])
            ->where('last_name', $validated['lastname'])
            ->where('suffix', $validated['suffix'] ?? null)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'A user with the same full name already exists.'
            ], 422);
        }

        // Driver-specific logic
        if ($validated['role'] === RoleEnum::DriverRole->value) {
            // Generate Driver Code
            $lastDriver = Profile::whereNotNull('driver_code')
                ->orderByDesc('id')
                ->first();

            $nextNumber = 1001;
            if ($lastDriver && $lastDriver->driver_code) {
                $lastNumber = (int) str_replace('DRV-', '', $lastDriver->driver_code);
                $nextNumber = $lastNumber + 1;
            }

            $driverCode = 'DRV-' . $nextNumber;

            // License is required for drivers
            if (empty($validated['license'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'License is required for driver accounts.'
                ], 422);
            }

            $driverLicense = $validated['license'];
        } else {
            // Non-drivers: clear driver fields
            $validated['driver_code'] = null;
            $validated['license'] = null;
            $validated['department'] = null;
        }

        try {
            DB::beginTransaction();

            // Auto-generate employee code
            $lastUser = User::whereNotNull('employee_code')
                ->orderByDesc('employee_code')
                ->first();
            $employeeNumber = 1001;
            if ($lastUser && $lastUser->employee_code) {
                $lastNumber = (int) str_replace('EMP-', '', $lastUser->employee_code);
                $employeeNumber = $lastNumber + 1;
            }
            $employeeCode = 'EMP-' . $employeeNumber;

            // Set default department
            if (empty($validated['department'])) {
                $validated['department'] = 'Fire Operations';
            }

            $roleEnum = RoleEnum::from($validated['role']);

            // Create user
            $user = User::create([
                'employee_code' => $employeeCode,
                'username' => Str::title($validated['username']),
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role' => $validated['role'],
                'status' => $validated['status'],
                'password' => $validated['password'],
            ]);

            // Create profile
            $user->profile()->create([
                'first_name' => Str::title($validated['firstname']),
                'middle_name' => $validated['middlename']
                    ? Str::title($validated['middlename'])
                    : null,
                'last_name' => Str::title($validated['lastname']),
                'suffix' => $validated['suffix'] ?? null,
                'driver_code' => $driverCode ?? null,
                'license' =>  $driverLicense ?? null,
                'department' => $validated['department'] ?? null,
                'position' =>  $roleEnum->position(),
            ]);

            DB::commit();

            SystemNotification::notifyAdmins(
                'New User Created',
                "{$user->profile->first_name} {$user->profile->last_name} has been registered.",
                'account_created'
            );

            return response()->json([
                'status' => 'success',
                'message' => 'User account created successfully.',
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('User creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while creating the account. Please try again.',
            ], 500);
        }
    }

    public function show(User $user)
    {
        $user->load('profile');

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role->value,
            'first_name' => $user->profile->first_name,
            'last_name' => $user->profile->last_name,
            'department' => $user->profile->department,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $roleEnum = RoleEnum::from($request->role);

        DB::transaction(function () use ($request, $user, $roleEnum) {

            $user->update([
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => $request->role,
            ]);

            $user->profile->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'department' => $request->department,
                'position' => $roleEnum->position(),
            ]);
        });

        return response()->json(['success' => true]);
    }

    public function destroy(User $user)
    {
        try {
            $name = $user->profile->first_name . ' ' . $user->profile->last_name;

            $user->profile()->delete();
            $user->delete();

            SystemNotification::notifyAdmins(
                'User Deleted',
                "{$name} account has been removed.",
                'account_deleted'
            );

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully.'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete user.'
            ], 500);
        }
    }
}
