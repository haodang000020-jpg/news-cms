@extends('admin.layouts.master')

@section('title', 'Người dùng')

@section('page-title', 'Quản lý người dùng')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Danh sách người dùng</h3>

    <a href="{{ route('users.create') }}" class="btn btn-primary">
        Thêm người dùng
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Ngày tạo</th>
            <th width="160">Thao tác</th>
        </tr>
    </thead>

    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>

                <td>
                    <strong>{{ $user->name }}</strong>

                    @if($user->id === auth()->id())
                        <span class="badge bg-info">Bạn</span>
                    @endif
                </td>

                <td>{{ $user->email }}</td>

                <td>
                    <span class="badge bg-dark">
                        {{ $user->role?->name ?? 'Chưa có vai trò' }}
                    </span>
                </td>

                <td>{{ $user->created_at?->format('d/m/Y H:i') }}</td>

                <td>
                    <a href="{{ route('users.edit', $user->id) }}"
                       class="btn btn-warning btn-sm">
                        Sửa
                    </a>

                    @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user->id) }}"
                              method="POST"
                              style="display:inline">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Xóa người dùng này?')">
                                Xóa
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $users->links() }}

@endsection