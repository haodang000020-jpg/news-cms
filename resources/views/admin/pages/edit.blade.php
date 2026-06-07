@extends('admin.layouts.master')

@section('title', 'Sửa trang')

@section('page-title', 'Sửa trang nội dung')

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
      action="{{ route('pages.update', $page->id) }}"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Tiêu đề</label>

        <input type="text"
               name="title"
               class="form-control"
               value="{{ old('title', $page->title) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Tóm tắt</label>

        <textarea name="summary"
                  class="form-control"
                  rows="3">{{ old('summary', $page->summary) }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Nội dung</label>

        <textarea id="editor"
                  name="content"
                  class="form-control"
                  rows="10">{{ old('content', $page->content) }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Ảnh hiện tại</label>

        @if($page->featured_image)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $page->featured_image) }}"
                     width="200">
            </div>
        @endif

        <input type="file"
               name="featured_image"
               class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">SEO title</label>

        <input type="text"
               name="seo_title"
               class="form-control"
               value="{{ old('seo_title', $page->seo_title) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">SEO description</label>

        <textarea name="seo_description"
                  class="form-control"
                  rows="3">{{ old('seo_description', $page->seo_description) }}</textarea>
    </div>

    <div class="form-check mb-3">
        <input type="checkbox"
               name="status"
               value="1"
               class="form-check-input"
               {{ old('status', $page->status) ? 'checked' : '' }}>

        <label class="form-check-label">
            Hiển thị
        </label>
    </div>

    <button class="btn btn-success">
        Cập nhật
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