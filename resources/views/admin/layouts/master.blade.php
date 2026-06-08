<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Google Font --}}
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=fallback">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

    {{-- Admin custom --}}
    <style>
        body {
            font-family: "Roboto", Arial, sans-serif;
            background: #f4f6f9;
            font-size: 14px;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 250px;
            background: #343a40;
            color: #c2c7d0;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .admin-brand {
            height: 57px;
            display: flex;
            align-items: center;
            padding: 0 18px;
            border-bottom: 1px solid #4b545c;
            color: #fff;
            font-size: 20px;
            font-weight: 600;
        }

        .admin-brand i {
            margin-right: 10px;
            color: #17a2b8;
        }

        .admin-user {
            padding: 15px 18px;
            border-bottom: 1px solid #4b545c;
            color: #fff;
        }

        .admin-user small {
            display: block;
            color: #adb5bd;
            margin-top: 3px;
        }

        .admin-menu {
            padding: 10px 8px;
        }

        .admin-menu .nav-link {
            color: #c2c7d0;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 3px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-menu .nav-link:hover {
            background: rgba(255,255,255,.1);
            color: #fff;
        }

        .admin-menu .nav-link.active {
            background: #007bff;
            color: #fff;
        }

        .admin-content {
            flex: 1;
            min-width: 0;
        }

        .admin-navbar {
            height: 57px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .content-header {
            padding: 20px 24px 10px;
        }

        .content-header h1 {
            font-size: 24px;
            margin: 0;
            font-weight: 500;
        }

        .content-body {
            padding: 0 24px 24px;
        }

        .card {
            border: 0;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        }

        .table {
            background: #fff;
        }

        .btn {
            border-radius: 4px;
        }

        .badge {
            font-weight: 500;
        }

        .filter-box {
            background: #f8f9fa;
            border: 1px solid #e3e6ea;
            border-radius: 6px;
            padding: 15px;
        }

        .admin-post-table th {
            font-size: 13px;
            white-space: nowrap;
        }

        .admin-post-table td {
            font-size: 13.5px;
            vertical-align: middle;
        }

        .post-thumb {
            width: 86px;
            height: 58px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }

        .post-thumb-empty {
            width: 86px;
            height: 58px;
            border-radius: 4px;
            border: 1px dashed #adb5bd;
            background: #f1f3f5;
            color: #868e96;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .post-title {
            font-weight: 600;
            color: #212529;
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .post-slug {
            color: #6c757d;
            font-size: 12.5px;
            margin-bottom: 4px;
            word-break: break-word;
        }

        .post-meta {
            color: #6c757d;
            font-size: 12.5px;
        }

        .btn-group form {
            display: inline-block;
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .btn-group form:last-child .btn,
        .btn-group > .btn:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        .post-image-preview {
            width: 100%;
            min-height: 180px;
            border: 1px dashed #adb5bd;
            border-radius: 6px;
            background: #f8f9fa;
            color: #868e96;

            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;

            overflow: hidden;
        }

        .post-image-preview i {
            font-size: 36px;
            margin-bottom: 8px;
        }

        .post-image-preview img {
            width: 100%;
            height: auto;
            display: block;
        }

        .category-check-list {
            max-height: 280px;
            overflow-y: auto;
        }

        .ck-editor__editable {
            min-height: 380px;
        }

        .dashboard-card {
            min-height: 105px;
            border-radius: 8px;
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        }

        .dashboard-number {
            font-size: 30px;
            font-weight: 700;
            line-height: 1;
        }

        .dashboard-label {
            margin-top: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .dashboard-icon {
            font-size: 38px;
            opacity: 0.45;
        }

        .attachment-admin-item {
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            background: #f8f9fa;
        }

        .attachment-admin-item a {
            font-weight: 500;
            color: #0d6efd;
            word-break: break-word;
        }

        .notification-bell {
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -7px;
            right: -7px;

            min-width: 18px;
            height: 18px;
            padding: 0 5px;

            background: #dc3545;
            color: #ffffff;

            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;

            display: none;
            align-items: center;
            justify-content: center;

            box-shadow: 0 0 0 2px #ffffff;
        }

        .realtime-toast {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 99999;

            background: #198754;
            color: #ffffff;

            padding: 12px 16px;
            border-radius: 6px;

            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            font-weight: 600;

            display: none;
            max-width: 320px;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                width: 220px;
            }

            .content-body,
            .content-header {
                padding-left: 15px;
                padding-right: 15px;
            }

            .admin-navbar {
                height: auto;
                min-height: 57px;
                flex-wrap: wrap;
                gap: 10px;
                padding: 10px 15px;
            }

            .realtime-toast {
                right: 12px;
                left: 12px;
                max-width: none;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="admin-wrapper">

    @include('admin.layouts.sidebar')

    <div class="admin-content">

        @include('admin.layouts.navbar')

        <div class="content-header">
            <h1>@yield('page-title')</h1>
        </div>

        <div class="content-body">
            @yield('content')
        </div>

    </div>

</div>

<div id="realtimeToast" class="realtime-toast"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>