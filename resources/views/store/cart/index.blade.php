@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 md:py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">Keranjang Belanja</span>
    </nav>

    <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-6">Keranjang Belanja</h1>

    @if($cart && $cart->items->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- Cart Items --}}
            <div class="lg:col-span-2 space-y-3">
                @foreach($cart->items as $item)
                    <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
                        <a href="{{ route('products.show', $item->product->slug) }}" class="w-20 h-20 md:w-24 md:h-24 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0">
                            @if($item->product->imageUrl)
                                <img src="{{ $item->product->imageUrl }}" class="w-full h-full object-contain p-2">
                            @else
                                <div class="w-full h-full flex items-center justify-center"><i class="fas fa-mobile-screen text-2xl text-gray-200"></i></div>
                            @endif
                        </a>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-sm font-medium text-gray-900 hover:text-amber-600 line-clamp-1">{{ $item->product->name }}</a>
                            <p class="text-xs text-gray-500 mt-1">{{ $item->product->category->name }}</p>
                            <p class="text-sm font-semibold text-amber-600 mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                @csrf
                                <div class="flex items-center border-2 border-gray-200 rounded-lg overflow-hidden" x-data="{ qty: {{ $item->quantity }} }">
                                    <button type="button" @click="qty = Math.max(1, qty - 1); $el.parentElement.querySelector('input').value = qty" class="px-2 md:px-3 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" name="quantity" x-model="qty" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="w-10 md:w-12 text-center border-0 focus:ring-0 text-sm font-medium py-2">
                                    <button type="button" @click="qty = Math.min({{ $item->product->stock }}, qty + 1); $el.parentElement.querySelector('input').value = qty" class="px-2 md:px-3 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <button type="submit" class="ml-1 p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors" title="Update">
                                    <i class="fas fa-sync-alt text-xs"></i>
                                </button>
                            </form>
                            <div class="text-right min-w-[100px]">
                                <p class="font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Cart Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-28">
                    <h3 class="font-bold text-gray-900 mb-4">Ringkasan Belanja</h3>

                    <div class="space-y-3 mb-4">
                        @foreach($cart->items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 truncate max-w-[180px]">{{ $item->product->name }}</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="text-green-600 font-medium">Gratis</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mt-2">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="text-xl font-bold text-amber-600">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <a href="{{ route('checkout.index') }}" class="btn-primary w-full">
                            <i class="fas fa-shopping-bag"></i> Checkout
                        </a>
                        @guest
                            <a href="{{ route('login') }}" class="btn-outline w-full text-sm">
                                <i class="fas fa-sign-in-alt"></i> Login (simpan riwayat pesanan)
                            </a>
                        @endguest
                        <a href="{{ route('products.index') }}" class="btn-light w-full">
                            <i class="fas fa-arrow-left"></i> Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-16 md:py-20 bg-white rounded-2xl border border-gray-100">
            <div class="w-24 h-24 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-shopping-cart text-4xl text-gray-300"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Keranjang Kosong</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Belum ada produk di keranjang Anda. Ayo mulai belanja sparepart dan aksesoris HP!</p>
            <a href="{{ route('products.index') }}" class="btn-primary inline-flex">
                <i class="fas fa-store"></i> Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection
