@extends('frontend.layouts.master')

@section('title', $category->name . ' - News CMS')

@section('meta_description', $category->description ?: 'Tin tức thuộc danh mục ' . $category->name)

@section('meta_keywords', $category->name . ', tin tức, bài viết')

@section('og_title', $category->name . ' - News CMS')

@section('og_description', $category->description ?: 'Tin tức thuộc danh mục ' . $category->name)

@section('og_type', 'website')

@section('content')

<h3 class="mb-4">
    Danh mục: {{ $category->name }}
</h3>

<div class="row">

    @foreach($posts as $post)

        <div class="col-md-4 mb-4">

            <div class="card h-100">

                @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                         class="card-img-top"
                         style="height:200px; object-fit:cover;">
                @endif

                <div class="card-body">

                    <h5>
                        <a href="{{ route('frontend.posts.show', $post->slug) }}"
                           class="text-decoration-none text-dark">
                            {{ $post->title }}
                        </a>
                    </h5>

                    <p>
                        {{ Str::limit($post->summary, 120) }}
                    </p>

                </div>

            </div>

        </div>

    @endforeach

</div>

{{ $posts->links() }}

@endsection