<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleExpense extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'vehicle_id',
        'type',
        'quantity',
        'unit_price',
        'total_cost',
        'description',
        'expense_date',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    // RELATIONSHIPS

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
