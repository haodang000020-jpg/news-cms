<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $categories = \App\Models\Category::with('parent')
        ->orderBy('id')
        ->get();

    return view(
        'admin.categories.index',
        compact('categories')
    );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $parents = Category::whereNull('parent_id')
        ->orderBy('name')
        ->get();

    return view(
        'admin.categories.create',
        compact('parents')
    );
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|max:255',
        'parent_id' => 'nullable|exists:categories,id',
        'sort_order' => 'nullable|integer',
    ]);

    Category::create([
        'parent_id' => $request->parent_id,
        'name' => $request->name,
        'slug' => $this->uniqueSlug($request->name),
        'sort_order' => $request->sort_order ?? 0,
        'status' => true
    ]);

    return redirect()
        ->route('categories.index')
        ->with('success', 'Thêm danh mục thành công');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
         $parents = Category::whereNull('parent_id')
        ->where('id', '!=', $category->id)
        ->get();

    return view(
        'admin.categories.edit',
        compact(
            'category',
            'parents'
        )
    );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
{
    $request->validate([
        'name' => 'required|max:255',
        'parent_id' => 'nullable|exists:categories,id',
        'sort_order' => 'nullable|integer',
    ]);

    if ($request->parent_id == $category->id) {
        return back()
            ->withErrors('Danh mục cha không được là chính nó.');
    }

    $category->update([
        'parent_id' => $request->parent_id,
        'name' => $request->name,
        'slug' => $this->uniqueSlug($request->name, $category->id),
        'sort_order' => $request->sort_order ?? 0,
    ]);

    return redirect()
        ->route('categories.index')
        ->with('success', 'Cập nhật thành công');
}

    /**
     * Remove the specified resource from storage.
     */
 public function destroy(Category $category)
{
    $category->delete();

    return redirect()
        ->route('categories.index')
        ->with(
            'success',
            'Đã xóa'
        );
}
private function uniqueSlug($name, $ignoreId = null)
{
    $slug = Str::slug($name);
    $originalSlug = $slug;
    $count = 1;

    while (
        Category::where('slug', $slug)
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
