@extends('admin.layouts.master')

@section('title', 'Video')

@section('page-title', 'Quản lý Video')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>
            <i class="fa-solid fa-video me-1"></i>
            Danh sách video
        </strong>

        <a href="{{ route('videos.create') }}"
           class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i>
            Thêm video
        </a>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="70">ID</th>
                        <th width="130">Ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Đường dẫn</th>
                        <th width="100">Thứ tự</th>
                        <th width="120">Trạng thái</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($videos as $video)
                        <tr>
                            <td class="text-center">
                                {{ $video->id }}
                            </td>

                            <td>
                                @if($video->thumbnail)
                                    <img src="{{ asset('storage/' . $video->thumbnail) }}"
                                         class="post-thumb"
                                         alt="{{ $video->title }}">
                                @else
                                    <div class="post-thumb-empty">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $video->title }}</strong>
                            </td>

                            <td>
                                <a href="{{ $video->url }}" target="_blank">
                                    {{ \Illuminate\Support\Str::limit($video->url, 60) }}
                                </a>
                            </td>

                            <td class="text-center">
                                {{ $video->sort_order }}
                            </td>

                            <td>
                                @if($video->status)
                                    <span class="badge bg-success">Hiển thị</span>
                                @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm">

                                    <a href="{{ route('videos.edit', $video->id) }}"
                                       class="btn btn-warning">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form action="{{ route('videos.destroy', $video->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger"
                                                onclick="return confirm('Xóa video này?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Chưa có video nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $videos->links() }}
        </div>

    </div>
</div>

@endsection