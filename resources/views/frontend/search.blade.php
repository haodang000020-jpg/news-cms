@extends('frontend.layouts.master')

@section('title', 'Tìm kiếm')

@section('content')

<h3 class="mb-4">
    Kết quả tìm kiếm: "{{ $keyword }}"
</h3>

@if($posts->count() == 0)

    <div class="alert alert-warning">
        Không tìm thấy bài viết phù hợp.
    </div>

@endif

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
                        {{ Str::limit(strip_tags($post->summary), 120) }}
                    </p>

                    <small class="text-muted">
                        {{ $post->published_at?->format('d/m/Y') }}
                    </small>

                </div>

            </div>

        </div>

    @endforeach

</div>

{{ $posts->links() }}

@endsection