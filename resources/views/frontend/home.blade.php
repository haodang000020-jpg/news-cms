@extends('frontend.layouts.master')

@section('title', 'Trang chủ - Trang thông tin điện tử')

@section('meta_description', 'Cập nhật tin tức, hoạt động, thông báo và văn bản mới nhất.')

@section('content')

@php
    $featured = $featuredPosts->first();

    $smallPosts = $featuredPosts->skip(1)->take(4);
@endphp

<div class="home-grid">

    <div>

        {{-- TIN NỔI BẬT --}}
        <div class="section-box">
            <div class="section-title">
                Tin nổi bật
            </div>

            <div class="section-body">

                @if($featured)

                    <div class="featured-news">

                        <div class="featured-main">

                            @if($featured->featured_image)
                                <a href="{{ route('frontend.posts.show', $featured->slug) }}">
                                    <img src="{{ asset('storage/' . $featured->featured_image) }}"
                                         alt="{{ $featured->title }}">
                                </a>
                            @else
                                <a href="{{ route('frontend.posts.show', $featured->slug) }}">
                                    <img src="{{ asset('images/default-og.jpg') }}"
                                         alt="{{ $featured->title }}">
                                </a>
                            @endif

                            <h2>
                                <a href="{{ route('frontend.posts.show', $featured->slug) }}">
                                    {{ $featured->title }}
                                </a>
                            </h2>

                            <p>
                                {{ \Illuminate\Support\Str::limit(strip_tags($featured->summary), 180) }}
                            </p>

                        </div>

                        <div class="featured-list">

                            @foreach($smallPosts as $post)

                                <div class="item">

                                    @if($post->featured_image)
                                        <a href="{{ route('frontend.posts.show', $post->slug) }}">
                                            <img src="{{ asset('storage/' . $post->featured_image) }}"
                                                 alt="{{ $post->title }}">
                                        </a>
                                    @else
                                        <a href="{{ route('frontend.posts.show', $post->slug) }}">
                                            <img src="{{ asset('images/default-og.jpg') }}"
                                                 alt="{{ $post->title }}">
                                        </a>
                                    @endif

                                    <h3>
                                        <a href="{{ route('frontend.posts.show', $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h3>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @else

                    <p>Chưa có bài viết.</p>

                @endif

            </div>
        </div>

        {{-- BANNER DƯỚI TIN NỔI BẬT --}}
        @if($homeBanners->count() > 0)

            <div class="home-banners">

                @foreach($homeBanners as $banner)

                    <a href="{{ $banner->link ?: '#' }}"
                       target="{{ $banner->link ? '_blank' : '_self' }}">

                        <img src="{{ asset('storage/' . $banner->image) }}"
                             alt="{{ $banner->title }}">

                    </a>

                @endforeach

            </div>

        @endif

        {{-- CÁC KHỐI DANH MỤC TRANG CHỦ --}}
        <div class="home-category-grid">

            @foreach($leftCategories as $category)

                @php
                    $categoryIds = collect([$category->id])
                        ->merge($category->children->pluck('id') ?? collect())
                        ->toArray();

                    $categoryPosts = \App\Models\Post::with(['categories', 'author'])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) use ($categoryIds) {
                            $q->whereIn('categories.id', $categoryIds);
                        })
                        ->latest('published_at')
                        ->take(5)
                        ->get();

                    $mainCategoryPost = $categoryPosts->first();

                    $listCategoryPosts = $categoryPosts->skip(1);
                @endphp

                <div class="home-category-card">

                    <div class="home-category-title">
                        {{ $category->name }}
                    </div>

                    <div class="home-category-body">

                        @if($mainCategoryPost)

                            <div class="home-category-featured">

                                @if($mainCategoryPost->featured_image)
                                    <a href="{{ route('frontend.posts.show', $mainCategoryPost->slug) }}">
                                        <img src="{{ asset('storage/' . $mainCategoryPost->featured_image) }}"
                                             alt="{{ $mainCategoryPost->title }}">
                                    </a>
                                @else
                                    <a href="{{ route('frontend.posts.show', $mainCategoryPost->slug) }}">
                                        <img src="{{ asset('images/default-og.jpg') }}"
                                             alt="{{ $mainCategoryPost->title }}">
                                    </a>
                                @endif

                                <h3>
                                    <a href="{{ route('frontend.posts.show', $mainCategoryPost->slug) }}">
                                        {{ $mainCategoryPost->title }}
                                    </a>
                                </h3>

                            </div>

                            <ul class="home-category-list">

                                @foreach($listCategoryPosts as $post)

                                    <li>
                                        <a href="{{ route('frontend.posts.show', $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </li>

                                @endforeach

                            </ul>

                        @else

                            <div class="home-category-empty">
                                Đang cập nhật.
                            </div>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    </div>

    {{-- SIDEBAR --}}
    <aside>

        <div class="sidebar-box">
            <div class="sidebar-title">
                Thông báo
            </div>

            <div class="sidebar-body">
                <ul class="news-list">
                    @forelse($documents as $post)
                        <li>
                            <a href="{{ route('frontend.posts.show', $post->slug) }}">
                                {{ $post->title }}
                            </a>
                        </li>
                    @empty
                        <li>Đang cập nhật.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="sidebar-box">
            <div class="sidebar-title">
                Văn bản chỉ đạo điều hành
            </div>

            <div class="sidebar-body">
                @forelse($documents as $post)
                    <div class="document-item">
                        <a href="{{ route('frontend.posts.show', $post->slug) }}">
                            {{ $post->title }}
                        </a>
                    </div>
                @empty
                    <div class="document-item">
                        Đang cập nhật.
                    </div>
                @endforelse
            </div>
        </div>
        {{-- LỊCH CÔNG TÁC --}}
