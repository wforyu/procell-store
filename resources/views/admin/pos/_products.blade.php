@foreach($products as $product)
    <div class="product-card" onclick="addProduct({{ $product->id }}, '{{ addslashes($product->name) }}')">
        <div class="thumb">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            @else
                <i class="fas fa-mobile-screen no-img"></i>
            @endif
        </div>
        <div class="info">
            @if($product->brand)
                <div class="brand">{{ $product->brand }}</div>
            @endif
            <div class="name">{{ $product->name }}</div>
            <div class="price">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
            <div class="stock {{ $product->stock <= $product->min_stock ? 'low' : '' }}">
                Stok: {{ $product->stock }}
            </div>
        </div>
    </div>
@endforeach

@if($products->isEmpty())
    <div class="col-span-full text-center py-12 text-gray-400">
        <i class="fas fa-search text-4xl mb-3"></i>
        <p>Produk tidak ditemukan</p>
    </div>
@endif
