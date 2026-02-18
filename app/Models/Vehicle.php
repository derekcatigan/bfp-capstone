<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'plate_number',
        'vehicle_type',
        'make',
        'model',
        'year',
        'color',
        'engine_number',
        'chassis_number',
        'fuel_type',
        'fuel_tank_capacity',
        'current_fuel_level',
        'status',
        'image',
        'description',
    ];

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function fuelPercentage(): float
    {
        if (!$this->fuel_tank_capacity || !$this->current_fuel_level) {
            return 0;
        }

        return ($this->current_fuel_level / $this->fuel_tank_capacity) * 100;
    }

    public function isAvailable(): bool
    {
        return $this->status === 'Available';
    }

    public function tripTickets()
    {
        return $this->hasMany(TripTicket::class, 'vehicle_id');
    }
}
