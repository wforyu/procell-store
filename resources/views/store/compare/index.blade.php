@section('title', 'Bandingkan Produk')
@section('meta_description', 'Bandingkan spesifikasi dan harga produk sparepart HP')

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 md:py-8">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">Bandingkan Produk</span>
    </nav>

    @if(count($products) > 0)
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-900">Bandingkan Produk</h1>
            <form action="{{ route('compare.clear') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-600 font-medium flex items-center gap-1"
                        onclick="return confirm('Hapus semua produk dari perbandingan?')">
                    <i class="fas fa-trash-can"></i> Hapus Semua
                </button>
            </form>
        </div>

        <p class="text-sm text-gray-500 mb-6">{{ count($products) }} produk sedang dibandingkan</p>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr>
                        <th class="w-32 md:w-40 p-3 border border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        @foreach($products as $product)
                            <th class="p-3 border border-gray-200 bg-gray-50 text-center min-w-[180px] md:min-w-[220px]">
                                <form action="{{ route('compare.remove', $product) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="text-red-400 hover:text-red-600 text-xs">
                                        <i class="fas fa-xmark"></i> Hapus
                                    </button>
                                </form>
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <div class="w-24 h-24 md:w-32 md:h-32 mx-auto bg-gray-50 rounded-xl overflow-hidden mb-2">
                                        @if($product->imageUrl)
                                            <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-3">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-mobile-screen text-3xl text-gray-200"></i></div>
                                        @endif
                                    </div>
                                </a>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-3 border border-gray-200 bg-gray-50 font-medium text-gray-700">Harga</td>
                        @foreach($products as $product)
                            <td class="p-3 border border-gray-200 text-center">
                                @if($product->is_promo)
                                    <p class="text-lg font-bold text-amber-600">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-400 line-through">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-lg font-bold text-amber-600">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <tr>
                        <td class="p-3 border border-gray-200 bg-gray-50 font-medium text-gray-700">Merk</td>
                        @foreach($products as $product)
                            <td class="p-3 border border-gray-200 text-center text-gray-600">{{ $product->brand ?? '-' }}</td>
                        @endforeach
                    </tr>

                    <tr>
                        <td class="p-3 border border-gray-200 bg-gray-50 font-medium text-gray-700">Kategori</td>
                        @foreach($products as $product)
                            <td class="p-3 border border-gray-200 text-center text-gray-600">{{ $product->category->name ?? '-' }}</td>
                        @endforeach
                    </tr>

                    <tr>
                        <td class="p-3 border border-gray-200 bg-gray-50 font-medium text-gray-700">SKU</td>
                        @foreach($products as $product)
                            <td class="p-3 border border-gray-200 text-center text-gray-600">{{ $product->sku ?? '-' }}</td>
                        @endforeach
                    </tr>

                    <tr>
                        <td class="p-3 border border-gray-200 bg-gray-50 font-medium text-gray-700">Stok</td>
                        @foreach($products as $product)
                            <td class="p-3 border border-gray-200 text-center">
                                @if($product->stock > 0)
                                    <span class="text-green-600 font-medium"><i class="fas fa-check-circle"></i> Tersedia ({{ $product->stock }})</span>
                                @else
                                    <span class="text-red-400 font-medium"><i class="fas fa-times-circle"></i> Habis</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <tr>
                        <td class="p-3 border border-gray-200 bg-gray-50 font-medium text-gray-700">Rating</td>
                        @foreach($products as $product)
                            <td class="p-3 border border-gray-200 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($product->avg_rating))
                                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        @else
                                            <i class="far fa-star text-gray-200 text-xs"></i>
                                        @endif
                                    @endfor
                                    <span class="text-xs text-gray-500 ml-1">({{ number_format($product->avg_rating, 1) }})</span>
                                </div>
                            </td>
                        @endforeach
                    </tr>

                    @if($products->first()->specifications)
                        @php
                            $specKeys = collect();
                            foreach ($products as $p) {
                                if ($p->specifications) {
                                    $lines = explode("\n", $p->specifications);
                                    foreach ($lines as $line) {
                                        if (str_contains($line, ':')) {
                                            $specKeys->push(trim(explode(':', $line, 2)[0]));
                                        }
                                    }
                                }
                            }
                            $specKeys = $specKeys->unique();
                        @endphp

                        @foreach($specKeys as $key)
                            <tr>
                                <td class="p-3 border border-gray-200 bg-gray-50 font-medium text-gray-700">{{ $key }}</td>
                                @foreach($products as $product)
                                    @php
                                        $val = '-';
                                        if ($product->specifications) {
                                            foreach (explode("\n", $product->specifications) as $line) {
                                                if (str_starts_with(trim($line), $key.':')) {
                                                    $val = trim(explode(':', $line, 2)[1] ?? '-');
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <td class="p-3 border border-gray-200 text-center text-gray-600">{{ $val }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif

                    <tr>
                        <td class="p-3 border border-gray-200 bg-gray-50 font-medium text-gray-700">Aksi</td>
                        @foreach($products as $product)
                            <td class="p-3 border border-gray-200 text-center">
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full text-sm bg-amber-500 hover:bg-amber-600 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                            <i class="fas fa-shopping-cart"></i> + Keranjang
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full text-sm bg-gray-100 text-gray-400 py-2 px-4 rounded-lg font-medium cursor-not-allowed">Stok Habis</button>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('products.index') }}" class="text-amber-600 hover:text-amber-700 font-medium inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Lanjut Belanja
            </a>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <i class="fas fa-scale-balanced text-6xl text-gray-200 mb-4"></i>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Produk</h2>
            <p class="text-gray-500 mb-4">Tambahkan produk untuk membandingkan spesifikasi dan harga</p>
            <p class="text-xs text-gray-400 mb-6">Maksimal 4 produk dapat dibandingkan sekaligus</p>
            <a href="{{ route('products.index') }}" class="btn-primary inline-flex">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection
