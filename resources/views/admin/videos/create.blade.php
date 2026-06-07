@extends('admin.layouts.master')

@section('title', 'Thêm video')

@section('page-title', 'Thêm video')

@section('content')

<div class="card">
    <div class="card-header">
        <strong>
            <i class="fa-solid fa-video me-1"></i>
            Thông tin video
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
              action="{{ route('videos.store') }}"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label class="form-label">Tiêu đề video</label>

                <input type="text"
                       name="title"
                       class="form-control"
                       value="{{ old('title') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Đường dẫn video</label>

                <input type="url"
                       name="url"
                       class="form-control"
                       value="{{ old('url') }}"
                       placeholder="https://youtube.com/...">
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh thumbnail</label>

                <input type="file"
                       name="thumbnail"
                       class="form-control"
                       accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Thứ tự hiển thị</label>

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

                <label class="form-check-label" for="status">
                    Hiển thị
                </label>
            </div>

            <button class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                Lưu video
            </button>

            <a href="{{ route('videos.index') }}"
               class="btn btn-secondary">
                Quay lại
            </a>

        </form>

    </div>
</div>

@endsection