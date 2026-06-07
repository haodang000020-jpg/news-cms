<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <title>@yield('title', 'Trang thông tin điện tử')</title>

    <meta name="description"
          content="@yield('meta_description', 'Trang thông tin điện tử')">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- CSS frontend --}}
    <link rel="stylesheet"
          href="{{ asset('frontend/css/gov-style.css') }}">

    @stack('styles')
</head>

<body>

<div class="site-wrapper">

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="site-container top-bar-inner">

            <div class="top-bar-left">
                <i class="fa-solid fa-calendar-days"></i>
                {{ now()->format('d/m/Y') }}
            </div>

            <div class="top-bar-right">
                <a href="{{ route('home') }}">
                    Trang chủ
                </a>

                <span>|</span>

                <a href="/sitemap.xml" target="_blank">
                    Sitemap
                </a>

                <span>|</span>

                <a href="{{ route('login') }}">
                    Đăng nhập
                </a>
            </div>

        </div>
    </div>

    {{-- HEADER --}}
    <header class="site-header">
        <div class="site-container header-inner">

            <div class="header-logo">
                <img src="{{ asset('frontend/images/logo.png') }}"
                     alt="Logo">
            </div>

            

        </div>
    </header>

    {{-- MAIN MENU --}}
    <nav class="main-menu">
        <div class="site-container">

            <ul class="main-menu-list">

                <li>
                    <a href="{{ route('home') }}">
                        <i class="fa-solid fa-house"></i>
                    </a>
                </li>

                @php
                    $menuCategories = \App\Models\Category::whereNull('parent_id')
                        ->where('status', true)
                        ->orderBy('sort_order')
                        ->take(7)
                        ->get();
                @endphp

                @foreach($menuCategories as $category)
                    <li>
                        <a href="{{ route('frontend.categories.show', $category->slug) }}">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach

            </ul>

        </div>
    </nav>

    {{-- SEARCH / BREAKING BAR --}}
    <div class="headline-bar">
        <div class="site-container headline-inner">

            <div class="headline-label">
                <i class="fa-solid fa-bullhorn"></i>
                Tin nổi bật
            </div>

            <div class="headline-marquee">
                @php
                    $runningPosts = \App\Models\Post::where('status', 'published')
                        ->latest('published_at')
                        ->take(6)
                        ->get();
                @endphp

                <marquee behavior="scroll" direction="left" scrollamount="4">
                    @foreach($runningPosts as $post)
                        <a href="{{ route('frontend.posts.show', $post->slug) }}">
                            {{ $post->title }}
                        </a>
                        &nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;&nbsp;
                    @endforeach
                </marquee>
            </div>

            <form action="{{ route('frontend.search') }}"
                  method="GET"
                  class="headline-search">

                <input type="text"
                       name="keyword"
                       value="{{ request('keyword') }}"
                       placeholder="Tìm kiếm...">

                <button>
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

            </form>

        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <main class="site-main">
        <div class="site-container">
            @yield('content')
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="site-footer">

        <div class="site-container footer-inner">

            <div class="footer-title">
                PHÒNG KINH TẾ, VĂN HÓA VÀ XÃ HỘI XÃ VĨNH BÌNH
            </div>

            <div class="footer-info">
                <p>
                    <strong>Địa chỉ:</strong>
                    Xã Vĩnh Bình, tỉnh An Giang
                </p>

                <p>
                    <strong>Điện thoại:</strong>
                    Đang cập nhật
                </p>

                <p>
                    <strong>Email:</strong>
                    Đang cập nhật
                </p>

                <p>
                    Chịu trách nhiệm nội dung: Ban biên tập Trang thông tin điện tử.
                </p>
            </div>

        </div>

    </footer>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selects = document.querySelectorAll('[data-open-link]');

        selects.forEach(function (select) {
            select.addEventListener('change', function () {
                if (this.value) {
                    window.open(this.value, '_blank');
                    this.value = '';
                }
            });
        });
    });
</script>

@stack('scripts')

</body>
</html>