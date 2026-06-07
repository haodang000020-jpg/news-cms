@extends('admin.layouts.master')

@section('title', 'Thêm liên kết website')

@section('page-title', 'Thêm liên kết website')

@section('content')

<div class="card">
    <div class="card-header">
        <strong>
            <i class="fa-solid fa-link me-1"></i>
            Thông tin liên kết
        </strong>
    </div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Có lỗi xảy ra:</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('website-links.store') }}">

            @csrf

            <div class="mb-3">
                <label class="form-label">
                    Tiêu đề
                </label>

                <input type="text"
                       name="title"
                       class="form-control"
                       value="{{ old('title') }}"
                       placeholder="Ví dụ: Cổng thông tin điện tử tỉnh An Giang">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Đường dẫn
                </label>

                <input type="url"
                       name="url"
                       class="form-control"
                       value="{{ old('url') }}"
                       placeholder="https://example.com">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Thứ tự hiển thị
                </label>

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
                       id="status"
                       checked>

                <label class="form-check-label"
                       for="status">
                    Hiển thị
                </label>
            </div>

            <button class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                Lưu liên kết
            </button>

            <a href="{{ route('website-links.index') }}"
               class="btn btn-secondary">
                Quay lại
            </a>

        </form>

    </div>
</div>

@endsection