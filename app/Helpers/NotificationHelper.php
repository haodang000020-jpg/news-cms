<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    public static function sendToUser(
        int $userId,
        string $title,
        string $message = null,
        string $link = null
    ) {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => false,
        ]);
    }

    public static function sendToUsersByPermission(
        string $permissionCode,
        string $title,
        string $message = null,
        string $link = null
    ) {
        $users = User::whereHas('role.permissions', function ($query) use ($permissionCode) {
                $query->where('code', $permissionCode);
            })
            ->get();

        foreach ($users as $user) {
            self::sendToUser(
                $user->id,
                $title,
                $message,
                $link
            );
        }
    }
}