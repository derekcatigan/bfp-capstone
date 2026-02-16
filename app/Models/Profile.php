<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'driver_code',
        'department',
        'license',
        'position',
    ];

    public function badgeClass(): string
    {
        return match ($this->position) {
            'Administrator' => 'badge-error text-red-700 font-semibold',
            'Driver' => 'badge-primary text-blue-300 font-semibold',
            'User' => 'badge-primary text-blue-300 font-semibold',
            default => 'badge',
        };
    }

    // RELATIONSHIPS

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
