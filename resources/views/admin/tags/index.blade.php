@extends('admin.layouts.master')

@section('title', 'Tags')

@section('page-title', 'Quản lý Tags')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>
            <i class="fa-solid fa-tags me-1"></i>
            Danh sách Tags
        </strong>

        <a href="{{ route('tags.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i>
            Thêm tag
        </a>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="80">ID</th>
                    <th>Tên tag</th>
                    <th>Slug</th>
                    <th width="120">Trạng thái</th>
                    <th width="160">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                @foreach($tags as $tag)
                    <tr>
                        <td>{{ $tag->id }}</td>

                        <td>
                            <strong>{{ $tag->name }}</strong>
                        </td>

                        <td>
                            <code>{{ $tag->slug }}</code>
                        </td>

                        <td>
                            @if($tag->status)
                                <span class="badge bg-success">Hiển thị</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('tags.edit', $tag->id) }}"
                               class="btn btn-warning btn-sm">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            <form action="{{ route('tags.destroy', $tag->id) }}"
                                  method="POST"
                                  style="display:inline">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Xóa tag này?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $tags->links() }}

    </div>
</div>

@endsection