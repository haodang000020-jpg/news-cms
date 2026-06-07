@extends('admin.layouts.master')

@section('title', 'Sửa danh mục')

@section('page-title', 'Sửa danh mục')

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
      action="{{ route('categories.update', $category->id) }}">

    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Tên danh mục</label>

        <input type="text"
               name="name"
               class="form-control"
               value="{{ old('name', $category->name) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Danh mục cha</label>

        <select name="parent_id" class="form-select">

            <option value="">-- Không có --</option>

            @foreach($parents as $parent)

                <option value="{{ $parent->id }}"
                    {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                    {{ $parent->name }}
                </option>

            @endforeach

        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Thứ tự</label>

        <input type="number"
               name="sort_order"
               class="form-control"
               value="{{ old('sort_order', $category->sort_order) }}">
    </div>

    <button class="btn btn-success">
        Cập nhật
    </button>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
        Quay lại
    </a>

</form>

@endsection