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

<form method="POST" action="{{ $action }}">
    @csrf

    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="card">
        <div class="card-header">
            <strong>
                <i class="fa-solid fa-calendar-days me-1"></i>
                Thông tin lịch công tác
            </strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Ngày công tác <span class="text-danger">*</span>
                    </label>

                    <input type="date"
                           name="work_date"
                           class="form-control"
                           value="{{ old('work_date', optional($schedule?->work_date)->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Giờ bắt đầu
                    </label>

                    <input type="time"
                           name="start_time"
                           class="form-control"
                           value="{{ old('start_time', $schedule?->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Giờ kết thúc
                    </label>

                    <input type="time"
                           name="end_time"
                           class="form-control"
                           value="{{ old('end_time', $schedule?->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}">
                </div>

            </div>

            <div class="mb-3">
                <label class="form-label">
                    Nội dung công tác <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="title"
                       class="form-control"
                       value="{{ old('title', $schedule?->title) }}"
                       placeholder="Ví dụ: Họp giao ban UBND xã">
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Địa điểm
                    </label>

                    <input type="text"
                           name="location"
                           class="form-control"
                           value="{{ old('location', $schedule?->location) }}"
                           placeholder="Ví dụ: Hội trường UBND xã">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Chủ trì
                    </label>

                    <input type="text"
                           name="chairperson"
                           class="form-control"
                           value="{{ old('chairperson', $schedule?->chairperson) }}"
                           placeholder="Ví dụ: Chủ tịch UBND xã">
                </div>

            </div>

            <div class="mb-3">
                <label class="form-label">
                    Thành phần tham dự
                </label>

                <textarea name="participants"
                          class="form-control"
                          rows="4"
                          placeholder="Ví dụ: Lãnh đạo UBND xã, công chức chuyên môn, trưởng các ấp...">{{ old('participants', $schedule?->participants) }}</textarea>
            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Thứ tự hiển thị
                    </label>

                    <input type="number"
                           name="sort_order"
                           class="form-control"
                           value="{{ old('sort_order', $schedule?->sort_order ?? 0) }}">
                </div>

                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox"
                               name="status"
                               value="1"
                               class="form-check-input"
                               id="status"
                               {{ old('status', $schedule?->status ?? true) ? 'checked' : '' }}>

                        <label class="form-check-label" for="status">
                            Hiển thị
                        </label>
                    </div>
                </div>

            </div>

            <button class="btn btn-success">
                <i class="fa-solid fa-floppy-disk"></i>
                Lưu lịch công tác
            </button>

            <a href="{{ route('work-schedules.index') }}"
               class="btn btn-secondary">
                Quay lại
            </a>

        </div>
    </div>
</form>