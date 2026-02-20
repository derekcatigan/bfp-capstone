<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripTracking extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'trip_tracking';

    protected $primaryKey = 'trip_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'trip_id',
        'is_tracking',
        'started_at',
        'stopped_at',
        'current_latitude',
        'current_longitude',
        'last_ping_at'
    ];

    protected $casts = [
        'is_tracking' => 'boolean',
        'started_at' => 'datetime',
        'stopped_at' => 'datetime',
        'last_ping_at' => 'datetime',
    ];

    /* ---------------- RELATIONSHIPS ---------------- */

    public function trip()
    {
        return $this->belongsTo(TripTicket::class, 'trip_id');
    }
}
