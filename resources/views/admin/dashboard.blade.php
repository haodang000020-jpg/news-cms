@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')

<div class="row">

    <div class="col-md-3 mb-3">
        <div class="dashboard-card bg-primary text-white">
            <div>
                <div class="dashboard-number">
                    {{ $totalPosts }}
                </div>
                <div class="dashboard-label">
                    Tổng bài viết
                </div>
            </div>

            <i class="fa-solid fa-file-lines dashboard-icon"></i>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="dashboard-card bg-success text-white">
            <div>
                <div class="dashboard-number">
                    {{ $publishedPosts }}
                </div>
                <div class="dashboard-label">
                    Đã xuất bản
                </div>
            </div>

            <i class="fa-solid fa-circle-check dashboard-icon"></i>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="dashboard-card bg-warning text-dark">
            <div>
                <div class="dashboard-number">
                    {{ $pendingPosts }}
                </div>
                <div class="dashboard-label">
                    Chờ duyệt
                </div>
            </div>

            <i class="fa-solid fa-clock dashboard-icon"></i>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="dashboard-card bg-danger text-white">
            <div>
                <div class="dashboard-number">
                    {{ $trashPosts }}
                </div>
                <div class="dashboard-label">
                    Trong thùng rác
                </div>
            </div>

            <i class="fa-solid fa-trash dashboard-icon"></i>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-3 mb-3">
        <div class="dashboard-card bg-info text-dark">
            <div>
                <div class="dashboard-number">
                    {{ $draftPosts }}
                </div>
                <div class="dashboard-label">
                    Bài nháp
                </div>
            </div>

            <i class="fa-solid fa-pen dashboard-icon"></i>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="dashboard-card bg-secondary text-white">
            <div>
                <div class="dashboard-number">
                    {{ $totalCategories }}
                </div>
                <div class="dashboard-label">
                    Danh mục
                </div>
            </div>

            <i class="fa-solid fa-folder-tree dashboard-icon"></i>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="dashboard-card bg-dark text-white">
            <div>
                <div class="dashboard-number">
                    {{ $totalUsers }}
                </div>
                <div class="dashboard-label">
                    Người dùng
                </div>
            </div>

            <i class="fa-solid fa-users dashboard-icon"></i>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="dashboard-card bg-light text-dark border">
            <div>
                <div class="dashboard-number">
                    {{ number_format($totalViews) }}
                </div>
                <div class="dashboard-label">
                    Tổng lượt xem
                </div>
            </div>

            <i class="fa-solid fa-eye dashboard-icon"></i>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-6 mb-3">

        <div class="card h-100">
            <div class="card-header">
                <strong>
                    <i class="fa-solid fa-clock-rotate-left me-1"></i>
                    Bài viết mới nhất
                </strong>
            </div>

            <div class="card-body p-0">

                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tiêu đề</th>
                            <th width="130">Tác giả</th>
                            <th width="110">Trạng thái</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($latestPosts as $post)
                            <tr>
                                <td>
                                    <a href="{{ route('posts.edit', $post->id) }}">
                                        {{ \Illuminate\Support\Str::limit($post->title, 55) }}
                                    </a>
                                </td>

                                <td>
                                    {{ $post->author?->name ?? 'Không rõ' }}
                                </td>

                                <td>
                                    @if($post->status === 'published')
                                        <span class="badge bg-success">Xuất bản</span>
                                    @elseif($post->status === 'pending')
                                        <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                    @elseif($post->status === 'draft')
                                        <span class="badge bg-secondary">Nháp</span>
                                    @else
                                        <span class="badge bg-dark">{{ $post->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    Chưa có bài viết.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

    </div>

    <div class="col-md-6 mb-3">

        <div class="card h-100">
            <div class="card-header">
                <strong>
                    <i class="fa-solid fa-fire me-1"></i>
                    Bài viết xem nhiều
                </strong>
            </div>

            <div class="card-body p-0">

                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tiêu đề</th>
                            <th width="120">Lượt xem</th>
                            <th width="130">Tác giả</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($mostViewedPosts as $post)
                            <tr>
                                <td>
                                    <a href="{{ route('posts.edit', $post->id) }}">
                                        {{ \Illuminate\Support\Str::limit($post->title, 55) }}
                                    </a>
                                </td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ number_format($post->views ?? 0) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $post->author?->name ?? 'Không rõ' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    Chưa có dữ liệu lượt xem.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

    </div>

</div>

@endsection