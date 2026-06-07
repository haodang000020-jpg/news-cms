<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->q);

        $posts = Post::with(['author', 'categories'])
            ->where('status', 'published')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', '%' . $keyword . '%')
                        ->orWhere('summary', 'like', '%' . $keyword . '%')
                        ->orWhere('content', 'like', '%' . $keyword . '%');
                });
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('frontend.search', compact(
            'posts',
            'keyword'
        ));
    }
}
