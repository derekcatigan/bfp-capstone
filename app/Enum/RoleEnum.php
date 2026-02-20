<?php

namespace App\Enum;

enum RoleEnum: string
{
    case AdminRole = 'admin';
    case DriverRole = 'driver';

    public function label(): string
    {
        return match ($this) {
            self::AdminRole => 'Admin',
            self::DriverRole => 'Driver',
        };
    }

    public function position(): string
    {
        return match ($this) {
            self::AdminRole => 'Administrator',
            self::DriverRole => 'Driver',
        };
    }
}
