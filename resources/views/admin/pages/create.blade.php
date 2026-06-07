@extends('admin.layouts.master')

@section('title', 'Thêm trang')

@section('page-title', 'Thêm trang nội dung')

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
      action="{{ route('pages.store') }}"
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
        <label class="form-label">Tóm tắt</label>

        <textarea name="summary"
                  class="form-control"
                  rows="3">{{ old('summary') }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Nội dung</label>

        <textarea id="editor"
                  name="content"
                  class="form-control"
                  rows="10">{{ old('content') }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Ảnh đại diện</label>

        <input type="file"
               name="featured_image"
               class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">SEO title</label>

        <input type="text"
               name="seo_title"
               class="form-control"
               value="{{ old('seo_title') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">SEO description</label>

        <textarea name="seo_description"
                  class="form-control"
                  rows="3">{{ old('seo_description') }}</textarea>
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

    <a href="{{ route('pages.index') }}" class="btn btn-secondary">
        Quay lại
    </a>

</form>

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>

@endsection