@extends('admin.layouts.master')

@section('title', 'Thùng rác bài viết')

@section('page-title', 'Thùng rác bài viết')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>
            <i class="fa-solid fa-trash me-1"></i>
            Danh sách bài viết đã xóa
        </strong>

        <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-list"></i>
            Quay lại danh sách bài viết
        </a>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="70">ID</th>
                        <th width="100">Ảnh</th>
                        <th>Tiêu đề</th>
                        <th width="160">Danh mục</th>
                        <th width="150">Tác giả</th>
                        <th width="150">Ngày xóa</th>
                        <th width="190">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td class="text-center">
                                {{ $post->id }}
                            </td>

                            <td>
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                                         class="post-thumb"
                                         alt="{{ $post->title }}">
                                @else
                                    <div class="post-thumb-empty">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="post-title">
                                    {{ $post->title }}
                                </div>

                                <div class="post-slug">
                                    {{ $post->slug }}
                                </div>

                                <span class="badge bg-danger">
                                    Đã xóa
                                </span>
                            </td>

                            <td>
                                @foreach($post->categories as $category)
                                    <span class="badge bg-secondary mb-1">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </td>

                            <td>
                                {{ $post->author?->name }}
                            </td>

                            <td>
                                {{ $post->deleted_at?->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm">

                                    <form action="{{ route('posts.restore', $post->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-success"
                                                title="Khôi phục"
                                                onclick="return confirm('Khôi phục bài viết này?')">
                                            <i class="fa-solid fa-rotate-left"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('posts.force_delete', $post->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger"
                                                title="Xóa vĩnh viễn"
                                                onclick="return confirm('Xóa vĩnh viễn bài viết này? Hành động này không thể khôi phục.')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fa-regular fa-folder-open fa-2x d-block mb-2"></i>
                                Chưa có bài viết nào trong thùng rác.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $posts->links() }}
        </div>

    </div>
</div>

@endsection