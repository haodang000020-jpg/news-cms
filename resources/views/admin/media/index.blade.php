@extends('admin.layouts.master')

@section('title', 'Media')

@section('page-title', 'Thư viện Media')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header">
        Upload file mới
    </div>

    <div class="card-body">
        <form action="{{ route('media.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <input type="file"
                       name="files[]"
                       class="form-control"
                       multiple>
            </div>

            <button class="btn btn-primary">
                Upload
            </button>
        </form>
    </div>
</div>

<div class="row">

    @forelse($media as $item)

        <div class="col-md-3 mb-4">
            <div class="card h-100">

                <div style="height:160px; background:#f5f5f5; display:flex; align-items:center; justify-content:center; overflow:hidden;">

                    @if(str_starts_with($item->mime_type, 'image/'))

                        <img src="{{ asset('storage/' . $item->file_path) }}"
                             style="width:100%; height:100%; object-fit:cover;">

                    @else

                        <div class="text-center">
                            <strong>FILE</strong>
                            <br>
                            {{ strtoupper(pathinfo($item->file_name, PATHINFO_EXTENSION)) }}
                        </div>

                    @endif

                </div>

                <div class="card-body">

                    <div class="mb-2">
                        <strong style="font-size:13px;">
                            {{ \Illuminate\Support\Str::limit($item->file_name, 35) }}
                        </strong>
                    </div>

                    <div class="text-muted" style="font-size:12px;">
                        {{ number_format($item->file_size / 1024, 1) }} KB
                    </div>

                    <div class="text-muted" style="font-size:12px;">
                        Người tải: {{ $item->uploader?->name ?? 'Không rõ' }}
                    </div>

                    <input type="text"
                           class="form-control form-control-sm mt-2"
                           value="{{ asset('storage/' . $item->file_path) }}"
                           onclick="this.select(); document.execCommand('copy');">

                </div>

                <div class="card-footer d-flex justify-content-between">

                    <a href="{{ asset('storage/' . $item->file_path) }}"
                       target="_blank"
                       class="btn btn-sm btn-secondary">
                        Xem
                    </a>

                    <form action="{{ route('media.destroy', $item->id) }}"
                          method="POST">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Xóa file này?')">
                            Xóa
                        </button>
                    </form>

                </div>

            </div>
        </div>

    @empty

        <div class="col-12">
            <div class="alert alert-info">
                Chưa có file nào.
            </div>
        </div>

    @endforelse

</div>

{{ $media->links() }}

@endsection