<div class="sidebar-box work-schedule-box">
    <div class="sidebar-title">
        Lịch công tác
    </div>

    <div class="sidebar-body">

        @if(isset($workSchedules) && $workSchedules->count() > 0)

            <div class="work-schedule-list">

                @foreach($workSchedules as $schedule)

                    <div class="work-schedule-item">

                        <div class="work-schedule-date">
                            <i class="fa-regular fa-calendar-days"></i>
                            {{ $schedule->work_date?->format('d/m/Y') }}
                        </div>

                        <div class="work-schedule-time">
                            @if($schedule->start_time)
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                            @endif

                            @if($schedule->end_time)
                                -
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            @endif
                        </div>

                        <div class="work-schedule-title">
                            {{ $schedule->title }}
                        </div>

                        @if($schedule->location)
                            <div class="work-schedule-location">
                                <i class="fa-solid fa-location-dot"></i>
                                {{ $schedule->location }}
                            </div>
                        @endif

                    </div>

                @endforeach

            </div>
            <div class="work-schedule-more">
    <a href="{{ route('frontend.work_schedules.index') }}">
        Xem toàn bộ lịch công tác
        <i class="fa-solid fa-arrow-right"></i>
    </a>
</div>

        @else

            <div class="work-schedule-empty">
                Đang cập nhật lịch công tác.
            </div>

        @endif

    </div>
</div>

     <div class="sidebar-box video-box">
    <div class="sidebar-title">
        Video
    </div>

    <div class="sidebar-body">

        @if($homeVideo)

            <a href="{{ $homeVideo->url }}"
               class="video-thumb"
               target="_blank">

                @if($homeVideo->thumbnail)
                    <img src="{{ asset('storage/' . $homeVideo->thumbnail) }}"
                         alt="{{ $homeVideo->title }}">
                @else
                    <img src="{{ asset('frontend/images/video-thumb.jpg') }}"
                         alt="{{ $homeVideo->title }}">
                @endif

            </a>

            <div class="video-caption">
                {{ $homeVideo->title }}
            </div>

        @else

            <a href="#" class="video-thumb">
                <img src="{{ asset('frontend/images/video-thumb.jpg') }}"
                     alt="Video">
            </a>

            <div class="video-caption">
                Đang cập nhật video.
            </div>

        @endif

    </div>
</div>

        @if($sidebarBanners->count() > 0)

            <div class="sidebar-links">

                @foreach($sidebarBanners as $banner)

                    <a href="{{ $banner->link ?: '#' }}"
                       class="sidebar-link-banner"
                       target="{{ $banner->link ? '_blank' : '_self' }}">

                        <img src="{{ asset('storage/' . $banner->image) }}"
                             alt="{{ $banner->title }}">

                    </a>

                @endforeach

            </div>

        @endif

        <div class="sidebar-box">
            <div class="sidebar-title">
                Liên kết website
            </div>

            <div class="sidebar-body">
            <select id="websiteLinkSelect"
        data-open-link
        style="width:100%; padding:7px;">
    <option value="">
        -- Chọn liên kết --
    </option>

    @foreach($websiteLinks as $link)
        <option value="{{ $link->url }}">
            {{ $link->title }}
        </option>
    @endforeach
</select>


            </div>
        </div>

    </aside>

</div>

@endsection