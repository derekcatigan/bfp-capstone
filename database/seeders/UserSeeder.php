<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Account
        $accountAdmin = User::create([
            'employee_code' => 'EMP-1001',
            'username' => 'Admin',
            'phone' => '09000000001',
            'email' => 'admin@email.com',
            'role' => RoleEnum::AdminRole,
            'password' => 'password',
        ]);

        $accountAdmin->profile()->create([
            'first_name' => 'Admin',
            'last_name' => 'Sample',
            'driver_code' => '',
            'department' => 'Fire Operations',
            'position' => 'Administrator',
        ]);

        // Driver Account
        $accountUser = User::create([
            'employee_code' => 'EMP-1003',
            'username' => 'Driver',
            'phone' => '09000000003',
            'email' => 'driver@email.com',
            'role' => RoleEnum::DriverRole,
            'password' => 'password',
        ]);

        $accountUser->profile()->create([
            'first_name' => 'Driver',
            'last_name' => 'Sample',
            'driver_code' => 'DRV-1001',
            'department' => 'Fire Operations',
            'position' => 'Driver',
        ]);
    }
}
