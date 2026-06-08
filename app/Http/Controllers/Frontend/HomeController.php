<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Post;
use App\Models\Video;
use App\Models\WebsiteLink;
use App\Models\WorkSchedule;

class HomeController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Tin nổi bật
        |--------------------------------------------------------------------------
        */
$workSchedules = WorkSchedule::where('status', true)
    ->whereDate('work_date', '>=', now()->startOfWeek())
    ->whereDate('work_date', '<=', now()->endOfWeek())
    ->orderBy('work_date')
    ->orderBy('start_time')
    ->orderBy('sort_order')
    ->take(10)
    ->get();
        $featuredPosts = Post::with(['categories', 'author'])
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('featured_order')
            ->latest('published_at')
            ->take(5)
            ->get();

        if ($featuredPosts->count() === 0) {
            $featuredPosts = Post::with(['categories', 'author'])
                ->where('status', 'published')
                ->latest('published_at')
                ->take(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Tin mới nhất
        |--------------------------------------------------------------------------
        */

        $latestPosts = Post::with(['categories', 'author'])
            ->where('status', 'published')
            ->latest('published_at')
            ->take(12)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Danh mục trang chủ
        |--------------------------------------------------------------------------
        */

        $leftCategories = Category::with('children')
            ->whereNull('parent_id')
            ->where('status', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Văn bản / Thông báo
        |--------------------------------------------------------------------------
        */

        $documents = Post::with(['categories', 'author'])
            ->where('status', 'published')
            ->whereHas('categories', function ($q) {
                $q->where('name', 'like', '%Văn bản%')
                    ->orWhere('name', 'like', '%Thông báo%')
                    ->orWhere('name', 'like', '%Chỉ đạo%')
                    ->orWhere('name', 'like', '%Điều hành%');
            })
            ->latest('published_at')
            ->take(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Banner trang chủ
        |--------------------------------------------------------------------------
        */

        $homeBanners = Banner::where('status', true)
            ->where('position', 'home_after_featured')
            ->orderBy('sort_order')
            ->get();

        $sidebarBanners = Banner::where('status', true)
            ->where('position', 'sidebar_links')
            ->orderBy('sort_order')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Video trang chủ
        |--------------------------------------------------------------------------
        */

        $homeVideo = Video::where('status', true)
            ->orderBy('sort_order')
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Liên kết website
        |--------------------------------------------------------------------------
        */

        $websiteLinks = WebsiteLink::where('status', true)
            ->orderBy('sort_order')
            ->get();

        return view('frontend.home', compact(
            'featuredPosts',
            'latestPosts',
            'leftCategories',
            'documents',
            'homeBanners',
            'sidebarBanners',
            'homeVideo',
            'websiteLinks',
            'workSchedules'
        ));
    }
}