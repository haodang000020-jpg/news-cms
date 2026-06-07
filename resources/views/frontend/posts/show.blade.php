@extends('frontend.layouts.master')

@section('title', $post->title)

@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($post->summary), 160))

@section('content')

<div class="breadcrumb-box">
    <a href="{{ route('home') }}">
        Trang chủ
    </a>

    <span>/</span>

    @if($post->categories->count() > 0)
        <a href="{{ route('frontend.categories.show', $post->categories->first()->slug) }}">
            {{ $post->categories->first()->name }}
        </a>

        <span>/</span>
    @endif

    <span>
        {{ \Illuminate\Support\Str::limit($post->title, 70) }}
    </span>
</div>

<div class="detail-layout">

    <article class="detail-main">

        @if($post->categories->count() > 0)
            <div class="detail-category-bar">
                {{ $post->categories->first()->name }}
            </div>
        @endif

        <h1 class="detail-title">
            {{ $post->title }}
        </h1>

        <div class="detail-meta">

            @if($post->published_at)
                <span>
                    <i class="fa-regular fa-clock"></i>
                    {{ $post->published_at->format('d/m/Y H:i') }}
                </span>
            @endif

            <span>
                <i class="fa-regular fa-user"></i>
                {{ $post->author?->name ?? 'Ban biên tập' }}
            </span>

            <span>
                <i class="fa-regular fa-eye"></i>
                {{ number_format($post->views ?? 0) }} lượt xem
            </span>

        </div>

        <div class="detail-tools">
    <button type="button"
            onclick="window.print()">
        <i class="fa-solid fa-print"></i>
        In bài viết
    </button>

    <button type="button"
            onclick="navigator.clipboard.writeText(window.location.href); alert('Đã sao chép liên kết bài viết');">
        <i class="fa-solid fa-link"></i>
        Sao chép liên kết
    </button>
</div>


        @if($post->summary)
            <div class="detail-summary">
                {{ $post->summary }}
            </div>
        @endif

        <div id="articleContent" class="detail-content">
            {!! $post->content !!}
        </div>

        <div class="article-source">
    <strong>Nguồn tin:</strong>
    {{ $post->author?->name ?? 'Ban biên tập' }}
</div>

        @if($post->attachments->count() > 0)

            <div class="post-attachments">

                <div class="post-attachments-title">
                    <i class="fa-solid fa-paperclip"></i>
                    Tệp đính kèm
                </div>

                <div class="post-attachments-list">

                    @foreach($post->attachments as $attachment)

                        <div class="post-attachment-item">

                            <div class="attachment-info">
                                <i class="fa-solid fa-file-lines"></i>

                                <div>
                                    <a href="{{ asset('storage/' . $attachment->file_path) }}"
                                       target="_blank">
                                        {{ $attachment->file_name }}
                                    </a>

                                    <div class="attachment-meta">
                                        {{ strtoupper($attachment->file_type) }}
                                        -
                                        {{ $attachment->file_size_text }}
                                    </div>
                                </div>
                            </div>

                            <a href="{{ asset('storage/' . $attachment->file_path) }}"
                               class="attachment-download"
                               target="_blank"
                               download>
                                <i class="fa-solid fa-download"></i>
                                Tải xuống
                            </a>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif

        @if($post->tags->count() > 0)

            <div class="post-tags">
                <strong>Từ khóa:</strong>

                @foreach($post->tags as $tag)
                    <a href="{{ route('frontend.tags.show', $tag->slug) }}">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>

        @endif

        @if(isset($relatedPosts) && $relatedPosts->count() > 0)

            <div class="related-posts">

                <div class="related-title">
                    Bài viết liên quan
                </div>

                <div class="related-grid">

                    @foreach($relatedPosts as $item)

                        <div class="related-item">

                            @if($item->featured_image)
                                <a href="{{ route('frontend.posts.show', $item->slug) }}">
                                    <img src="{{ asset('storage/' . $item->featured_image) }}"
                                         alt="{{ $item->title }}">
                                </a>
                            @endif

                            <h3>
                                <a href="{{ route('frontend.posts.show', $item->slug) }}">
                                    {{ $item->title }}
                                </a>
                            </h3>

                            @if($item->published_at)
                                <div class="related-date">
                                    {{ $item->published_at->format('d/m/Y') }}
                                </div>
                            @endif

                        </div>

                    @endforeach

                </div>

            </div>

        @endif

    </article>

   <aside class="detail-sidebar">

    <div class="right-box">
        <div class="right-box-title blue-title">
            Tin mới
        </div>

        <div class="right-box-body">
            <ul class="right-news-list">
                @forelse($latestPosts as $item)
                    <li>
                        <a href="{{ route('frontend.posts.show', $item->slug) }}">
                            {{ $item->title }}
                        </a>
                    </li>
                @empty
                    <li>Đang cập nhật.</li>
                @endforelse
            </ul>
        </div>
    </div>

    @if(isset($sidebarBanners) && $sidebarBanners->count() > 0)

        <div class="detail-sidebar-banners">

            @foreach($sidebarBanners as $banner)

                <a href="{{ $banner->link ?: '#' }}"
                   class="detail-sidebar-banner"
                   target="{{ $banner->link ? '_blank' : '_self' }}">

                    <img src="{{ asset('storage/' . $banner->image) }}"
                         alt="{{ $banner->title }}">

                </a>

            @endforeach

        </div>

    @endif

</aside>

</div>

@endsection

