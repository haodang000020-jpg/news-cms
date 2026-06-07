<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
{
    $category = Category::with('children')
        ->where('slug', $slug)
        ->where('status', true)
        ->firstOrFail();

    $categoryIds = collect([$category->id])
        ->merge($category->children->pluck('id'))
        ->toArray();

    $posts = \App\Models\Post::with(['author', 'categories'])
        ->where('status', 'published')
        ->whereHas('categories', function ($query) use ($categoryIds) {
            $query->whereIn('categories.id', $categoryIds);
        })
        ->latest('published_at')
        ->paginate(9);

    return view('frontend.categories.show', compact(
        'category',
        'posts'
    ));
}
}
