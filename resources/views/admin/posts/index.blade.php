@extends('admin.layouts.master')

@section('title', 'Bài viết')

@section('page-title', 'Quản lý bài viết')

@section('content')

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fa-solid fa-file-lines me-1"></i>
            Danh sách bài viết
        </h3>

        @if(auth()->user()->hasPermission('post.create'))
            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i>
                Thêm bài viết
            </a>
        @endif
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

        <form method="GET"
              action="{{ route('posts.index') }}"
              class="filter-box mb-4">

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">
                        Từ khóa
                    </label>

                    <input type="text"
                           name="keyword"
                           class="form-control"
                           value="{{ request('keyword') }}"
                           placeholder="Tìm tiêu đề, tóm tắt, nội dung">
                </div>

                <div class="col-md-3">
                    <label class="form-label">
                        Trạng thái
                    </label>

                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>

                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                            Nháp
                        </option>

                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Chờ duyệt
                        </option>

                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                            Xuất bản
                        </option>

                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>
                            Lưu trữ
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">
                        Danh mục
                    </label>

                    <select name="category_id" class="form-select">
                        <option value="">-- Tất cả --</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-dark w-100">
                        <i class="fa-solid fa-filter"></i>
                        Lọc
                    </button>
                </div>

            </div>

            @if(request()->hasAny(['keyword', 'status', 'category_id']))
                <div class="mt-3">
                    <a href="{{ route('posts.index') }}"
                       class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-rotate-left"></i>
                        Xóa bộ lọc
                    </a>
                </div>
            @endif

        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle admin-post-table">
                <thead class="table-light">
                    <tr>
                        <th width="60">ID</th>
                        <th width="105">Ảnh</th>
                        <th>Tiêu đề</th>
                        <th width="150">Danh mục</th>
                        <th width="130">Tác giả</th>
                        <th width="120">Trạng thái</th>
                        <th width="135">Ngày đăng</th>
                        <th width="170">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($posts as $post)

                        @php
                            $statusLabels = [
                                'draft' => 'Nháp',
                                'pending' => 'Chờ duyệt',
                                'published' => 'Xuất bản',
                                'archived' => 'Lưu trữ',
                            ];

                            $statusColors = [
                                'draft' => 'secondary',
                                'pending' => 'warning',
                                'published' => 'success',
                                'archived' => 'dark',
                            ];
                        @endphp

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

                                <div class="post-meta">
                                    <i class="fa-regular fa-eye"></i>
                                    {{ $post->views ?? 0 }} lượt xem
                                </div>

                                @if($post->is_featured)
                                    <div class="mt-1">
                                        <span class="badge bg-danger">
                                            <i class="fa-solid fa-star"></i>
                                            Tin nổi bật
                                        </span>

                                        @if($post->featured_order)
                                            <span class="badge bg-secondary">
                                                Thứ tự: {{ $post->featured_order }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <td>
                                @foreach($post->categories as $category)
                                    <span class="badge bg-secondary mb-1">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </td>

                            <td>
                                {{ $post->author?->name ?? 'Không rõ' }}
                            </td>

                            <td>
                                <span class="badge bg-{{ $statusColors[$post->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$post->status] ?? $post->status }}
                                </span>
                            </td>

                            <td>
                                @if($post->published_at)
                                    <div>
                                        {{ $post->published_at->format('d/m/Y') }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $post->published_at->format('H:i') }}
                                    </small>
                                @else
                                    <span class="text-muted">
                                        Chưa đăng
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm">

                                    <a href="{{ route('posts.edit', $post->id) }}"
                                       class="btn btn-warning"
                                       title="Sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    @if(auth()->user()->hasPermission('post.publish') && $post->status !== 'published')
                                        <form action="{{ route('posts.publish', $post->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button class="btn btn-success"
                                                    title="Xuất bản"
                                                    onclick="return confirm('Xuất bản bài viết này?')">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if(auth()->user()->hasPermission('post.publish') && $post->status === 'published')
                                        <form action="{{ route('posts.draft', $post->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button class="btn btn-secondary"
                                                    title="Chuyển về nháp"
                                                    onclick="return confirm('Chuyển bài viết này về nháp?')">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if(auth()->user()->hasPermission('post.delete'))
                                        <form action="{{ route('posts.destroy', $post->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger"
                                                    title="Xóa"
                                                    onclick="return confirm('Đưa bài viết này vào thùng rác?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fa-regular fa-folder-open fa-2x d-block mb-2"></i>
                                Không có bài viết nào.
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