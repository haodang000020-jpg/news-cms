@extends('admin.layouts.master')

@section('title', 'Thêm bài viết')

@section('page-title', 'Thêm bài viết')

@section('content')

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
      action="{{ route('posts.store') }}"
      enctype="multipart/form-data">

    @csrf

    <div class="row">

        {{-- CỘT TRÁI --}}
        <div class="col-md-8">

            <div class="card mb-3">
                <div class="card-header">
                    <strong>
                        <i class="fa-solid fa-pen-to-square me-1"></i>
                        Nội dung bài viết
                    </strong>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label">Tiêu đề bài viết</label>

                        <input type="text"
                               name="title"
                               class="form-control form-control-lg"
                               value="{{ old('title') }}"
                               placeholder="Nhập tiêu đề bài viết">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tóm tắt</label>

                        <textarea name="summary"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Nhập mô tả ngắn cho bài viết">{{ old('summary') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nội dung</label>

                        <textarea id="editor"
                                  name="content"
                                  class="form-control"
                                  rows="12">{{ old('content') }}</textarea>
                    </div>

                </div>
            </div>

        </div>

        {{-- CỘT PHẢI --}}
        <div class="col-md-4">

            {{-- XUẤT BẢN --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>
                        <i class="fa-solid fa-paper-plane me-1"></i>
                        Xuất bản
                    </strong>
                </div>

                <div class="card-body">

                    @if(auth()->user()->hasRole('reporter'))

                        <div class="alert alert-info mb-3">
                            Bài viết của bạn sẽ được gửi ở trạng thái
                            <strong>Chờ duyệt</strong>.
                        </div>

                        <input type="hidden"
                               name="status"
                               value="pending">

                    @else

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>

                            <select name="status" class="form-select">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                                    Nháp
                                </option>

                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                    Chờ duyệt
                                </option>

                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                    Xuất bản
                                </option>

                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>
                                    Lưu trữ
                                </option>
                            </select>
                        </div>

                    @endif

                    <button class="btn btn-primary w-100">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Lưu bài viết
                    </button>

                    <a href="{{ route('posts.index') }}"
                       class="btn btn-secondary w-100 mt-2">
                        Quay lại
                    </a>

                </div>
            </div>

            {{-- TIN NỔI BẬT --}}
            @if(!auth()->user()->hasRole('reporter'))
                <div class="card mb-3">
                    <div class="card-header">
                        <strong>
                            <i class="fa-solid fa-star me-1"></i>
                            Tin nổi bật
                        </strong>
                    </div>

                    <div class="card-body">

                        <div class="form-check mb-3">
                            <input type="checkbox"
                                   name="is_featured"
                                   value="1"
                                   class="form-check-input"
                                   id="is_featured"
                                   {{ old('is_featured') ? 'checked' : '' }}>

                            <label class="form-check-label"
                                   for="is_featured">
                                Đánh dấu là tin nổi bật
                            </label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thứ tự hiển thị</label>

                            <input type="number"
                                   name="featured_order"
                                   class="form-control"
                                   value="{{ old('featured_order', 0) }}">
                        </div>

                    </div>
                </div>
            @endif

            {{-- ẢNH ĐẠI DIỆN --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>
                        <i class="fa-solid fa-image me-1"></i>
                        Ảnh đại diện
                    </strong>
                </div>

                <div class="card-body">

                    <div class="post-image-preview mb-3" id="imagePreview">
                        <i class="fa-regular fa-image"></i>
                        <span>Chưa chọn ảnh</span>
                    </div>

                    <input type="file"
                           name="featured_image"
                           class="form-control"
                           accept="image/*"
                           onchange="previewFeaturedImage(event)">

                </div>
            </div>

<div class="card mb-3">
    <div class="card-header">
        <strong>
            <i class="fa-solid fa-paperclip me-1"></i>
            File đính kèm
        </strong>
    </div>

    <div class="card-body">

        <input type="file"
               name="attachments[]"
               class="form-control"
               multiple
               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">

        <small class="text-muted d-block mt-2">
            Cho phép: PDF, Word, Excel, PowerPoint, ZIP, RAR. Tối đa 10MB/file.
        </small>

    </div>
</div>

            {{-- DANH MỤC --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>
                        <i class="fa-solid fa-folder-tree me-1"></i>
                        Danh mục
                    </strong>
                </div>

                <div class="card-body category-check-list">

                    @foreach($categories as $category)

                        <div class="form-check mb-2">
                            <input type="checkbox"
                                   name="categories[]"
                                   value="{{ $category->id }}"
                                   class="form-check-input"
                                   id="category_{{ $category->id }}"
                                   {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>

                            <label class="form-check-label"
                                   for="category_{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>

                    @endforeach

                </div>
            </div>

            {{-- TAGS --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>
                        <i class="fa-solid fa-tags me-1"></i>
                        Tags
                    </strong>
                </div>

                <div class="card-body category-check-list">

                    @forelse($tags as $tag)

                        <div class="form-check mb-2">
                            <input type="checkbox"
                                   name="tags[]"
                                   value="{{ $tag->id }}"
                                   class="form-check-input"
                                   id="tag_{{ $tag->id }}"
                                   {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>

                            <label class="form-check-label"
                                   for="tag_{{ $tag->id }}">
                                {{ $tag->name }}
                            </label>
                        </div>

                    @empty

                        <div class="text-muted">
                            Chưa có tag nào.
                        </div>

                    @endforelse

                </div>
            </div>

        </div>

    </div>

</form>

@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>
    class MyUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file.then(file => {
                return new Promise((resolve, reject) => {
                    const data = new FormData();

                    data.append('upload', file);

                    fetch("{{ route('ckeditor.upload') }}", {
                        method: 'POST',
                        body: data,
                        headers: {
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.url) {
                            resolve({
                                default: result.url
                            });
                        } else {
                            reject(result.error?.message || 'Upload thất bại.');
                        }
                    })
                    .catch(error => {
                        reject(error);
                    });
                });
            });
        }

        abort() {}
    }

    function MyCustomUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new MyUploadAdapter(loader);
        };
    }

    ClassicEditor
        .create(document.querySelector('#editor'), {
            extraPlugins: [MyCustomUploadAdapterPlugin],
        })
        .catch(error => {
            console.error(error);
        });

    function previewFeaturedImage(event) {
        const preview = document.getElementById('imagePreview');
        const file = event.target.files[0];

        if (!file) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };

        reader.readAsDataURL(file);
    }
</script>
@endpush