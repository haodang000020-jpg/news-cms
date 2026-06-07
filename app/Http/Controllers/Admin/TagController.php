<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::latest()
            ->paginate(20);

        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        Tag::create([
            'name' => $request->name,
            'slug' => $this->uniqueSlug($request->name),
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Thêm tag thành công');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $tag->update([
            'name' => $request->name,
            'slug' => $this->uniqueSlug($request->name, $tag->id),
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Cập nhật tag thành công');
    }

    public function destroy(Tag $tag)
    {
        $tag->posts()->detach();

        $tag->delete();

        return redirect()
            ->route('tags.index')
            ->with('success', 'Đã xóa tag');
    }

    private function uniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (
            Tag::where('slug', $slug)
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}