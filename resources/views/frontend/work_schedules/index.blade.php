@extends('frontend.layouts.master')

@section('title', 'Lịch công tác - Trang thông tin điện tử')

@section('meta_description', 'Lịch công tác tuần của Phòng Kinh tế, Văn hóa và Xã hội xã Vĩnh Bình.')

@section('content')

<div class="breadcrumb-box">
    <a href="{{ route('home') }}">
        Trang chủ
    </a>

    <span>/</span>

    <span>
        Lịch công tác
    </span>
</div>

<div class="schedule-page">

    <div class="schedule-page-header">
        <div>
            <h1>
                Lịch công tác
            </h1>

            <p>
                Từ ngày {{ $fromDate->format('d/m/Y') }}
                đến ngày {{ $toDate->format('d/m/Y') }}
            </p>
        </div>
    </div>

    <form method="GET"
          action="{{ route('frontend.work_schedules.index') }}"
          class="schedule-filter">

        <div class="schedule-filter-grid">

            <div>
                <label>
                    Từ ngày
                </label>

                <input type="date"
                       name="from_date"
                       value="{{ request('from_date', $fromDate->format('Y-m-d')) }}">
            </div>

            <div>
                <label>
                    Đến ngày
                </label>

                <input type="date"
                       name="to_date"
                       value="{{ request('to_date', $toDate->format('Y-m-d')) }}">
            </div>

            <div class="schedule-filter-action">
                <button>
                    <i class="fa-solid fa-filter"></i>
                    Xem lịch
                </button>
            </div>

        </div>

    </form>

    <div class="schedule-list-page">

        @forelse($schedules as $date => $items)

            @php
                $dateObj = \Carbon\Carbon::parse($date);

                $dayNames = [
                    1 => 'Thứ hai',
                    2 => 'Thứ ba',
                    3 => 'Thứ tư',
                    4 => 'Thứ năm',
                    5 => 'Thứ sáu',
                    6 => 'Thứ bảy',
                    0 => 'Chủ nhật',
                ];
            @endphp

            <div class="schedule-day-box">

                <div class="schedule-day-title">
                    <i class="fa-regular fa-calendar-days"></i>
                    {{ $dayNames[$dateObj->dayOfWeek] }}
                    -
                    {{ $dateObj->format('d/m/Y') }}
                </div>

                <div class="schedule-day-body">

                    @foreach($items as $schedule)

                        <div class="schedule-row">

                            <div class="schedule-time-col">
                                @if($schedule->start_time)
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                @else
                                    Cả ngày
                                @endif

                                @if($schedule->end_time)
                                    <br>
                                    <span>
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </span>
                                @endif
                            </div>

                            <div class="schedule-content-col">

                                <h3>
                                    {{ $schedule->title }}
                                </h3>

                                @if($schedule->location)
                                    <div class="schedule-meta">
                                        <i class="fa-solid fa-location-dot"></i>
                                        <strong>Địa điểm:</strong>
                                        {{ $schedule->location }}
                                    </div>
                                @endif

                                @if($schedule->chairperson)
                                    <div class="schedule-meta">
                                        <i class="fa-solid fa-user-tie"></i>
                                        <strong>Chủ trì:</strong>
                                        {{ $schedule->chairperson }}
                                    </div>
                                @endif

                                @if($schedule->participants)
                                    <div class="schedule-meta">
                                        <i class="fa-solid fa-users"></i>
                                        <strong>Thành phần:</strong>
                                        {{ $schedule->participants }}
                                    </div>
                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        @empty

            <div class="schedule-empty-page">
                <i class="fa-regular fa-calendar-xmark"></i>
                Chưa có lịch công tác trong khoảng thời gian này.
            </div>

        @endforelse

    </div>

</div>

@endsection