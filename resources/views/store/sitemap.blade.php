<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <priority>1.0</priority>
        <changefreq>daily</changefreq>
    </url>
    <url>
        <loc>{{ route('products.index') }}</loc>
        <priority>0.9</priority>
        <changefreq>daily</changefreq>
    </url>
    @foreach($categories as $cat)
    <url>
        <loc>{{ route('products.category', $cat->slug) }}</loc>
        <priority>0.8</priority>
        <changefreq>weekly</changefreq>
    </url>
    @endforeach
    @foreach($products as $product)
    <url>
        <loc>{{ route('products.show', $product->slug) }}</loc>
        <lastmod>{{ $product->updated_at ? $product->updated_at->tz('UTC')->toW3cString() : now()->toW3cString() }}</lastmod>
        <priority>0.7</priority>
        <changefreq>weekly</changefreq>
    </url>
    @endforeach
    @foreach($pages as $page)
    <url>
        <loc>{{ route('page.show', $page->slug) }}</loc>
        <lastmod>{{ $page->updated_at ? $page->updated_at->tz('UTC')->toW3cString() : now()->toW3cString() }}</lastmod>
        <priority>0.5</priority>
        <changefreq>monthly</changefreq>
    </url>
    @endforeach
</urlset>
