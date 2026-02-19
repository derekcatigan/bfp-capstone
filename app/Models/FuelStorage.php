<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelStorage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'transaction_datetime',
        'container_type',
        'transaction_type',
        'amount',
        'running_balance',
        'note',
    ];

    protected $casts = [
        'transaction_datetime' => 'datetime',
        'amount' => 'decimal:2',
        'running_balance' => 'decimal:2',
    ];

    /* -------------------------------------------------
    | Helper Methods
    |--------------------------------------------------*/

    // Check if fuel was added
    public function isAddition(): bool
    {
        return $this->transaction_type === 'added';
    }

    // Check if fuel was removed
    public function isRemoval(): bool
    {
        return $this->transaction_type === 'removed';
    }

    /* -------------------------------------------------
    | Scopes (useful for reports)
    |--------------------------------------------------*/

    public function scopeAdded($query)
    {
        return $query->where('transaction_type', 'added');
    }

    public function scopeRemoved($query)
    {
        return $query->where('transaction_type', 'removed');
    }

    public function scopeForContainer($query, string $container)
    {
        return $query->where('container_type', $container);
    }

    /* -------------------------------------------------
    | Static Utilities
    |--------------------------------------------------*/

    // Get latest fuel balance
    public static function currentBalance(?string $container = null): float
    {
        $query = static::query();

        if ($container !== null) {
            $query->where('container_type', $container);
        }

        return (float) ($query->latest('transaction_datetime')->value('running_balance') ?? 0);
    }
}
