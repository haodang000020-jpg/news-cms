<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;

class CKEditorController extends Controller
{
  public function upload(Request $request)
{
    if (!$request->hasFile('upload')) {
        return response()->json([
            'error' => [
                'message' => 'Không có file upload.'
            ]
        ], 400);
    }

    $request->validate([
        'upload' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
    ]);

    $uploadedFile = $request->file('upload');

    $path = $uploadedFile->store('ckeditor', 'public');

    Media::create([
        'uploader_id' => Auth::id(),
        'file_name' => $uploadedFile->getClientOriginalName(),
        'file_path' => $path,
        'mime_type' => $uploadedFile->getMimeType(),
        'file_size' => $uploadedFile->getSize(),
    ]);

    return response()->json([
        'url' => asset('storage/' . $path)
    ]);
}
}