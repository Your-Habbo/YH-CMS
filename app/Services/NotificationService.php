<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function create($notifiable, $type, $data)
    {
        return Notification::create([
            'type' => $type,
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
            'data' => $data,
        ]);
    }

    public function markAsRead($notification)
    {
        $notification->markAsRead();
    }

    public function markAllAsRead($user)
    {
        $user->unreadNotifications()->update(['read_at' => now()]);
    }
}