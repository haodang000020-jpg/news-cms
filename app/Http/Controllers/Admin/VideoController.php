<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::orderBy('sort_order')
            ->latest()
            ->paginate(15);

        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'url' => 'required|max:500',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request
                ->file('thumbnail')
                ->store('videos', 'public');
        }

        $video = Video::create([
            'title' => $request->title,
            'url' => $request->url,
            'thumbnail' => $thumbnailPath,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->boolean('status'),
        ]);

        ActivityLogger::log(
            'create',
            'videos',
            'Thêm video: ' . $video->title,
            Video::class,
            $video->id
        );

        return redirect()
            ->route('videos.index')
            ->with('success', 'Thêm video thành công');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|max:255',
            'url' => 'required|max:500',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        $thumbnailPath = $video->thumbnail;

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }

            $thumbnailPath = $request
                ->file('thumbnail')
                ->store('videos', 'public');
        }

        $video->update([
            'title' => $request->title,
            'url' => $request->url,
            'thumbnail' => $thumbnailPath,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->boolean('status'),
        ]);

        ActivityLogger::log(
            'update',
            'videos',
            'Cập nhật video: ' . $video->title,
            Video::class,
            $video->id
        );

        return redirect()
            ->route('videos.index')
            ->with('success', 'Cập nhật video thành công');
    }

    public function destroy(Video $video)
    {
        ActivityLogger::log(
            'delete',
            'videos',
            'Xóa video: ' . $video->title,
            Video::class,
            $video->id
        );

        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        $video->delete();

        return redirect()
            ->route('videos.index')
            ->with('success', 'Đã xóa video');
    }
}