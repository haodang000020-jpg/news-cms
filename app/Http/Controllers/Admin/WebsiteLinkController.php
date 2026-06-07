<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\WebsiteLink;
use Illuminate\Http\Request;

class WebsiteLinkController extends Controller
{
    public function index()
    {
        $links = WebsiteLink::orderBy('sort_order')
            ->latest()
            ->paginate(15);

        return view('admin.website_links.index', compact('links'));
    }

    public function create()
    {
        return view('admin.website_links.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'url' => 'required|max:500',
            'sort_order' => 'nullable|integer',
        ]);

        $link = WebsiteLink::create([
            'title' => $request->title,
            'url' => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->boolean('status'),
        ]);

        ActivityLogger::log(
            'create',
            'website_links',
            'Thêm liên kết website: ' . $link->title,
            WebsiteLink::class,
            $link->id
        );

        return redirect()
            ->route('website-links.index')
            ->with('success', 'Thêm liên kết website thành công');
    }

    public function edit(WebsiteLink $websiteLink)
    {
        return view('admin.website_links.edit', compact('websiteLink'));
    }

    public function update(Request $request, WebsiteLink $websiteLink)
    {
        $request->validate([
            'title' => 'required|max:255',
            'url' => 'required|max:500',
            'sort_order' => 'nullable|integer',
        ]);

        $websiteLink->update([
            'title' => $request->title,
            'url' => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->boolean('status'),
        ]);

        ActivityLogger::log(
            'update',
            'website_links',
            'Cập nhật liên kết website: ' . $websiteLink->title,
            WebsiteLink::class,
            $websiteLink->id
        );

        return redirect()
            ->route('website-links.index')
            ->with('success', 'Cập nhật liên kết website thành công');
    }

    public function destroy(WebsiteLink $websiteLink)
    {
        ActivityLogger::log(
            'delete',
            'website_links',
            'Xóa liên kết website: ' . $websiteLink->title,
            WebsiteLink::class,
            $websiteLink->id
        );

        $websiteLink->delete();

        return redirect()
            ->route('website-links.index')
            ->with('success', 'Đã xóa liên kết website');
    }
}