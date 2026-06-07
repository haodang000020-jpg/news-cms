@extends('admin.layouts.master')

@section('title', 'Danh mục')

@section('page-title', 'Quản lý danh mục')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>
            <i class="fa-solid fa-folder-tree me-1"></i>
            Danh sách danh mục
        </strong>

        <a href="{{ route('categories.create') }}"
           class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i>
            Thêm mới
        </a>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="70">ID</th>
                        <th>Tên danh mục</th>
                        <th width="220">Danh mục cha</th>
                        <th width="220">Slug</th>
                        <th width="110">Thứ tự</th>
                        <th width="120">Trạng thái</th>
                        <th width="160">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categories as $category)

                        <tr>
                            <td class="text-center">
                                {{ $category->id }}
                            </td>

                            <td>
                                <strong>
                                    {{ $category->name }}
                                </strong>
                            </td>

                            <td>
                                @if($category->parent)
                                    <span class="badge bg-info text-dark">
                                        {{ $category->parent->name }}
                                    </span>
                                @else
                                    <span class="text-muted">
                                        Danh mục cha
                                    </span>
                                @endif
                            </td>

                            <td>
                                <code>
                                    {{ $category->slug }}
                                </code>
                            </td>

                            <td class="text-center">
                                {{ $category->sort_order ?? 0 }}
                            </td>

                            <td>
                                @if($category->status)
                                    <span class="badge bg-success">
                                        Hiển thị
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Ẩn
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm">

                                    <a href="{{ route('categories.edit', $category->id) }}"
                                       class="btn btn-warning"
                                       title="Sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form action="{{ route('categories.destroy', $category->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger"
                                                title="Xóa"
                                                onclick="return confirm('Xóa danh mục này?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7"
                                class="text-center text-muted py-4">
                                <i class="fa-regular fa-folder-open fa-2x d-block mb-2"></i>
                                Chưa có danh mục nào.
                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($categories, 'links'))
            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        @endif

    </div>
</div>

@endsection