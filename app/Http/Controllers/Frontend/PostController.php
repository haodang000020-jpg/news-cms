<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Banner;

class PostController extends Controller
{
  public function show($slug)
{
    $post = Post::with(['author', 'categories', 'tags', 'attachments'])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

    $post->increment('views');

    $latestPosts = Post::where('status', 'published')
        ->where('id', '!=', $post->id)
        ->latest('published_at')
        ->take(6)
        ->get();

    $categoryIds = $post->categories->pluck('id')->toArray();
    $tagIds = $post->tags->pluck('id')->toArray();

    $relatedPosts = Post::with(['categories', 'tags'])
        ->where('status', 'published')
        ->where('id', '!=', $post->id)
        ->where(function ($query) use ($categoryIds, $tagIds) {
            if (!empty($categoryIds)) {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }

            if (!empty($tagIds)) {
                $query->orWhereHas('tags', function ($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
                });
            }
        })
        ->latest('published_at')
        ->take(6)
        ->get();

    if ($relatedPosts->count() === 0) {
        $relatedPosts = Post::with(['categories', 'tags'])
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(6)
            ->get();
    }
$sidebarBanners = Banner::where('status', true)
    ->where('position', 'sidebar_links')
    ->orderBy('sort_order')
    ->get();
    return view('frontend.posts.show', compact(
        'post',
        'latestPosts',
        'relatedPosts',
        'sidebarBanners'
    ));
}
}
