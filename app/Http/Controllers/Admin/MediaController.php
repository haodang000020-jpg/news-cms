<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::with('uploader')
            ->latest()
            ->paginate(24);

        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('media', 'public');

            Media::create([
                'uploader_id' => Auth::id(),
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        return back()->with('success', 'Upload file thành công');
    }

    public function destroy(Media $medium)
    {
        Storage::disk('public')->delete($medium->file_path);

        $medium->delete();

        return back()->with('success', 'Đã xóa file');
    }
}