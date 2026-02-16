<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class SystemNotification
{
    public static function notifyAdmins(string $title, string $message, string $type = 'system')
    {
        $admins = User::where('role', 'admin')->pluck('id');

        foreach ($admins as $adminId) {
            Notification::create([
                'user_id' => $adminId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'status' => true,
            ]);
        }
    }

    public static function notifyUser(string $userId, string $title, string $message, string $type = 'system')
    {
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'status' => true,
        ]);
    }
}
