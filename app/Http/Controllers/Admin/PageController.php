<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()
            ->paginate(10);

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'summary' => 'nullable',
            'content' => 'nullable',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'seo_title' => 'nullable|max:255',
            'seo_description' => 'nullable',
        ]);

        $imagePath = null;

        if ($request->hasFile('featured_image')) {
            $imagePath = $request
                ->file('featured_image')
                ->store('pages', 'public');
        }

        Page::create([
            'title' => $request->title,
            'slug' => $this->uniqueSlug($request->title),
            'summary' => $request->summary,
            'content' => $request->content,
            'featured_image' => $imagePath,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('pages.index')
            ->with('success', 'Thêm trang thành công');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|max:255',
            'summary' => 'nullable',
            'content' => 'nullable',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'seo_title' => 'nullable|max:255',
            'seo_description' => 'nullable',
        ]);

        $imagePath = $page->featured_image;

        if ($request->hasFile('featured_image')) {
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }

            $imagePath = $request
                ->file('featured_image')
                ->store('pages', 'public');
        }

        $page->update([
            'title' => $request->title,
            'slug' => $this->uniqueSlug($request->title, $page->id),
            'summary' => $request->summary,
            'content' => $request->content,
            'featured_image' => $imagePath,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('pages.index')
            ->with('success', 'Cập nhật trang thành công');
    }

    public function destroy(Page $page)
    {
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }

        $page->delete();

        return redirect()
            ->route('pages.index')
            ->with('success', 'Đã xóa trang');
    }

    private function uniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (
            Page::where('slug', $slug)
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