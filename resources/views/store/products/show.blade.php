@section('title', $product->name)
@section('meta_description', Str::limit(strip_tags($product->description), 160))
@section('og_image', $product->imageUrl)
@section('og_type', 'product')

@php
    $breadcrumbs = [
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Produk', 'url' => route('products.index')],
        ['name' => $product->category->name, 'url' => route('products.category', $product->category->slug)],
        ['name' => $product->name, 'url' => route('products.show', $product->slug)],
    ];
    $breadcrumbSchema = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array_map(fn($b, $i) => [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $b['name'],
            'item' => $b['url'],
        ], $breadcrumbs, array_keys($breadcrumbs)),
    ]);
    $productSchema = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'description' => Str::limit(strip_tags($product->description), 300),
        'image' => $product->imageUrl,
        'offers' => [
            '@type' => 'Offer',
            'price' => $product->selling_price,
            'priceCurrency' => 'IDR',
            'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        ],
    ]);
@endphp
@section('breadcrumb_schema')
<script type="application/ld+json">{{ $breadcrumbSchema }}</script>
@endsection
@section('product_schema')
<script type="application/ld+json">{{ $productSchema }}</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 md:py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <a href="{{ route('products.index') }}" class="hover:text-amber-600 transition-colors">Produk</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-amber-600 transition-colors">{{ $product->category->name }}</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium truncate max-w-[200px]">{{ $product->name }}</span>
    </nav>

    {{-- Product Detail --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 p-4 md:p-8">

            {{-- Images --}}
            @php
                $allImages = collect();
                if ($product->imageUrl) {
                    $allImages->push($product->imageUrl);
                }
                foreach ($product->images as $img) {
                    $allImages->push(asset('storage/' . $img->image));
                }
            @endphp
            <div x-data="{ activeImage: 0, images: @js($allImages->values()) }">
                <div class="relative aspect-square bg-gray-50 rounded-2xl overflow-hidden border border-gray-100">
                    <template x-for="(img, i) in images" :key="i">
                        <img :src="img" x-show="activeImage === i" alt="{{ $product->name }}" class="w-full h-full object-contain p-8 md:p-12">
                    </template>
                    @if($allImages->isEmpty())
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-mobile-screen text-6xl text-gray-200"></i>
                        </div>
                    @endif
                    @if($product->stock > 0)
                        <span class="absolute top-4 left-4 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Tersedia
                        </span>
                    @else
                        <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                            Stok Habis
                        </span>
                    @endif
                </div>

                {{-- Thumbnails --}}
                @if($allImages->count() > 1)
                    <div class="flex gap-2 mt-3 overflow-x-auto scrollbar-hide">
                        <template x-for="(img, i) in images" :key="i">
                            <button @click="activeImage = i" class="flex-shrink-0 w-16 h-16 md:w-20 md:h-20 rounded-xl border-2 overflow-hidden transition-all duration-200" :class="activeImage === i ? 'border-amber-500 shadow-md' : 'border-gray-200 hover:border-gray-300'">
                                <img :src="img" class="w-full h-full object-contain p-2">
                            </button>
                        </template>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div>
                <p class="text-xs text-amber-600 font-semibold bg-amber-50 inline-block px-3 py-1 rounded-full mb-3">{{ $product->category->name }}</p>
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>

                {{-- Rating Display --}}
                <div class="flex items-center gap-2 mb-3">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($product->avg_rating))
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                            @else
                                <i class="far fa-star text-gray-200 text-sm"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ number_format($product->avg_rating, 1) }}</span>
                    <span class="text-xs text-gray-400">({{ $product->review_count }} ulasan)</span>
                </div>

                <p class="text-sm text-gray-500 mb-4 flex items-center gap-2">
                    <i class="fas fa-barcode"></i> SKU: {{ $product->sku }}
                </p>

                {{-- Price --}}
                <div class="flex items-baseline gap-3 mb-4">
                    @if($product->is_promo)
                        <p class="text-3xl md:text-4xl font-bold text-amber-600">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</p>
                        <p class="text-lg text-gray-400 line-through">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                        <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full">DISKON!</span>
                    @else
                        <p class="text-3xl md:text-4xl font-bold text-amber-600">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                        @if($product->buying_price > 0 && $product->selling_price < $product->buying_price * 1.5)
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full">HEMAT!</span>
                        @endif
                    @endif
                </div>

                {{-- Stock Status --}}
                <div class="flex flex-wrap gap-4 mb-6 p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-2">
                        <i class="fas {{ $product->stock > 0 ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-400' }}"></i>
                        <span class="text-sm {{ $product->stock > 0 ? 'text-green-700' : 'text-red-500' }} font-medium">
                            @if($product->stock > 0)
                                Stok Tersedia ({{ $product->stock }} unit)
                            @else
                                Stok Habis
                            @endif
                        </span>
                    </div>
                    @if($product->stock > 0 && $product->stock <= $product->min_stock)
                        <span class="text-sm text-orange-600 font-medium flex items-center gap-1">
                            <i class="fas fa-exclamation-triangle"></i> Segera habis
                        </span>
                    @endif
                </div>

                {{-- Add to Cart + Wishlist + Quick Buy --}}
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="flex items-center gap-4">
                            <div class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden" x-data="{ qty: 1 }">
                                <button type="button" @click="qty = Math.max(1, qty - 1); $el.parentElement.querySelector('input').value = qty" class="px-3 md:px-4 py-3 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <input type="number" name="quantity" x-model="qty" value="1" min="1" max="{{ $product->stock }}" class="w-14 md:w-16 text-center border-0 focus:ring-0 text-sm font-medium py-3">
                                <button type="button" @click="qty = Math.min({{ $product->stock }}, qty + 1); $el.parentElement.querySelector('input').value = qty" class="px-3 md:px-4 py-3 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                            <button type="submit" class="flex-1 flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-amber-500/25">
                                <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </form>

                    <div class="flex items-center gap-3 mb-6">
                        @auth
                            <button onclick="event.preventDefault(); fetch('{{ route('wishlist.toggle', $product) }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}).then(r=>r.json()).then(d=>{let i=this.querySelector('i');i.className=d.status=='added'?'fas fa-heart text-red-500':'far fa-heart text-gray-400';this.querySelector('span').innerText=d.status=='added'?' Tersimpan':' Simpan'})" class="flex items-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:border-red-200 hover:text-red-500 hover:bg-red-50 transition-all">
                                <i class="far fa-heart text-gray-400"></i>
                                <span>Simpan</span>
                            </button>
                        @endauth
                        <form action="{{ route('compare.toggle', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:border-amber-200 hover:text-amber-600 hover:bg-amber-50 transition-all">
                                <i class="fas fa-scale-balanced"></i>
                                <span>Bandingkan</span>
                            </button>
                        </form>
                        <form action="{{ route('products.quick-buy', $product) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl font-semibold transition-all shadow-lg shadow-green-600/25">
                                <i class="fas fa-bolt"></i> Beli Sekarang
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Delivery Info --}}
                <div class="border-t border-gray-100 pt-4 space-y-2">
                    <p class="text-sm text-gray-500 flex items-center gap-2"><i class="fas fa-truck text-amber-500"></i> Gratis ongkir untuk pembelian di atas Rp 200.000</p>
                    <p class="text-sm text-gray-500 flex items-center gap-2"><i class="fas fa-shield-halved text-amber-500"></i> Garansi 30 hari untuk semua produk</p>
                    <p class="text-sm text-gray-500 flex items-center gap-2"><i class="fas fa-rotate-left text-amber-500"></i> Mudah retur jika produk rusak</p>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="border-t border-gray-100" x-data="{ tab: 'description' }">
            <div class="flex border-b border-gray-100">
                <button @click="tab = 'description'" class="px-4 md:px-8 py-4 text-sm font-medium transition-colors relative" :class="tab === 'description' ? 'text-amber-600' : 'text-gray-500 hover:text-gray-700'">
                    Deskripsi
                    <span x-show="tab === 'description'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-amber-500"></span>
                </button>
                @if($product->specifications)
                    <button @click="tab = 'specs'" class="px-4 md:px-8 py-4 text-sm font-medium transition-colors relative" :class="tab === 'specs' ? 'text-amber-600' : 'text-gray-500 hover:text-gray-700'">
                        Spesifikasi
                        <span x-show="tab === 'specs'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-amber-500"></span>
                    </button>
                @endif
            </div>
            <div class="p-4 md:p-8">
                <div x-show="tab === 'description'" x-transition class="text-sm text-gray-600 leading-relaxed max-w-none">
                    @if($product->description)
                        {{ $product->description }}
                    @else
                        <p class="text-gray-400">Belum ada deskripsi untuk produk ini.</p>
                    @endif
                </div>
                @if($product->specifications)
                    <div x-show="tab === 'specs'" x-transition class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $product->specifications }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="mt-10 md:mt-12">
        <div class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-star text-yellow-500"></i> Ulasan Produk
            </h2>

            @php $approvedReviews = $product->approvedReviews()->with('user')->get(); @endphp

            @if($approvedReviews->count() > 0)
                {{-- Rating Summary --}}
                <div class="flex items-center gap-6 p-4 bg-gray-50 rounded-xl mb-6">
                    <div class="text-center">
                        <p class="text-4xl font-bold text-gray-900">{{ number_format($product->avg_rating, 1) }}</p>
                        <div class="flex items-center gap-0.5 mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($product->avg_rating))
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                @else
                                    <i class="far fa-star text-gray-200 text-xs"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $product->review_count }} ulasan</p>
                    </div>
                </div>

                {{-- Review List --}}
                <div class="space-y-5">
                    @foreach($approvedReviews as $review)
                        <div class="border-b border-gray-100 pb-5 {{ $loop->last ? 'border-b-0 pb-0' : '' }}">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-semibold text-xs">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $review->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $review->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-0.5 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    @else
                                        <i class="far fa-star text-gray-200 text-xs"></i>
                                    @endif
                                @endfor
                            </div>
                            @if($review->review)
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $review->review }}</p>
                            @endif
                            @if($review->images && count($review->images) > 0)
                                <div class="flex gap-2 mt-2">
                                    @foreach($review->images as $img)
                                        <a href="{{ asset('storage/' . $img) }}" target="_blank" class="w-16 h-16 bg-gray-50 rounded-lg overflow-hidden border border-gray-100 hover:border-amber-300 transition-colors">
                                            <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="far fa-star text-5xl text-gray-200 mb-3"></i>
                    <p class="text-gray-500">Belum ada ulasan</p>
                    <p class="text-xs text-gray-400 mt-1">Jadilah yang pertama memberikan ulasan untuk produk ini.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Frequently Bought Together --}}
    @if($frequentlyBoughtTogether->count() > 0)
        <div class="mt-10 md:mt-12">
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl border border-amber-100 p-6 md:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                    <i class="fas fa-shopping-bag text-amber-500"></i> Sering Dibeli Bersamaan
                </h2>
                <p class="text-sm text-gray-500 mb-6">Pelanggan yang membeli <strong>{{ $product->name }}</strong> juga membeli produk berikut</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                    @foreach($frequentlyBoughtTogether as $related)
                        <div class="product-card bg-white">
                            <a href="{{ route('products.show', $related->slug) }}" class="block">
                                <div class="relative aspect-square bg-gray-50 overflow-hidden">
                                    @if($related->imageUrl)
                                        <img src="{{ $related->imageUrl }}" class="w-full h-full object-contain p-4 hover:scale-105 transition-transform duration-500" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-mobile-screen text-4xl text-gray-200"></i></div>
                                    @endif
                                </div>
                                <div class="p-3 md:p-4">
                                    <h3 class="text-sm font-medium text-gray-900 line-clamp-2">{{ $related->name }}</h3>
                                    <p class="text-base md:text-lg font-bold text-amber-600 mt-2">
                                        @if($related->is_promo)
                                            <span class="line-through text-gray-400 text-sm font-normal">Rp {{ number_format($related->selling_price, 0, ',', '.') }}</span>
                                            Rp {{ number_format($related->promo_price, 0, ',', '.') }}
                                        @else
                                            Rp {{ number_format($related->selling_price, 0, ',', '.') }}
                                        @endif
                                    </p>
                                </div>
                            </a>
                            <div class="px-3 md:px-4 pb-3 md:pb-4">
                                <form action="{{ route('cart.add', $related) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full text-sm border-2 border-amber-500 text-amber-600 hover:bg-amber-500 hover:text-white py-2 rounded-lg font-medium transition-all flex items-center justify-center gap-2">
                                        <i class="fas fa-shopping-cart text-xs"></i> + Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <div class="mt-10 md:mt-12">
            <h2 class="section-title mb-6">Produk Terkait</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                @foreach($relatedProducts as $related)
                    <div class="product-card">
                        <a href="{{ route('products.show', $related->slug) }}" class="block">
                            <div class="relative aspect-square bg-gray-50 overflow-hidden">
                                @if($related->imageUrl)
                                    <img src="{{ $related->imageUrl }}" class="w-full h-full object-contain p-4 hover:scale-105 transition-transform duration-500" loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-mobile-screen text-4xl text-gray-200"></i></div>
                                @endif
                            </div>
                            <div class="p-3 md:p-4">
                                <h3 class="text-sm font-medium text-gray-900 line-clamp-2">{{ $related->name }}</h3>
                                <p class="text-base md:text-lg font-bold text-amber-600 mt-2">
                                    @if($related->is_promo)
                                        <span class="line-through text-gray-400 text-sm font-normal">Rp {{ number_format($related->selling_price, 0, ',', '.') }}</span>
                                        Rp {{ number_format($related->promo_price, 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($related->selling_price, 0, ',', '.') }}
                                    @endif
                                </p>
                            </div>
                        </a>
                        <div class="px-3 md:px-4 pb-3 md:pb-4">
                            <form action="{{ route('cart.add', $related) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full text-sm border-2 border-amber-500 text-amber-600 hover:bg-amber-500 hover:text-white py-2 rounded-lg font-medium transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-shopping-cart text-xs"></i> + Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
