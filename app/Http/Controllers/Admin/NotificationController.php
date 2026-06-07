<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function read(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update([
            'is_read' => true,
        ]);

        return redirect($notification->link ?: route('notifications.index'));
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);

        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }
    public function unreadData()
{
    $unreadCount = Notification::where('user_id', auth()->id())
        ->where('is_read', false)
        ->count();

    $latestNotifications = Notification::where('user_id', auth()->id())
        ->latest()
        ->take(5)
        ->get()
        ->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'is_read' => $notification->is_read,
                'created_at' => $notification->created_at?->format('d/m/Y H:i'),
                'read_url' => route('notifications.read', $notification->id),
            ];
        });

    return response()->json([
        'unread_count' => $unreadCount,
        'notifications' => $latestNotifications,
    ]);
}
}