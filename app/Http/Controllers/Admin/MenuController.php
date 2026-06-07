<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['parent', 'category'])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20);

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $parents = Menu::whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        return view('admin.menus.create', compact('parents', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'category_id' => 'nullable|exists:categories,id',
            'url' => 'nullable|max:500',
            'target' => 'required|in:_self,_blank',
            'sort_order' => 'nullable|integer',
        ]);

        Menu::create([
            'parent_id' => $request->parent_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'url' => $request->url ?: null,
            'target' => $request->target,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('menus.index')
            ->with('success', 'Thêm menu thành công');
    }

    public function edit(Menu $menu)
    {
        $parents = Menu::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->orderBy('sort_order')
            ->get();

        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        return view('admin.menus.edit', compact(
            'menu',
            'parents',
            'categories'
        ));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'category_id' => 'nullable|exists:categories,id',
            'url' => 'nullable|max:500',
            'target' => 'required|in:_self,_blank',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->parent_id == $menu->id) {
            return back()->withErrors('Menu cha không được là chính nó.');
        }

        $menu->update([
            'parent_id' => $request->parent_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'url' => $request->url ?: null,
            'target' => $request->target,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('menus.index')
            ->with('success', 'Cập nhật menu thành công');
    }

    public function destroy(Menu $menu)
    {
        $menu->children()->update([
            'parent_id' => null
        ]);

        $menu->delete();

        return redirect()
            ->route('menus.index')
            ->with('success', 'Đã xóa menu');
    }
}