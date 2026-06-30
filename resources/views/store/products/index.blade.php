@section('title', isset($category) ? $category->name : 'Produk')

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 md:py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <a href="{{ route('products.index') }}" class="hover:text-amber-600 transition-colors">Produk</a>
        @if(isset($category))
            <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
            <span class="text-gray-900 font-medium">{{ $category->name }}</span>
        @endif
    </nav>

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">

        {{-- SIDEBAR --}}
        <aside class="lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-2xl border border-gray-100 p-5 sticky top-28">

                {{-- Mobile Filter Toggle --}}
                <div class="flex items-center justify-between mb-4 lg:hidden" x-data="{ filterOpen: false }">
                    <button @click="filterOpen = !filterOpen" class="flex items-center gap-2 text-sm font-bold text-gray-900">
                        <i class="fas fa-sliders-h"></i> Filter
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="{'rotate-180': filterOpen}"></i>
                    </button>
                    @if(request()->hasAny(['brand', 'min_price', 'max_price', 'sort']))
                        <a href="{{ route('products.index', request()->only(['q', 'category'])) }}" class="text-xs text-amber-600 hover:text-amber-700">Hapus Filter</a>
                    @endif
                </div>

                {{-- Filter Content (collapsible on mobile) --}}
                <div x-data="{ filterOpen: window.innerWidth >= 1024 }" x-init="window.addEventListener('resize', () => { if(window.innerWidth >= 1024) filterOpen = true })">
                    <div x-show="filterOpen" x-collapse.duration.300ms>
                        <form method="GET" action="{{ route('products.index') }}">
                            @if(request('q'))
                                <input type="hidden" name="q" value="{{ request('q') }}">
                            @endif
                            @if(isset($category))
                                <input type="hidden" name="category" value="{{ $category->slug }}">
                            @endif

                            {{-- Categories --}}
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-bold text-gray-900 text-sm">Kategori</h3>
                                    <i class="fas fa-folder text-gray-400 text-xs"></i>
                                </div>
                                <ul class="space-y-0.5">
                                    <li>
                                        <a href="{{ route('products.index', request()->only(['q', 'brand', 'min_price', 'max_price', 'sort'])) }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm {{ !isset($category) ? 'bg-amber-50 text-amber-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-amber-600' }} transition-colors">
                                            <span>Semua Produk</span>
                                        </a>
                                    </li>
                                    @foreach($categories as $cat)
                                        <li>
                                            <a href="{{ route('products.category', $cat->slug) }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm {{ isset($category) && $category->id === $cat->id ? 'bg-amber-50 text-amber-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-amber-600' }} transition-colors">
                                                <span>{{ $cat->name }}</span>
                                            </a>
                                            @if($cat->children->count() > 0)
                                                <ul class="ml-3 mt-0.5 space-y-0.5">
                                                    @foreach($cat->children as $child)
                                                        <li>
                                                            <a href="{{ route('products.category', $child->slug) }}" class="block px-3 py-1.5 rounded-lg text-xs text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">{{ $child->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Brand Filter --}}
                            @if($brands->count() > 0)
                                <div class="border-t border-gray-100 pt-4 mb-6">
                                    <h3 class="font-bold text-gray-900 text-sm mb-3">Brand</h3>
                                    <div class="space-y-2 max-h-48 overflow-y-auto">
                                        @foreach($brands as $brand)
                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <input type="checkbox" name="brand[]" value="{{ $brand }}"
                                                    {{ in_array($brand, (array) request('brand', [])) ? 'checked' : '' }}
                                                    class="w-4 h-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500"
                                                    onchange="this.closest('form').submit()">
                                                <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ $brand }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Price Range --}}
                            <div class="border-t border-gray-100 pt-4 mb-6">
                                <h3 class="font-bold text-gray-900 text-sm mb-3">Rentang Harga</h3>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500">
                                    <span class="text-gray-400">-</span>
                                    <input type="number" name="max_price" placeholder="Maks" value="{{ request('max_price') }}"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500">
                                </div>
                                <button type="submit" class="w-full mt-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg font-medium transition-colors">Terapkan</button>
                            </div>

                            {{-- Sort --}}
                            <div class="border-t border-gray-100 pt-4">
                                <h3 class="font-bold text-gray-900 text-sm mb-3">Urutkan</h3>
                                <select name="sort" onchange="this.closest('form').submit()"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="cheapest" {{ request('sort') == 'cheapest' ? 'selected' : '' }}>Termurah</option>
                                    <option value="most_expensive" {{ request('sort') == 'most_expensive' ? 'selected' : '' }}>Termahal</option>
                                    <option value="best_rating" {{ request('sort') == 'best_rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                                </select>
                            </div>

                            {{-- Reset --}}
                            @if(request()->hasAny(['brand', 'min_price', 'max_price', 'sort']))
                                <div class="border-t border-gray-100 pt-4 mt-4">
                                    <a href="{{ route('products.index', request()->only(['q', 'category'])) }}"
                                        class="text-sm text-red-500 hover:text-red-600 font-medium flex items-center justify-center gap-1">
                                        <i class="fas fa-times"></i> Hapus Semua Filter
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 min-w-0">

            {{-- Category Header --}}
            @if(isset($category))
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="text-gray-500 text-sm mt-2">{{ $category->description }}</p>
                    @endif
                </div>
            @else
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 mb-6">
                    <h1 class="text-xl md:text-2xl font-bold text-white">Semua Produk</h1>
                    <p class="text-white/80 text-sm mt-1">Temukan sparepart dan aksesoris HP yang Anda butuhkan</p>
                </div>
            @endif

            {{-- Search query indicator --}}
            @if(request('q'))
                <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-4 text-sm text-amber-700">
                    <i class="fas fa-search mr-2"></i> Hasil pencarian untuk: "<strong>{{ request('q') }}</strong>" ({{ $products->total() }} produk ditemukan)
                </div>
            @endif

            {{-- Products Grid --}}
            @if($products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
                    @foreach($products as $product)
                        <div class="product-card">
                            <a href="{{ route('products.show', $product->slug) }}" class="block">
                                <div class="relative aspect-square bg-gray-50 overflow-hidden">
                                    @if($product->imageUrl)
                                        <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-4 hover:scale-105 transition-transform duration-500" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-mobile-screen text-4xl text-gray-200"></i>
                                        </div>
                                    @endif
                                    @if($product->stock <= 0)
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                            <span class="bg-red-500 text-white px-4 py-1.5 rounded-full text-sm font-bold">Stok Habis</span>
                                        </div>
                                    @endif
                                    {{-- Wishlist Button --}}
                                    @auth
                                        <button onclick="event.preventDefault(); fetch('{{ route('wishlist.toggle', $product) }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}).then(r=>r.json()).then(d=>{this.querySelector('i').className=d.status=='added'?'fas fa-heart text-red-500':'far fa-heart text-gray-400'})" class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow-sm hover:bg-white transition-colors">
                                            <i class="far fa-heart text-gray-400"></i>
                                        </button>
                                    @endauth
                                </div>
                                <div class="p-3 md:p-4">
                                    <p class="text-xs text-gray-500 mb-1">{{ $product->category->name }}</p>
                                    <h3 class="text-sm font-medium text-gray-900 line-clamp-2 min-h-[2.5rem] leading-snug">{{ $product->name }}</h3>
                                    <p class="text-base md:text-lg font-bold text-amber-600 mt-2">
                                        @if($product->is_promo)
                                            <span class="line-through text-gray-400 text-sm font-normal">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                                            Rp {{ number_format($product->promo_price, 0, ',', '.') }}
                                        @else
                                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                        @endif
                                    </p>
                                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                        <span class="flex items-center gap-1"><i class="fas fa-star text-yellow-400 text-[10px]"></i> {{ number_format($product->avg_rating, 1) }}</span>
                                        <span>|</span>
                                        @if($product->stock > 0)
                                            <span class="text-green-600"><i class="fas fa-check-circle text-[10px]"></i> Tersedia</span>
                                        @else
                                            <span class="text-red-400"><i class="fas fa-times-circle text-[10px]"></i> Habis</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            <div class="px-3 md:px-4 pb-3 md:pb-4">
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full text-sm bg-amber-500 hover:bg-amber-600 text-white py-2 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                            <i class="fas fa-shopping-cart text-xs"></i> + Keranjang
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full text-sm bg-gray-100 text-gray-400 py-2 rounded-lg font-medium cursor-not-allowed flex items-center justify-center gap-2">
                                        <i class="fas fa-times-circle text-xs"></i> Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                    <i class="fas fa-box-open text-6xl text-gray-200 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500 mb-6">Maaf, tidak ada produk yang sesuai dengan kriteria Anda.</p>
                    <a href="{{ route('products.index') }}" class="btn-primary inline-flex">Lihat Semua Produk</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
