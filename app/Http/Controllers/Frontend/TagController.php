<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;

class TagController extends Controller
{
    public function show($slug)
    {
        $tag = Tag::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $posts = Post::with(['author', 'categories', 'tags'])
            ->where('status', 'published')
            ->whereHas('tags', function ($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->latest('published_at')
            ->paginate(9);

        return view('frontend.tags.show', compact(
            'tag',
            'posts'
        ));
    }
}