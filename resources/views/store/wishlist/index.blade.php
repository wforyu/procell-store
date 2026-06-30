@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 md:py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">Wishlist</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl md:text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-heart text-red-500"></i> Wishlist ({{ $wishlist->total() }} item)
        </h1>
        @if($wishlist->count() > 0)
            <a href="{{ route('products.index') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium inline-flex items-center gap-1">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        @endif
    </div>

    @if($wishlist->count() > 0)
        {{-- Wishlist Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
            @foreach($wishlist as $item)
                <div class="product-card">
                    <a href="{{ route('products.show', $item->product->slug) }}" class="block">
                        <div class="relative aspect-square bg-gray-50 overflow-hidden">
                            @if($item->product->imageUrl)
                                <img src="{{ $item->product->imageUrl }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain p-4 hover:scale-105 transition-transform duration-500" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-mobile-screen text-4xl text-gray-200"></i>
                                </div>
                            @endif
                            @if($item->product->stock <= 0)
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                    <span class="bg-red-500 text-white px-4 py-1.5 rounded-full text-sm font-bold">Stok Habis</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-3 md:p-4">
                            <p class="text-xs text-gray-500 mb-1">{{ $item->product->category->name }}</p>
                            <h3 class="text-sm font-medium text-gray-900 line-clamp-2 min-h-[2.5rem] leading-snug">{{ $item->product->name }}</h3>
                            <p class="text-base md:text-lg font-bold text-amber-600 mt-2">Rp {{ number_format($item->product->selling_price, 0, ',', '.') }}</p>
                            <div class="flex items-center gap-2 mt-2 text-xs">
                                @if($item->product->stock > 0)
                                    <span class="text-green-600"><i class="fas fa-check-circle text-[10px]"></i> Tersedia</span>
                                @else
                                    <span class="text-red-400"><i class="fas fa-times-circle text-[10px]"></i> Habis</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    <div class="px-3 md:px-4 pb-3 md:pb-4 space-y-2">
                        <form action="{{ route('wishlist.toggle', $item->product) }}" method="POST" onsubmit="event.preventDefault(); fetch(this.action, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}).then(()=>this.closest('.product-card').remove())">
                            @csrf
                            <button type="submit" class="w-full text-sm border border-red-200 text-red-500 hover:bg-red-50 py-2 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-trash-alt text-xs"></i> Hapus
                            </button>
                        </form>
                        @if($item->product->stock > 0)
                            <form action="{{ route('cart.add', $item->product) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full text-sm bg-amber-500 hover:bg-amber-600 text-white py-2 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-shopping-cart text-xs"></i> + Keranjang
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $wishlist->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="far fa-heart text-4xl text-gray-300"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Wishlist Masih Kosong</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">Simpan produk favorit Anda di wishlist agar mudah ditemukan kembali.</p>
            <a href="{{ route('products.index') }}" class="btn-primary inline-flex">
                <i class="fas fa-shopping-bag"></i> Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection
