@extends('admin.layouts.master')

@section('title', 'Trang nội dung')

@section('page-title', 'Quản lý trang nội dung')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Danh sách trang</h3>

    <a href="{{ route('pages.create') }}" class="btn btn-primary">
        Thêm trang
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Slug</th>
            <th>Trạng thái</th>
            <th width="160">Thao tác</th>
        </tr>
    </thead>

    <tbody>
        @foreach($pages as $page)
            <tr>
                <td>{{ $page->id }}</td>

                <td>
                    <strong>{{ $page->title }}</strong>
                </td>

                <td>
                    <code>{{ $page->slug }}</code>
                </td>

                <td>
                    @if($page->status)
                        <span class="badge bg-success">Hiển thị</span>
                    @else
                        <span class="badge bg-secondary">Ẩn</span>
                    @endif
                </td>

                <td>
                    <a href="{{ route('pages.edit', $page->id) }}"
                       class="btn btn-warning btn-sm">
                        Sửa
                    </a>

                    <form action="{{ route('pages.destroy', $page->id) }}"
                          method="POST"
                          style="display:inline">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Xóa trang này?')">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $pages->links() }}

@endsection