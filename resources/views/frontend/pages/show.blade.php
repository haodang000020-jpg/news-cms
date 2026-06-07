@extends('frontend.layouts.master')

@section('title', $page->seo_title ?: $page->title)

@section('meta_description', $page->seo_description ?: \Illuminate\Support\Str::limit(strip_tags($page->summary ?: $page->content), 160))

@section('content')

<div class="detail-layout">

    <article class="detail-main">

        <div class="detail-category-bar">
            {{ $page->title }}
        </div>

        <h1 class="detail-title">
            {{ $page->title }}
        </h1>

        @if($page->summary)
            <div class="detail-summary">
                {{ $page->summary }}
            </div>
        @endif

        @if($page->featured_image)
            <div class="detail-image">
                <img src="{{ asset('storage/' . $page->featured_image) }}"
                     alt="{{ $page->title }}">
            </div>
        @endif

        <div class="detail-content">
            {!! $page->content !!}
        </div>

    </article>

    <aside class="detail-sidebar">

        <div class="right-box">
            <div class="right-box-title blue-title">
                Tin mới
            </div>

            <div class="right-box-body">
                <ul class="right-news-list">
                    @foreach(\App\Models\Post::where('status', 'published')->latest('published_at')->take(6)->get() as $item)
                        <li>
                            <a href="{{ route('frontend.posts.show', $item->slug) }}">
                                {{ $item->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </aside>

</div>

@endsection