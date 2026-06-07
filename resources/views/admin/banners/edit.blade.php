@extends('admin.layouts.master')

@section('title', 'Sửa banner')

@section('page-title', 'Sửa banner')

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
      action="{{ route('banners.update', $banner->id) }}"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Tiêu đề</label>

        <input type="text"
               name="title"
               class="form-control"
               value="{{ old('title', $banner->title) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Ảnh hiện tại</label>

        <div>
            <img src="{{ asset('storage/' . $banner->image) }}"
                 width="300">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Đổi ảnh mới</label>

        <input type="file"
               name="image"
               class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Link khi bấm vào</label>

        <input type="text"
               name="link"
               class="form-control"
               value="{{ old('link', $banner->link) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Vị trí hiển thị</label>

        <select name="position" class="form-select">
            <option value="home_after_featured"
                {{ old('position', $banner->position) == 'home_after_featured' ? 'selected' : '' }}>
                Dưới Tin nổi bật trang chủ
            </option>

            <option value="sidebar_links"
                {{ old('position', $banner->position) == 'sidebar_links' ? 'selected' : '' }}>
                Cột phải - ảnh liên kết
            </option>

            <option value="home_top"
                {{ old('position', $banner->position) == 'home_top' ? 'selected' : '' }}>
                Banner đầu trang chủ
            </option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Thứ tự</label>

        <input type="number"
               name="sort_order"
               class="form-control"
               value="{{ old('sort_order', $banner->sort_order) }}">
    </div>

    <div class="form-check mb-3">
        <input type="checkbox"
               name="status"
               value="1"
               class="form-check-input"
               {{ old('status', $banner->status) ? 'checked' : '' }}>

        <label class="form-check-label">
            Hiển thị
        </label>
    </div>

    <button class="btn btn-success">
        Cập nhật
    </button>

    <a href="{{ route('banners.index') }}" class="btn btn-secondary">
        Quay lại
    </a>

</form>

@endsection