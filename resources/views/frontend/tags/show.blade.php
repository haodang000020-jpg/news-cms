@extends('frontend.layouts.master')

@section('title', 'Tag: ' . $tag->name)

@section('meta_description', 'Các bài viết thuộc tag ' . $tag->name)

@section('content')

<div class="detail-layout">

    <article class="detail-main">

        <div class="detail-category-bar">
            Tag: {{ $tag->name }}
        </div>

        <h1 class="detail-title">
            Các bài viết thuộc tag "{{ $tag->name }}"
        </h1>

        <ul class="news-list">

            @forelse($posts as $post)

                <li>
                    <a href="{{ route('frontend.posts.show', $post->slug) }}">
                        {{ $post->title }}
                    </a>

                    @if($post->published_at)
                        <small class="text-muted">
                            - {{ $post->published_at->format('d/m/Y') }}
                        </small>
                    @endif
                </li>

            @empty

                <li>Chưa có bài viết nào thuộc tag này.</li>

            @endforelse

        </ul>

        <div class="mt-3">
            {{ $posts->links() }}
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