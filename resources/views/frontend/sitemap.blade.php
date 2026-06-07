<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    @foreach($categories as $category)
        <url>
            <loc>{{ route('frontend.categories.show', $category->slug) }}</loc>
            <lastmod>{{ optional($category->updated_at ?? $category->created_at ?? now())->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    @foreach($posts as $post)
        <url>
            <loc>{{ route('frontend.posts.show', $post->slug) }}</loc>
            <lastmod>{{ optional($post->updated_at ?? $post->published_at ?? $post->created_at ?? now())->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    @foreach($pages as $page)
        <url>
            <loc>{{ route('frontend.pages.show', $page->slug) }}</loc>
            <lastmod>{{ optional($page->updated_at ?? $page->created_at ?? now())->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach

</urlset>