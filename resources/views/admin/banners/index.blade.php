@extends('admin.layouts.master')

@section('title', 'Banner')

@section('page-title', 'Quản lý Banner')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Danh sách banner</h3>

    <a href="{{ route('banners.create') }}" class="btn btn-primary">
        Thêm banner
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
            <th>Ảnh</th>
            <th>Tiêu đề</th>
            <th>Vị trí</th>
            <th>Thứ tự</th>
            <th>Trạng thái</th>
            <th width="160">Thao tác</th>
        </tr>
    </thead>

    <tbody>
        @foreach($banners as $banner)
            <tr>
                <td>{{ $banner->id }}</td>

                <td>
                    <img src="{{ asset('storage/' . $banner->image) }}"
                         width="180">
                </td>

                <td>
                    <strong>{{ $banner->title }}</strong>

                    @if($banner->link)
                        <br>
                        <small>{{ $banner->link }}</small>
                    @endif
                </td>

                <td>{{ $banner->position }}</td>

                <td>{{ $banner->sort_order }}</td>

                <td>
                    @if($banner->status)
                        <span class="badge bg-success">Hiển thị</span>
                    @else
                        <span class="badge bg-secondary">Ẩn</span>
                    @endif
                </td>

                <td>
                    <a href="{{ route('banners.edit', $banner->id) }}"
                       class="btn btn-warning btn-sm">
                        Sửa
                    </a>

                    <form action="{{ route('banners.destroy', $banner->id) }}"
                          method="POST"
                          style="display:inline">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Xóa banner này?')">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $banners->links() }}

@endsection