
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- الصفحة الرئيسية -->
    <url>
        <loc>{{ url('/') }}</loc>
        <priority>1.0</priority>
        <changefreq>daily</changefreq>
    </url>

    <!-- المنتجات -->
    @foreach ($products as $product)
        <url>
            <loc>{{ route('products.show', $product->slug) }}</loc>
            @if($product->updated_at)
                <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
            @elseif($product->created_at)
                <lastmod>{{ $product->created_at->toAtomString() }}</lastmod>
            @else
                <lastmod>{{ now()->toAtomString() }}</lastmod>
            @endif
            <priority>0.8</priority>
            <changefreq>weekly</changefreq>
        </url>
    @endforeach
</urlset>
