@extends('admin.layouts.master')

@section('title', 'Sửa video')

@section('page-title', 'Sửa video')

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
              action="{{ route('videos.update', $video->id) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tiêu đề video</label>

                <input type="text"
                       name="title"
                       class="form-control"
                       value="{{ old('title', $video->title) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Đường dẫn video</label>

                <input type="url"
                       name="url"
                       class="form-control"
                       value="{{ old('url', $video->url) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Thumbnail hiện tại</label>

                <div>
                    @if($video->thumbnail)
                        <img src="{{ asset('storage/' . $video->thumbnail) }}"
                             style="width:160px; height:90px; object-fit:cover;"
                             alt="{{ $video->title }}">
                    @else
                        <span class="text-muted">Chưa có ảnh.</span>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Thay ảnh thumbnail</label>

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
                       value="{{ old('sort_order', $video->sort_order) }}">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox"
                       name="status"
                       value="1"
                       class="form-check-input"
                       id="status"
                       {{ old('status', $video->status) ? 'checked' : '' }}>

                <label class="form-check-label" for="status">
                    Hiển thị
                </label>
            </div>

            <button class="btn btn-success">
                <i class="fa-solid fa-floppy-disk"></i>
                Cập nhật
            </button>

            <a href="{{ route('videos.index') }}"
               class="btn btn-secondary">
                Quay lại
            </a>

        </form>

    </div>
</div>

@endsection