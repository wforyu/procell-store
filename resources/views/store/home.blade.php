@php
    $categoryIcons = [
        'lcd-display' => 'fa-mobile-screen',
        'battery' => 'fa-battery-three-quarters',
        'flexible-cable' => 'fa-plug',
        'mainboard-ic' => 'fa-microchip',
        'button-switch' => 'fa-toggle-on',
        'charger-adapter' => 'fa-charging-station',
        'data-cable' => 'fa-cable-car',
    ];
@endphp

@extends('layouts.app')

@section('content')
{{-- HERO CAROUSEL --}}
@if($banners->count() > 0)
    <div class="bg-gray-100" x-data="{
        current: 0,
        slides: {{ $banners->count() }},
        autoplay() { setInterval(() => { this.current = (this.current + 1) % this.slides }, 5000) },
        init() { this.autoplay() }
    }">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="relative rounded-2xl overflow-hidden">
                <div class="aspect-[21/9] md:aspect-[3/1] relative">
                    @foreach($banners as $i => $banner)
                        <div x-show="current === {{ $i }}" x-transition:enter="transition-all duration-700" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition-all duration-500" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute inset-0 rounded-2xl overflow-hidden">
                            @if($banner->image)
                                <img src="{{ asset('storage/' . $banner->image) }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $banner->title }}">
                                <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
                            @else
                                <div class="absolute inset-0" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, {{ $i % 2 == 0 ? '#0f3460' : '#533483' }} 100%);"></div>
                            @endif
                            <div class="absolute inset-0 flex items-center px-8 md:px-16">
                                <div class="max-w-lg relative z-10">
                                    <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white mb-2 md:mb-4 leading-tight">{{ $banner->title }}</h2>
                                    @if($banner->subtitle)
                                        <p class="text-sm md:text-lg text-gray-200 mb-4 md:mb-6">{{ $banner->subtitle }}</p>
                                    @endif
                                    @if($banner->link)
                                        <a href="{{ $banner->link }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-semibold transition-all text-sm md:text-base shadow-lg shadow-amber-500/25">
                                            Lihat Detail
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Navigation Arrows --}}
                <button @click="current = (current - 1 + slides) % slides" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center text-gray-700 hover:text-amber-500 transition-all">
                    <i class="fas fa-chevron-left text-sm"></i>
                </button>
                <button @click="current = (current + 1) % slides" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center text-gray-700 hover:text-amber-500 transition-all">
                    <i class="fas fa-chevron-right text-sm"></i>
                </button>

                {{-- Dots --}}
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    @foreach($banners as $i => $banner)
                        <button @click="current = {{ $i }}" class="w-2 h-2 rounded-full transition-all duration-300" :class="current === {{ $i }} ? 'bg-amber-500 w-6' : 'bg-white/50 hover:bg-white/80'"></button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@else
    {{-- Default Hero --}}
    <div class="gradient-hero">
        <div class="max-w-7xl mx-auto px-4 py-12 md:py-20">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-1 bg-amber-500/20 text-amber-400 px-3 py-1 rounded-full text-sm font-medium mb-4">
                    <i class="fas fa-bolt"></i> Sparepart & Aksesoris HP
                </span>
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">Toko Sparepart & <span class="text-amber-500">Aksesoris</span> HP Terpercaya</h1>
                <p class="text-gray-300 text-sm md:text-lg mb-6">LCD, Baterai, Flex Cable, Charger dan berbagai aksesoris smartphone berkualitas dengan harga terbaik.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-amber-500/25">
                    Belanja Sekarang <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
@endif

{{-- STORE FEATURES STRIP --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0"><i class="fas fa-truck"></i></div>
                <div><p class="text-sm font-semibold text-gray-900">Gratis Ongkir</p><p class="text-xs text-gray-500">Min. belanja Rp 200rb</p></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0"><i class="fas fa-shield-halved"></i></div>
                <div><p class="text-sm font-semibold text-gray-900">Garansi 30 Hari</p><p class="text-xs text-gray-500">Barang rusak? Kami ganti</p></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 flex-shrink-0"><i class="fas fa-rotate-left"></i></div>
                <div><p class="text-sm font-semibold text-gray-900">Mudah Retur</p><p class="text-xs text-gray-500">Proses cepat dan mudah</p></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0"><i class="fas fa-headset"></i></div>
                <div><p class="text-sm font-semibold text-gray-900">CS 24/7</p><p class="text-xs text-gray-500">Siap bantu kapan saja</p></div>
            </div>
        </div>
    </div>
</div>

{{-- CATEGORIES --}}
<div class="max-w-7xl mx-auto px-4 py-8 md:py-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="section-title">Kategori Produk</h2>
        <a href="{{ route('products.index') }}" class="hidden sm:flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700 font-medium">Lihat Semua <i class="fas fa-chevron-right text-xs"></i></a>
    </div>
    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3 md:gap-4">
        @foreach($categories as $category)
            <a href="{{ route('products.category', $category->slug) }}" class="group flex flex-col items-center p-4 md:p-5 bg-white rounded-2xl border border-gray-100 hover:border-amber-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 flex items-center justify-center text-2xl md:text-3xl text-amber-500 group-hover:scale-110 transition-transform duration-300 mb-3">
                    <i class="fas {{ $categoryIcons[$category->slug] ?? 'fa-folder' }}"></i>
                </div>
                <span class="text-xs md:text-sm font-medium text-gray-700 group-hover:text-amber-600 text-center leading-tight">{{ $category->name }}</span>
            </a>
        @endforeach
    </div>
</div>

{{-- FLASH SALE / PROMO BANNER --}}
<div class="max-w-7xl mx-auto px-4 pb-8">
    <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-red-600 via-red-500 to-orange-500 p-6 md:p-10">
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse-soft"><i class="fas fa-bolt"></i> FLASH SALE</span>
                    <span class="text-white/80 text-xs">Akhir Pekan Ini</span>
                </div>
                <h3 class="text-xl md:text-3xl font-bold text-white">Diskon Hingga 50%</h3>
                <p class="text-white/80 text-sm mt-1">Untuk pembelian LCD dan Baterai, promo terbatas!</p>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-white text-red-600 px-6 py-3 rounded-xl font-bold hover:bg-gray-100 transition-all shadow-lg whitespace-nowrap">
                Beli Sekarang <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- NEW PRODUCTS --}}
