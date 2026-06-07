@extends('admin.layouts.master')

@section('title', 'Thêm banner')

@section('page-title', 'Thêm banner')

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
      action="{{ route('banners.store') }}"
      enctype="multipart/form-data">

    @csrf

    <div class="mb-3">
        <label class="form-label">Tiêu đề</label>

        <input type="text"
               name="title"
               class="form-control"
               value="{{ old('title') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Ảnh banner</label>

        <input type="file"
               name="image"
               class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Link khi bấm vào</label>

        <input type="text"
               name="link"
               class="form-control"
               value="{{ old('link') }}"
               placeholder="https://... hoặc để trống">
    </div>

    <div class="mb-3">
        <label class="form-label">Vị trí hiển thị</label>

        <select name="position" class="form-select">
            <option value="home_after_featured">
                Dưới Tin nổi bật trang chủ
            </option>

            <option value="sidebar_links">
                Cột phải - ảnh liên kết
            </option>

            <option value="home_top">
                Banner đầu trang chủ
            </option>
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

    <a href="{{ route('banners.index') }}" class="btn btn-secondary">
        Quay lại
    </a>

</form>

@endsection