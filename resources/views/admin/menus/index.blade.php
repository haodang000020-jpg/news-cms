@extends('admin.layouts.master')

@section('title', 'Menu')

@section('page-title', 'Quản lý Menu')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Danh sách menu</h3>

    <a href="{{ route('menus.create') }}" class="btn btn-primary">
        Thêm menu
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
            <th>Menu cha</th>
            <th>Danh mục</th>
            <th>URL</th>
            <th>Target</th>
            <th>Thứ tự</th>
            <th>Trạng thái</th>
            <th width="160">Thao tác</th>
        </tr>
    </thead>

    <tbody>
        @foreach($menus as $menu)
            <tr>
                <td>{{ $menu->id }}</td>

                <td>
                    <strong>{{ $menu->title }}</strong>
                </td>

                <td>{{ $menu->parent?->title }}</td>

                <td>{{ $menu->category?->name }}</td>

                <td>
                    <small>{{ $menu->url }}</small>
                </td>

                <td>{{ $menu->target }}</td>

                <td>{{ $menu->sort_order }}</td>

                <td>
                    @if($menu->status)
                        <span class="badge bg-success">Hiển thị</span>
                    @else
                        <span class="badge bg-secondary">Ẩn</span>
                    @endif
                </td>

                <td>
                    <a href="{{ route('menus.edit', $menu->id) }}"
                       class="btn btn-warning btn-sm">
                        Sửa
                    </a>

                    <form action="{{ route('menus.destroy', $menu->id) }}"
                          method="POST"
                          style="display:inline">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Xóa menu này?')">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $menus->links() }}

@endsection