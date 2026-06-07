@extends('admin.layout')

@section('content')

<h3>Thêm danh mục</h3>

<form method="POST"
      action="{{ route('categories.store') }}">

    @csrf

    <div class="mb-3">

        <label>Tên danh mục</label>

        <input type="text"
               name="name"
               class="form-control">

    </div>

    <div class="mb-3">

        <label>Danh mục cha</label>

        <select name="parent_id"
                class="form-select">

            <option value="">
                -- Không có --
            </option>

            @foreach($parents as $parent)

                <option value="{{ $parent->id }}">
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
           value="{{ old('sort_order', 0) }}">
</div>
    <button class="btn btn-primary">
        Lưu
    </button>

</form>

@endsection