<div class="max-w-7xl mx-auto px-4 pb-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="section-title">Produk Terbaru</h2>
        <a href="{{ route('products.index') }}" class="hidden sm:flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700 font-medium">Lihat Semua <i class="fas fa-chevron-right text-xs"></i></a>
    </div>

    @if($products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 md:gap-4">
            @foreach($products as $product)
                <div class="product-card">
                    <a href="{{ route('products.show', $product->slug) }}" class="block">
                        <div class="relative aspect-square bg-gray-50 overflow-hidden">
                            @if($product->imageUrl)
                                <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-mobile-screen text-4xl text-gray-200"></i>
                                </div>
                            @endif
                            @if($product->stock <= 0)
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                    <span class="bg-red-500 text-white px-4 py-1.5 rounded-full text-sm font-bold">Stok Habis</span>
                                </div>
                            @elseif($product->stock <= $product->min_stock)
                                <span class="badge-discount">Sisa {{ $product->stock }}</span>
                            @endif
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
                            <div class="flex items-center gap-2 mt-2 text-xs text-gray-400">
                                <span class="flex items-center gap-1"><i class="fas fa-star text-yellow-400 text-[10px]"></i> 5.0</span>
                                <span>|</span>
                                <span><i class="far fa-eye text-[10px]"></i> Terjual</span>
                            </div>
                        </div>
                    </a>
                    <div class="px-3 md:px-4 pb-3 md:pb-4">
                        @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full text-sm bg-amber-500 hover:bg-amber-600 text-white py-2 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-shopping-cart text-xs"></i> Tambah
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <i class="fas fa-box-open text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-500">Belum ada produk tersedia</p>
        </div>
    @endif
</div>

{{-- BRANDS / TRUSTED --}}
<div class="bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <p class="text-center text-sm text-gray-500 mb-6">Kepercayaan Pelanggan</p>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6 items-center justify-items-center">
            <div class="flex items-center gap-2 text-gray-400">
                <i class="fas fa-check-circle text-green-500"></i>
                <span class="text-sm font-medium">Produk Original</span>
            </div>
            <div class="flex items-center gap-2 text-gray-400">
                <i class="fas fa-check-circle text-green-500"></i>
                <span class="text-sm font-medium">Harga Kompetitif</span>
            </div>
            <div class="flex items-center gap-2 text-gray-400">
                <i class="fas fa-check-circle text-green-500"></i>
                <span class="text-sm font-medium">Pengiriman Cepat</span>
            </div>
            <div class="flex items-center gap-2 text-gray-400">
                <i class="fas fa-check-circle text-green-500"></i>
                <span class="text-sm font-medium">Garansi 30 Hari</span>
            </div>
            <div class="flex items-center gap-2 text-gray-400">
                <i class="fas fa-check-circle text-green-500"></i>
                <span class="text-sm font-medium">Respon Cepat</span>
            </div>
        </div>
    </div>
</div>
{{-- POPUP BANNER MODAL --}}
@if($popupBanner)
    <div x-data="{ open: false }" x-init="setTimeout(() => open = true, 1500)" x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="open = false">
        {{-- Backdrop --}}
        <div x-show="open" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>
        {{-- Modal Content --}}
        <div x-show="open" x-transition:enter="transition-all duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition-all duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="relative z-10 bg-white rounded-3xl overflow-hidden max-w-lg w-full shadow-2xl">
            {{-- Close Button --}}
            <button @click="open = false" class="absolute top-3 right-3 z-20 w-8 h-8 rounded-full bg-white/90 hover:bg-white shadow flex items-center justify-center text-gray-500 hover:text-gray-700 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
            {{-- Image --}}
            @if($popupBanner->image)
                <div class="w-full aspect-[4/3] bg-gray-100 flex items-center justify-center p-4">
                    <img src="{{ asset('storage/' . $popupBanner->image) }}" alt="{{ $popupBanner->title }}" class="w-full h-full object-contain">
                </div>
            @else
                <div class="w-full aspect-[4/3] bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center">
                    <i class="fas fa-bullhorn text-6xl text-white/50"></i>
                </div>
            @endif
            <div class="p-6 text-center">
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $popupBanner->title }}</h3>
                @if($popupBanner->subtitle)
                    <p class="text-gray-500 text-sm mb-5">{{ $popupBanner->subtitle }}</p>
                @endif
                <div class="flex items-center justify-center gap-3">
                    @if($popupBanner->link)
                        <a href="{{ $popupBanner->link }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-xl font-semibold transition-all shadow-lg shadow-amber-500/25">
                            Lihat <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    @endif
                    <button @click="open = false" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-all">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
