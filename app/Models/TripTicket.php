<?php

namespace App\Models;

use App\Enum\RoleEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripTicket extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'control_no',
        'ticket_date',
        'driver_id',
        'vehicle_id',
        'authorized_passenger',
        'place',
        'latitude',
        'longitude',
        'purpose',
        'time_departed_garage',
        'time_arrival_destination',
        'time_departure_destination',
        'time_arrival_garage',
        'approx_distance',
        'balance_tank',
        'issued_stock',
        'purchased_trip',
        'deduct_trip',
        'gear_oil_issued',
        'lub_oil_issued',
        'grease_issued',
        'speedometer_start',
        'speedometer_end',
        'remarks',
        'passenger_name1',
        'passenger_date1',
        'passenger_name2',
        'passenger_date2',
        'passenger_name3',
        'passenger_date3',
        'status'
    ];

    // RELATIONSHIPS
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id')
            ->where('role', RoleEnum::DriverRole);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
