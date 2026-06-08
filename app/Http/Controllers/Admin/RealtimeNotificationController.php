<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;

class RealtimeNotificationController extends Controller
{
    public function check()
    {
        $user = auth()->user();

        $pendingPostsCount = 0;

        if ($user->hasPermission('post.publish')) {
            $pendingPostsCount = Post::where('status', 'pending')->count();
        }

        return response()->json([
            'pending_posts_count' => $pendingPostsCount,
            'total_count' => $pendingPostsCount,
            'message' => $pendingPostsCount > 0
                ? 'Có ' . $pendingPostsCount . ' bài viết đang chờ duyệt'
                : 'Không có thông báo mới',
        ]);
    }
}