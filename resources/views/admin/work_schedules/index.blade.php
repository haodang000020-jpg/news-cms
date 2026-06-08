@extends('admin.layouts.master')

@section('title', 'Lịch công tác')

@section('page-title', 'Quản lý lịch công tác')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fa-solid fa-calendar-days me-1"></i>
            Danh sách lịch công tác
        </h3>

        <a href="{{ route('work-schedules.create') }}"
           class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i>
            Thêm lịch công tác
        </a>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="GET"
              action="{{ route('work-schedules.index') }}"
              class="filter-box mb-4">

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">Từ ngày</label>
                    <input type="date"
                           name="from_date"
                           class="form-control"
                           value="{{ request('from_date') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Đến ngày</label>
                    <input type="date"
                           name="to_date"
                           class="form-control"
                           value="{{ request('to_date') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Từ khóa</label>
                    <input type="text"
                           name="keyword"
                           class="form-control"
                           value="{{ request('keyword') }}"
                           placeholder="Nội dung, địa điểm, chủ trì...">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-dark w-100">
                        <i class="fa-solid fa-filter"></i>
                        Lọc
                    </button>
                </div>

            </div>

        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="60">ID</th>
                        <th width="120">Ngày</th>
                        <th width="120">Thời gian</th>
                        <th>Nội dung</th>
                        <th width="180">Địa điểm</th>
                        <th width="160">Chủ trì</th>
                        <th width="100">Trạng thái</th>
                        <th width="140">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($schedules as $schedule)

                        <tr>
                            <td class="text-center">
                                {{ $schedule->id }}
                            </td>

                            <td>
                                {{ $schedule->work_date?->format('d/m/Y') }}
                            </td>

                            <td>
                                @if($schedule->start_time)
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                @endif

                                @if($schedule->end_time)
                                    -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                @endif
                            </td>

                            <td>
                                <strong>{{ $schedule->title }}</strong>

                                @if($schedule->participants)
                                    <div class="text-muted small mt-1">
                                        Thành phần: {{ \Illuminate\Support\Str::limit($schedule->participants, 100) }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                {{ $schedule->location }}
                            </td>

                            <td>
                                {{ $schedule->chairperson }}
                            </td>

                            <td>
                                @if($schedule->status)
                                    <span class="badge bg-success">Hiển thị</span>
                                @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm">

                                    <a href="{{ route('work-schedules.edit', $schedule->id) }}"
                                       class="btn btn-warning">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form action="{{ route('work-schedules.destroy', $schedule->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger"
                                                onclick="return confirm('Xóa lịch công tác này?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Chưa có lịch công tác.
                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $schedules->links() }}
        </div>

    </div>
</div>

@endsection