@extends('admin.layouts.master')

@section('title', 'Bài chờ duyệt')

@section('page-title', 'Bài chờ duyệt')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>
            <i class="fa-solid fa-clock me-1"></i>
            Danh sách bài viết chờ duyệt
        </strong>

        <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-list"></i>
            Tất cả bài viết
        </a>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="70">ID</th>
                        <th width="100">Ảnh</th>
                        <th>Tiêu đề</th>
                        <th width="160">Danh mục</th>
                        <th width="150">Tác giả</th>
                        <th width="150">Ngày gửi</th>
                        <th width="190">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td class="text-center">
                                {{ $post->id }}
                            </td>

                            <td>
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                                         class="post-thumb"
                                         alt="{{ $post->title }}">
                                @else
                                    <div class="post-thumb-empty">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="post-title">
                                    {{ $post->title }}
                                </div>

                                <div class="post-slug">
                                    {{ $post->slug }}
                                </div>

                                <span class="badge bg-warning text-dark">
                                    Chờ duyệt
                                </span>
                            </td>

                            <td>
                                @foreach($post->categories as $category)
                                    <span class="badge bg-secondary mb-1">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </td>

                            <td>
                                {{ $post->author?->name }}
                            </td>

                            <td>
                                {{ $post->created_at?->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm">

                                    <a href="{{ route('posts.edit', $post->id) }}"
                                       class="btn btn-warning"
                                       title="Xem / sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form action="{{ route('posts.publish', $post->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-success"
                                                title="Duyệt và xuất bản"
                                                onclick="return confirm('Duyệt và xuất bản bài viết này?')">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>

                                    <button type="button"
                                            class="btn btn-secondary"
                                            title="Trả về nháp"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $post->id }}">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fa-regular fa-folder-open fa-2x d-block mb-2"></i>
                                Không có bài viết nào đang chờ duyệt.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MODAL ĐỂ NGOÀI TABLE CHO ĐÚNG HTML --}}
        @foreach($posts as $post)
            <div class="modal fade"
                 id="rejectModal{{ $post->id }}"
                 tabindex="-1">

                <div class="modal-dialog">
                    <form action="{{ route('posts.reject', $post->id) }}"
                          method="POST"
                          class="modal-content">

                        @csrf
                        @method('PATCH')

                        <div class="modal-header">
                            <h5 class="modal-title">
                                Trả bài viết về nháp
                            </h5>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal">
                            </button>
                        </div>

                        <div class="modal-body">

                            <p>
                                Bạn đang trả bài:
                                <strong>{{ $post->title }}</strong>
                            </p>

                            <div class="mb-3">
                                <label class="form-label">
                                    Lý do trả bài / nội dung cần chỉnh sửa
                                </label>

                                <textarea name="review_note"
                                          class="form-control"
                                          rows="5"
                                          placeholder="Ví dụ: Cần bổ sung nguồn tin, chỉnh lỗi chính tả, thay ảnh đại diện..."></textarea>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                Hủy
                            </button>

                            <button class="btn btn-warning">
                                Trả về nháp
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        @endforeach

        <div class="mt-3">
            {{ $posts->links() }}
        </div>

    </div>
</div>

@endsection