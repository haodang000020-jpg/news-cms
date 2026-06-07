@extends('admin.layouts.master')

@section('title', 'Sửa người dùng')

@section('page-title', 'Sửa người dùng')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ route('users.update', $user->id) }}">

    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Họ tên</label>

        <input type="text"
               name="name"
               class="form-control"
               value="{{ old('name', $user->name) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>

        <input type="email"
               name="email"
               class="form-control"
               value="{{ old('email', $user->email) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Vai trò</label>

        <select name="role_id" class="form-select">
            <option value="">-- Chọn vai trò --</option>

            @foreach($roles as $role)
                <option value="{{ $role->id }}"
                    {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                    {{ $role->name }} - {{ $role->code }}
                </option>
            @endforeach
        </select>
    </div>

    <hr>

    <p class="text-muted">
        Để trống mật khẩu nếu không muốn đổi.
    </p>

    <div class="mb-3">
        <label class="form-label">Mật khẩu mới</label>

        <input type="password"
               name="password"
               class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Nhập lại mật khẩu mới</label>

        <input type="password"
               name="password_confirmation"
               class="form-control">
    </div>

    <button class="btn btn-success">
        Cập nhật
    </button>

    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        Quay lại
    </a>

</form>

@endsection