@extends('admin.layouts.master')

@section('title', 'Cài đặt website')

@section('page-title', 'Cài đặt website')

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

@php
    use App\Models\Setting;

    $logo = Setting::getValue('logo');
    $headerBanner = Setting::getValue('header_banner');
@endphp

<form method="POST"
      action="{{ route('settings.update') }}"
      enctype="multipart/form-data">

    @csrf

    <div class="card mb-4">
        <div class="card-header">
            Thông tin chung
        </div>

        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">Tên website</label>

                <input type="text"
                       name="site_name"
                       class="form-control"
                       value="{{ old('site_name', Setting::getValue('site_name', 'Trang thông tin điện tử')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Tên cơ quan</label>

                <input type="text"
                       name="agency_name"
                       class="form-control"
                       value="{{ old('agency_name', Setting::getValue('agency_name', 'Ủy ban nhân dân xã Vĩnh Bình')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Dòng phụ / mô tả</label>

                <input type="text"
                       name="site_subtitle"
                       class="form-control"
                       value="{{ old('site_subtitle', Setting::getValue('site_subtitle', 'Phòng Kinh tế, Văn hóa và Xã hội')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email', Setting::getValue('email')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Điện thoại</label>

                <input type="text"
                       name="phone"
                       class="form-control"
                       value="{{ old('phone', Setting::getValue('phone')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>

                <input type="text"
                       name="address"
                       class="form-control"
                       value="{{ old('address', Setting::getValue('address')) }}">
            </div>

        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            Logo và Banner
        </div>

        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">Logo</label>

                @if($logo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $logo) }}"
                             style="max-height:90px;">
                    </div>
                @endif

                <input type="file"
                       name="logo"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Banner đầu trang</label>

                @if($headerBanner)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $headerBanner) }}"
                             style="max-width:100%; max-height:180px;">
                    </div>
                @endif

                <input type="file"
                       name="header_banner"
                       class="form-control">
            </div>

        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            Footer
        </div>

        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Nội dung footer</label>

                <textarea name="footer_text"
                          class="form-control"
                          rows="5">{{ old('footer_text', Setting::getValue('footer_text')) }}</textarea>
            </div>
        </div>
    </div>

    <button class="btn btn-primary">
        Lưu cài đặt
    </button>

</form>

@endsection