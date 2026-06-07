<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('position')
            ->orderBy('sort_order')
            ->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'link' => 'nullable|max:500',
            'position' => 'required|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        $imagePath = $request
            ->file('image')
            ->store('banners', 'public');
            $uploadedFile = $request->file('image');

Media::create([
    'uploader_id' => Auth::id(),
    'file_name' => $uploadedFile->getClientOriginalName(),
    'file_path' => $imagePath,
    'mime_type' => $uploadedFile->getMimeType(),
    'file_size' => $uploadedFile->getSize(),
]);

        Banner::create([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
            'position' => $request->position,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('banners.index')
            ->with('success', 'Thêm banner thành công');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'link' => 'nullable|max:500',
            'position' => 'required|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        $imagePath = $banner->image;

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }

            $imagePath = $request
                ->file('image')
                ->store('banners', 'public');
        }

        $banner->update([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
            'position' => $request->position,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('banners.index')
            ->with('success', 'Cập nhật banner thành công');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()
            ->route('banners.index')
            ->with('success', 'Đã xóa banner');
    }
}