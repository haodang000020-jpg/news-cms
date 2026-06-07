<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->get();

        $categories = Category::where('status', true)
            ->latest()
            ->get();

        $pages = Page::where('status', true)
            ->latest()
            ->get();

        return response()
            ->view('frontend.sitemap', compact(
                'posts',
                'categories',
                'pages'
            ))
            ->header('Content-Type', 'text/xml');
    }
}