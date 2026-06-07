@extends('admin.layouts.master')

@section('title', 'Liên kết website')

@section('page-title', 'Liên kết website')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>
            <i class="fa-solid fa-link me-1"></i>
            Danh sách liên kết website
        </strong>

        <a href="{{ route('website-links.create') }}"
           class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i>
            Thêm liên kết
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
                        <th>Tiêu đề</th>
                        <th>Đường dẫn</th>
                        <th width="120">Thứ tự</th>
                        <th width="120">Trạng thái</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($links as $link)
                        <tr>
                            <td class="text-center">
                                {{ $link->id }}
                            </td>

                            <td>
                                <strong>{{ $link->title }}</strong>
                            </td>

                            <td>
                                <a href="{{ $link->url }}"
                                   target="_blank">
                                    {{ $link->url }}
                                </a>
                            </td>

                            <td>
                                {{ $link->sort_order }}
                            </td>

                            <td>
                                @if($link->status)
                                    <span class="badge bg-success">
                                        Hiển thị
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Ẩn
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm">

                                    <a href="{{ route('website-links.edit', $link->id) }}"
                                       class="btn btn-warning"
                                       title="Sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form action="{{ route('website-links.destroy', $link->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger"
                                                title="Xóa"
                                                onclick="return confirm('Xóa liên kết này?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Chưa có liên kết website nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $links->links() }}
        </div>

    </div>
</div>

@endsection