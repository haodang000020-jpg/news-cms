@extends('admin.layouts.master')

@section('title', 'Thông báo')

@section('page-title', 'Thông báo')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>
            <i class="fa-solid fa-bell me-1"></i>
            Danh sách thông báo
        </strong>

        <form action="{{ route('notifications.mark_all_read') }}"
              method="POST">
            @csrf

            <button class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-check-double"></i>
                Đánh dấu tất cả đã đọc
            </button>
        </form>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="list-group">

            @forelse($notifications as $notification)

                <a href="{{ route('notifications.read', $notification->id) }}"
                   class="list-group-item list-group-item-action {{ !$notification->is_read ? 'fw-bold bg-light' : '' }}">

                    <div class="d-flex justify-content-between">
                        <div>
                            <i class="fa-solid fa-bell me-1 text-warning"></i>
                            {{ $notification->title }}
                        </div>

                        <small class="text-muted">
                            {{ $notification->created_at?->format('d/m/Y H:i') }}
                        </small>
                    </div>

                    @if($notification->message)
                        <div class="mt-1 text-muted">
                            {{ $notification->message }}
                        </div>
                    @endif

                    @if(!$notification->is_read)
                        <span class="badge bg-primary mt-2">
                            Chưa đọc
                        </span>
                    @endif

                </a>

            @empty

                <div class="text-center text-muted py-4">
                    <i class="fa-regular fa-bell-slash fa-2x d-block mb-2"></i>
                    Chưa có thông báo nào.
                </div>

            @endforelse

        </div>

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>

    </div>
</div>

@endsection