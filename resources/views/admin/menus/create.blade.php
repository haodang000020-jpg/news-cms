@extends('admin.layouts.master')

@section('title', 'Thêm menu')

@section('page-title', 'Thêm menu')

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
      action="{{ route('menus.store') }}">

    @csrf

    <div class="mb-3">
        <label class="form-label">Tiêu đề menu</label>

        <input type="text"
               name="title"
               class="form-control"
               value="{{ old('title') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Menu cha</label>

        <select name="parent_id" class="form-select">
            <option value="">-- Không có --</option>

            @foreach($parents as $parent)
                <option value="{{ $parent->id }}">
                    {{ $parent->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Liên kết đến danh mục</label>

        <select name="category_id" class="form-select">
            <option value="">-- Không chọn --</option>

            @foreach($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <small class="text-muted">
            Nếu chọn danh mục, hệ thống sẽ tự tạo link đến danh mục đó.
        </small>
    </div>

    <div class="mb-3">
        <label class="form-label">URL tùy chỉnh</label>

        <input type="text"
               name="url"
               class="form-control"
               value="{{ old('url') }}"
               placeholder="/gioi-thieu hoặc https://...">
    </div>

    <div class="mb-3">
        <label class="form-label">Mở liên kết</label>

        <select name="target" class="form-select">
            <option value="_self">Cùng tab</option>
            <option value="_blank">Tab mới</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Thứ tự</label>

        <input type="number"
               name="sort_order"
               class="form-control"
               value="{{ old('sort_order', 0) }}">
    </div>

    <div class="form-check mb-3">
        <input type="checkbox"
               name="status"
               value="1"
               class="form-check-input"
               checked>

        <label class="form-check-label">
            Hiển thị
        </label>
    </div>

    <button class="btn btn-primary">
        Lưu
    </button>

    <a href="{{ route('menus.index') }}" class="btn btn-secondary">
        Quay lại
    </a>

</form>

@endsection