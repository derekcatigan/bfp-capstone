<?php

namespace App\Enum;

enum RoleEnum: string
{
    case AdminRole = 'admin';
        // case UserRole = 'user';
    case DriverRole = 'driver';

    public function label(): string
    {
        return match ($this) {
            self::AdminRole => 'Admin',
            // self::UserRole => 'User',
            self::DriverRole => 'Driver',
        };
    }

    public function position(): string
    {
        return match ($this) {
            self::AdminRole => 'Administrator',
            // self::UserRole => 'User',
            self::DriverRole => 'Driver',
        };
    }
}
