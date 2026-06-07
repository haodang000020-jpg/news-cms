<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPosts = Post::count();

        $publishedPosts = Post::where('status', 'published')
            ->count();

        $pendingPosts = Post::where('status', 'pending')
            ->count();

        $draftPosts = Post::where('status', 'draft')
            ->count();

        $trashPosts = Post::onlyTrashed()
            ->count();

        $totalCategories = Category::count();

        $totalUsers = User::count();

        $totalViews = Post::sum('views');

        $latestPosts = Post::with('author')
            ->latest()
            ->take(6)
            ->get();

        $mostViewedPosts = Post::with('author')
            ->where('status', 'published')
            ->orderByDesc('views')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalPosts',
            'publishedPosts',
            'pendingPosts',
            'draftPosts',
            'trashPosts',
            'totalCategories',
            'totalUsers',
            'totalViews',
            'latestPosts',
            'mostViewedPosts'
        ));
    }
}