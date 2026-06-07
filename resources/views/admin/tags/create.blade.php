@extends('admin.layouts.master')

@section('title', 'Thêm tag')

@section('page-title', 'Thêm tag')

@section('content')

<div class="card">
    <div class="card-header">
        <strong>Thông tin tag</strong>
    </div>

    <div class="card-body">

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
              action="{{ route('tags.store') }}">

            @csrf

            <div class="mb-3">
                <label class="form-label">Tên tag</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name') }}">
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

            <a href="{{ route('tags.index') }}" class="btn btn-secondary">
                Quay lại
            </a>

        </form>

    </div>
</div>

@endsection