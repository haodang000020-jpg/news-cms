@extends('admin.layouts.master')

@section('title', 'Sửa menu')

@section('page-title', 'Sửa menu')

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
      action="{{ route('menus.update', $menu->id) }}">

    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Tiêu đề menu</label>

        <input type="text"
               name="title"
               class="form-control"
               value="{{ old('title', $menu->title) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Menu cha</label>

        <select name="parent_id" class="form-select">
            <option value="">-- Không có --</option>

            @foreach($parents as $parent)
                <option value="{{ $parent->id }}"
                    {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>
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
                <option value="{{ $category->id }}"
                    {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">URL tùy chỉnh</label>

        <input type="text"
               name="url"
               class="form-control"
               value="{{ old('url', $menu->url) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Mở liên kết</label>

        <select name="target" class="form-select">
            <option value="_self"
                {{ old('target', $menu->target) == '_self' ? 'selected' : '' }}>
                Cùng tab
            </option>

            <option value="_blank"
                {{ old('target', $menu->target) == '_blank' ? 'selected' : '' }}>
                Tab mới
            </option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Thứ tự</label>

        <input type="number"
               name="sort_order"
               class="form-control"
               value="{{ old('sort_order', $menu->sort_order) }}">
    </div>

    <div class="form-check mb-3">
        <input type="checkbox"
               name="status"
               value="1"
               class="form-check-input"
               {{ old('status', $menu->status) ? 'checked' : '' }}>

        <label class="form-check-label">
            Hiển thị
        </label>
    </div>

    <button class="btn btn-success">
        Cập nhật
    </button>

    <a href="{{ route('menus.index') }}" class="btn btn-secondary">
        Quay lại
    </a>

</form>

@endsection