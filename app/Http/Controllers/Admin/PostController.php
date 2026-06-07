<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\NotificationHelper;
use App\Models\PostAttachment;

class PostController extends Controller
{
    /**
     * Danh sách bài viết
     */
    public function index(Request $request)
    {
        $query = Post::with(['author', 'categories', 'tags']);

        // Reporter chỉ thấy bài của chính mình
        if (auth()->user()->hasRole('reporter')) {
            $query->where('author_id', auth()->id());
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('summary', 'like', '%' . $keyword . '%')
                    ->orWhere('content', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        $posts = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        return view('admin.posts.index', compact(
            'posts',
            'categories'
        ));
    }

    /**
     * Form thêm bài viết
     */
    public function create()
    {
        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        $tags = Tag::where('status', true)
            ->orderBy('name')
            ->get();

        return view('admin.posts.create', compact(
            'categories',
            'tags'
        ));
    }

    /**
     * Lưu bài viết mới
     */
    public function store(Request $request)
    {
     $request->validate([
    'title' => 'required|max:500',
    'summary' => 'nullable',
    'content' => 'required',
    'status' => 'required|in:draft,pending,published,archived',
    'categories' => 'required|array',
    'categories.*' => 'exists:categories,id',
    'tags' => 'nullable|array',
    'tags.*' => 'exists:tags,id',
    'is_featured' => 'nullable|boolean',
    'featured_order' => 'nullable|integer',
    'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    'attachments' => 'nullable|array',
    'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:10240',
]);

        $imagePath = null;

        if ($request->hasFile('featured_image')) {
            $imagePath = $request
                ->file('featured_image')
                ->store('posts', 'public');
        }

        $status = auth()->user()->hasRole('reporter')
            ? 'pending'
            : $request->status;

       $post = Post::create([
    'author_id' => Auth::id(),
    'title' => $request->title,
    'slug' => $this->uniqueSlug($request->title),
    'summary' => $request->summary,
    'content' => $request->content,
    'featured_image' => $imagePath,
    'status' => $status,
    'is_featured' => $request->boolean('is_featured'),
    'featured_order' => $request->featured_order ?? 0,
    'published_at' => (
        !auth()->user()->hasRole('reporter') &&
        $request->status === 'published'
    ) ? now() : null,
    'review_note' => null,
]);

        $post->categories()->sync($request->categories);

        $post->tags()->sync($request->tags ?? []);
        if ($request->hasFile('attachments')) {
    foreach ($request->file('attachments') as $file) {
        $path = $file->store('post_attachments', 'public');

        PostAttachment::create([
            'post_id' => $post->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
        ]);
    }
}

        ActivityLogger::log(
            'create',
            'posts',
            'Tạo bài viết: ' . $post->title,
            Post::class,
            $post->id
        );
        if (auth()->user()->hasRole('reporter')) {
    NotificationHelper::sendToUsersByPermission(
        'post.publish',
        'Có bài viết mới chờ duyệt',
        auth()->user()->name . ' vừa gửi bài viết: ' . $post->title,
        route('posts.pending')
    );
}

        return redirect()
            ->route('posts.index')
            ->with('success', 'Thêm bài viết thành công');
    }

    /**
     * Không dùng trong admin hiện tại
     */
    public function show(Post $post)
    {
        abort(404);
    }

    /**
     * Form sửa bài viết
     */
    public function edit(Post $post)
    {
        if (
            auth()->user()->hasRole('reporter') &&
            $post->author_id !== auth()->id()
            ) {
                abort(403, 'Bạn chỉ được sửa bài viết của chính mình.');
                }
                $post->load('attachments');

        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        $tags = Tag::where('status', true)
            ->orderBy('name')
            ->get();

        $selectedCategories = $post
            ->categories
            ->pluck('id')
            ->toArray();

        $selectedTags = $post
            ->tags
            ->pluck('id')
            ->toArray();

        return view('admin.posts.edit', compact(
            'post',
            'categories',
            'selectedCategories',
            'tags',
            'selectedTags'
        ));
    }

    /**
     * Cập nhật bài viết
     */
    public function update(Request $request, Post $post)
    {
        if (
            auth()->user()->hasRole('reporter') &&
            $post->author_id !== auth()->id()
        ) {
            abort(403, 'Bạn chỉ được cập nhật bài viết của chính mình.');
        }

            $request->validate([
                'title' => 'required|max:500',
                'summary' => 'nullable',
                'content' => 'required',
                'status' => 'required|in:draft,pending,published,archived',
                'categories' => 'required|array',
                'categories.*' => 'exists:categories,id',
                'tags' => 'nullable|array',
                'tags.*' => 'exists:tags,id',
                'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'attachments' => 'nullable|array',
                'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:10240',
            ]);

        $imagePath = $post->featured_image;

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            $imagePath = $request
                ->file('featured_image')
                ->store('posts', 'public');
        }

        $post->update([
            'title' => $request->title,
            'slug' => $this->uniqueSlug($request->title, $post->id),
            'summary' => $request->summary,
            'content' => $request->content,
            'featured_image' => $imagePath,

            // Reporter sửa bài xong thì tự gửi lại chờ duyệt
            'status' => auth()->user()->hasRole('reporter')
                ? 'pending'
                : $request->status,
                'is_featured' => auth()->user()->hasRole('reporter')
    ? $post->is_featured
    : $request->boolean('is_featured'),

'featured_order' => auth()->user()->hasRole('reporter')
    ? $post->featured_order
    : ($request->featured_order ?? 0),

            'published_at' => (
                !auth()->user()->hasRole('reporter') &&
                $request->status === 'published'
            ) ? ($post->published_at ?? now()) : null,

            // Reporter sửa lại bài thì xóa ghi chú trả bài cũ
            'review_note' => auth()->user()->hasRole('reporter')
                ? null
                : $post->review_note,
        ]);

        $post->categories()->sync($request->categories);

        $post->tags()->sync($request->tags ?? []);

        if ($request->hasFile('attachments')) {
    foreach ($request->file('attachments') as $file) {
        $path = $file->store('post_attachments', 'public');

        PostAttachment::create([
            'post_id' => $post->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
        ]);
    }
}

        ActivityLogger::log(
            'update',
            'posts',
            'Cập nhật bài viết: ' . $post->title,
            Post::class,
            $post->id
        );

        return redirect()
            ->route('posts.index')
            ->with('success', 'Cập nhật bài viết thành công');
    }

    /**
     * Xóa bài viết
     */
    public function destroy(Post $post)
{
    if (!auth()->user()->hasPermission('post.delete')) {
        abort(403, 'Bạn không có quyền xóa bài viết.');
    }

    ActivityLogger::log(
        'delete',
        'posts',
        'Đưa bài viết vào thùng rác: ' . $post->title,
        Post::class,
        $post->id
    );

    $post->delete();

    return redirect()
        ->route('posts.index')
        ->with('success', 'Bài viết đã được đưa vào thùng rác');
}

    /**
     * Duyệt và xuất bản bài viết
     */
    public function publish(Post $post)
    {
        if (!auth()->user()->hasPermission('post.publish')) {
            abort(403, 'Bạn không có quyền xuất bản bài viết.');
        }

        $post->update([
            'status' => 'published',
            'published_at' => $post->published_at ?? now(),
            'review_note' => null,
        ]);

        ActivityLogger::log(
            'publish',
            'posts',
            'Duyệt và xuất bản bài viết: ' . $post->title,
            Post::class,
            $post->id
        );

        return back()->with('success', 'Bài viết đã được xuất bản');
    }

    /**
     * Chuyển bài về nháp
     */
    public function draft(Post $post)
    {
        if (!auth()->user()->hasPermission('post.publish')) {
            abort(403, 'Bạn không có quyền chuyển bài về nháp.');
        }

        $post->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        ActivityLogger::log(
            'draft',
            'posts',
            'Chuyển bài viết về nháp: ' . $post->title,
            Post::class,
            $post->id
        );

        return back()->with('success', 'Bài viết đã chuyển về nháp');
    }

    /**
     * Danh sách bài viết chờ duyệt
     */
    public function pending()
{
    if (!auth()->user()->hasPermission('post.publish')) {
        abort(403, 'Bạn không có quyền duyệt bài viết.');
    }

    Notification::where('user_id', auth()->id())
        ->where('is_read', false)
        ->where('link', route('posts.pending'))
        ->update([
            'is_read' => true,
        ]);

    $posts = Post::with(['author', 'categories', 'tags'])
        ->where('status', 'pending')
        ->latest()
        ->paginate(10);

    return view('admin.posts.pending', compact('posts'));
}

    /**
     * Trả bài về nháp kèm lý do
     */
    public function reject(Request $request, Post $post)
    {
        if (!auth()->user()->hasPermission('post.publish')) {
            abort(403, 'Bạn không có quyền từ chối bài viết.');
        }

        $request->validate([
            'review_note' => 'nullable|max:2000',
        ]);

        $post->update([
            'status' => 'draft',
            'published_at' => null,
            'review_note' => $request->review_note,
        ]);

        ActivityLogger::log(
            'reject',
            'posts',
            'Trả bài viết về nháp: ' . $post->title,
            Post::class,
            $post->id
        );

        NotificationHelper::sendToUser(
    $post->author_id,
    'Bài viết cần chỉnh sửa',
    'Bài viết "' . $post->title . '" đã được trả về nháp. Vui lòng kiểm tra ghi chú của biên tập viên.',
    route('posts.edit', $post->id)
);
        return back()->with('success', 'Bài viết đã được trả về nháp kèm lý do.');
    }
public function trash()
{
    if (!auth()->user()->hasPermission('post.delete')) {
        abort(403, 'Bạn không có quyền xem thùng rác.');
    }

    $posts = Post::onlyTrashed()
        ->with(['author', 'categories', 'tags'])
        ->latest('deleted_at')
        ->paginate(10);

    return view('admin.posts.trash', compact('posts'));
}

public function restore($id)
{
    if (!auth()->user()->hasPermission('post.delete')) {
        abort(403, 'Bạn không có quyền khôi phục bài viết.');
    }

    $post = Post::onlyTrashed()->findOrFail($id);

    $post->restore();

    ActivityLogger::log(
        'restore',
        'posts',
        'Khôi phục bài viết: ' . $post->title,
        Post::class,
        $post->id
    );

    return redirect()
        ->route('posts.trash')
        ->with('success', 'Đã khôi phục bài viết');
}

public function forceDelete($id)
{
    if (!auth()->user()->hasPermission('post.delete')) {
        abort(403, 'Bạn không có quyền xóa vĩnh viễn bài viết.');
    }

    $post = Post::onlyTrashed()->findOrFail($id);

    ActivityLogger::log(
        'force_delete',
        'posts',
        'Xóa vĩnh viễn bài viết: ' . $post->title,
        Post::class,
        $post->id
    );

    $post->categories()->detach();
    $post->tags()->detach();

    if ($post->featured_image) {
        Storage::disk('public')->delete($post->featured_image);
    }

    $post->forceDelete();

    return redirect()
        ->route('posts.trash')
        ->with('success', 'Đã xóa vĩnh viễn bài viết');
}

    /**
     * Tạo slug không trùng
     */
    private function uniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (
            Post::where('slug', $slug)
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

    public function deleteAttachment(PostAttachment $attachment)
{
    $post = $attachment->post;

    if (
        auth()->user()->hasRole('reporter') &&
        $post->author_id !== auth()->id()
    ) {
        abort(403, 'Bạn không có quyền xóa file của bài viết này.');
    }

    if ($attachment->file_path) {
        Storage::disk('public')->delete($attachment->file_path);
    }

    ActivityLogger::log(
        'delete_attachment',
        'posts',
        'Xóa file đính kèm: ' . $attachment->file_name,
        Post::class,
        $post->id
    );

    $attachment->delete();

    return back()->with('success', 'Đã xóa file đính kèm');
}
}