<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'requester_id',
        'type',
        'title',
        'message',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }


    /* SCOPES */
    public function scopeUnread($query)
    {
        return $query->where('status', true);
    }

    /* HELPERS */
    public function markAsRead()
    {
        $this->update(['status' => false]);
    }
}
