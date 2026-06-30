@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 md:py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">Pesanan Saya</span>
    </nav>

    <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
        <i class="fas fa-clipboard-list text-amber-500"></i> Pesanan Saya
    </h1>

    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="block bg-white rounded-2xl border border-gray-100 p-4 md:p-6 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-sm">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">#{{ $order->order_number }}</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold self-start sm:self-auto
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'waiting_confirmation') bg-orange-100 text-orange-800
                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status == 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            <i class="fas
                                @if($order->status == 'pending') fa-clock
                                @elseif($order->status == 'waiting_confirmation') fa-hourglass-half
                                @elseif($order->status == 'processing') fa-spinner fa-spin
                                @elseif($order->status == 'shipped') fa-truck
                                @elseif($order->status == 'completed') fa-check-circle
                                @else fa-times-circle
                                @endif
                            "></i>
                            @if($order->status == 'pending') Menunggu Pembayaran
                            @elseif($order->status == 'waiting_confirmation') Menunggu Konfirmasi
                            @elseif($order->status == 'processing') Diproses
                            @elseif($order->status == 'shipped') Dikirim
                            @elseif($order->status == 'completed') Selesai
                            @else Dibatalkan
                            @endif
                        </span>
                    </div>

                    {{-- Items Preview --}}
                    <div class="flex gap-2 mb-4">
                        @foreach($order->items->take(3) as $item)
                            <div class="w-12 h-12 bg-gray-50 rounded-lg overflow-hidden border border-gray-100">
                                @if($item->product->imageUrl)
                                    <img src="{{ $item->product->imageUrl }}" class="w-full h-full object-contain p-1">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-mobile-screen text-gray-200 text-sm"></i></div>
                                @endif
                            </div>
                        @endforeach
                        @if($order->items->count() > 3)
                            <div class="w-12 h-12 bg-gray-50 rounded-lg border border-gray-100 flex items-center justify-center text-xs text-gray-500 font-medium">
                                +{{ $order->items->count() - 3 }}
                            </div>
                        @endif
                        <div class="flex-1"></div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">{{ $order->items->count() }} barang</p>
                            <p class="font-bold text-amber-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-16 md:py-20 bg-white rounded-2xl border border-gray-100">
            <div class="w-24 h-24 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-box-open text-4xl text-gray-300"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Anda belum memiliki pesanan apapun. Ayo mulai belanja sekarang!</p>
            <a href="{{ route('products.index') }}" class="btn-primary inline-flex">
                <i class="fas fa-store"></i> Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection
