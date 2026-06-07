@php
    use App\Models\Setting;

    $logo = Setting::getValue('logo');
    $headerBanner = Setting::getValue('header_banner');

    $agencyName = Setting::getValue('agency_name', 'Ủy ban nhân dân xã Vĩnh Bình');
    $siteName = Setting::getValue('site_name', 'Trang thông tin điện tử');
    $siteSubtitle = Setting::getValue('site_subtitle', 'Phòng Kinh tế, Văn hóa và Xã hội');
    $email = Setting::getValue('email', 'bbt@angiang.gov.vn');
@endphp

<header class="header">

    @if($headerBanner)
        <img src="{{ asset('storage/' . $headerBanner) }}"
             alt="{{ $siteName }}"
             class="header-banner-image">
    @else
        <div class="header-inner">

            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}"
                     class="logo"
                     alt="Logo">
            @endif

            <div class="site-title">
                <small>{{ $agencyName }}</small>
                <h1>{{ $siteName }}</h1>
                <small>{{ $siteSubtitle }}</small>
            </div>

        </div>
    @endif

</header>

<div class="hotline-bar">
    <div>
        {{ now()->format('d/m/Y') }}
    </div>

    <div>
        Email: {{ $email }}
    </div>
</div>

<div class="hot-news">
    <div class="hot-news-text">
    <strong>TIN NỔI BẬT:</strong>

    <div class="hot-news-marquee">
        <div class="hot-news-marquee-inner">

            @php
                $hotPosts = \App\Models\Post::where('status', 'published')
                    ->latest('published_at')
                    ->take(5)
                    ->get();
            @endphp

            @forelse($hotPosts as $item)
                <a href="{{ route('frontend.posts.show', $item->slug) }}">
                    {{ $item->title }}
                </a>

                <span class="marquee-separator">★</span>
            @empty
                <span>Đang cập nhật tin nổi bật</span>
            @endforelse

        </div>
    </div>
</div>

    <form class="hot-news-search"
          action="{{ route('frontend.search') }}"
          method="GET">

        <input type="text"
               name="q"
               value="{{ request('q') }}"
               placeholder="Nhập từ khóa tìm kiếm...">

        <button type="submit">
            Tìm kiếm
        </button>
    </form>
</div>