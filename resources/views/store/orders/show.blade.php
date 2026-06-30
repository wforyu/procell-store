@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 md:py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <a href="{{ route('orders.index') }}" class="hover:text-amber-600 transition-colors">Pesanan Saya</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">#{{ $order->order_number }}</span>
    </nav>

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-receipt text-amber-500"></i>
                    Pesanan #{{ $order->order_number }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d/m/Y H:i') }} WIB</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-bold self-start
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
                @elseif($order->status == 'processing') Sedang Diproses
                @elseif($order->status == 'shipped') Dalam Pengiriman
                @elseif($order->status == 'completed') Selesai
                @else Dibatalkan
                @endif
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Order Items --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-box text-amber-500"></i> Item Pesanan
                </h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 {{ !$loop->last ? 'pb-4 border-b border-gray-50' : '' }}">
                            <div class="w-16 h-16 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0">
                                @if($item->product->imageUrl)
                                    <img src="{{ $item->product->imageUrl }}" class="w-full h-full object-contain p-2">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-mobile-screen text-xl text-gray-200"></i></div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="text-sm font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Total --}}
                <div class="border-t border-gray-100 pt-4 mt-4 space-y-1">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    @if($order->shipping_cost > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center border-t border-gray-100 pt-2">
                        <span class="font-semibold text-gray-900">Total Pesanan</span>
                        <span class="text-xl font-bold text-amber-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment Proof Upload --}}
            @if(in_array($order->status, ['pending']))
                <div class="bg-white rounded-2xl border border-amber-200 p-6" x-data="{ uploading: false }">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-upload text-amber-500"></i> Upload Bukti Transfer
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Silakan transfer ke rekening yang tertera dan upload bukti pembayaran untuk mempercepat proses konfirmasi.
                    </p>

                    {{-- Bank Accounts --}}
                    @php $bankAccounts = App\Models\BankAccount::active()->get(); @endphp
                    @if($bankAccounts->count() > 0)
                        <div class="bg-gray-50 rounded-xl p-4 mb-4 space-y-2">
                            <p class="text-xs font-medium text-gray-700">Rekening Tujuan:</p>
                            @foreach($bankAccounts as $bank)
                                <div class="flex items-center justify-between text-sm">
                                    <div>
                                        <span class="font-semibold text-gray-900">{{ $bank->bank_name }}</span>
                                        <span class="text-gray-600"> — {{ $bank->account_number }}</span>
                                        <span class="text-gray-500">a.n. {{ $bank->account_holder }}</span>
                                    </div>
                                    <button onclick="navigator.clipboard.writeText('{{ $bank->account_number }}')" class="text-xs text-amber-600 hover:text-amber-700 font-medium px-2 py-1 rounded border border-amber-200 hover:bg-amber-50 transition-colors">
                                        <i class="fas fa-copy"></i> Salin
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('orders.payment.upload', $order) }}" method="POST" enctype="multipart/form-data"
                          @submit="uploading = true">
                        @csrf
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <input type="file" name="payment_proof" accept="image/*" required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-colors">
                                @error('payment_proof') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" :disabled="uploading"
                                    class="btn-primary text-sm !py-2.5 !px-5" x-text="uploading ? 'Mengupload...' : 'Upload'">
                                Upload
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-2"><i class="fas fa-info-circle"></i> Format: JPG, PNG. Maksimal 2MB</p>
                    </form>
                </div>
            @endif

            {{-- Confirm Received Button + Modal --}}
            @if($order->status === 'shipped')
                <div x-data="{ confirmModal: false }">
                    <div class="bg-white rounded-2xl border border-green-200 p-6 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-box-open text-2xl text-green-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">Pesanan Sudah Diterima?</h3>
                        <p class="text-sm text-gray-500 mb-5">Konfirmasi jika barang sudah sampai dengan selamat</p>
                        <button type="button" @click="confirmModal = true"
                                class="btn-primary bg-green-600 hover:bg-green-700">
                            <i class="fas fa-check-circle"></i> Pesanan Diterima
                        </button>
                    </div>

                    {{-- Modal Overlay --}}
                    <div x-show="confirmModal" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                         @click="confirmModal = false"
                         @keydown.escape.window="confirmModal = false">
                        <div x-show="confirmModal" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                             @click.stop
                             class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 text-center relative overflow-hidden">
                            {{-- Accent bar --}}
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-green-500 to-emerald-500"></div>

                            {{-- Icon --}}
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 mt-2">
                                <i class="fas fa-check-circle text-3xl text-green-600"></i>
                            </div>

                            {{-- Title --}}
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi Pesanan</h3>

                            {{-- Message --}}
                            <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                                Apakah Anda yakin pesanan ini sudah diterima dengan selamat?<br>
                                <span class="text-xs text-gray-400">Tindakan ini tidak dapat dibatalkan.</span>
                            </p>

                            {{-- Actions --}}
                            <div class="flex gap-3">
                                <button type="button" @click="confirmModal = false"
                                        class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <form action="{{ route('orders.confirm-received', $order) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                            class="w-full px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-green-600/20">
                                        <i class="fas fa-check-circle"></i> Ya, Konfirmasi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Retur Section --}}
            @if(in_array($order->status, ['shipped', 'completed']))
                @php
                    $activeRetur = $order->returns->whereIn('status', ['pending', 'approved'])->first();
                @endphp
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-undo-alt text-red-500"></i> Retur Barang
                    </h3>

                    @if($activeRetur)
                        <div class="bg-{{ $activeRetur->status === 'approved' ? 'green' : 'orange' }}-50 rounded-xl p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-{{ $activeRetur->status === 'approved' ? 'check-circle text-green-600' : 'hourglass-half text-orange-600' }}"></i>
                                <span class="font-medium text-sm">
                                    @if($activeRetur->status === 'pending') Menunggu persetujuan admin
                                    @elseif($activeRetur->status === 'approved') Retur disetujui
                                    @else Retur ditolak
                                    @endif
                                </span>
                            </div>
                            <p class="text-xs text-gray-600">No. Retur: #{{ $activeRetur->return_number }}</p>
                            @if($activeRetur->admin_note)
                                <p class="text-xs text-gray-600 mt-1">Catatan admin: {{ $activeRetur->admin_note }}</p>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mb-4">Barang rusak/tidak sesuai? Ajukan retur dalam waktu 3 hari setelah pesanan diterima.</p>
                        <a href="{{ route('returns.create', $order) }}" class="btn-outline text-sm !py-2 !px-4">
                            <i class="fas fa-undo-alt"></i> Ajukan Retur
                        </a>
                    @endif
                </div>
            @endif

            {{-- Review Section --}}
            @if($order->status === 'completed')
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-star text-yellow-500"></i> Beri Ulasan
                    </h3>
                    @php
                        $allReviewed = $order->items->every(fn($item) => $item->product->reviews->where('user_id', auth()->id())->where('order_id', $order->id)->isNotEmpty());
                    @endphp
                    @if(!$allReviewed)
                        <form action="{{ route('orders.review.store', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @foreach($order->items as $item)
                                @php $existingReview = $item->product->reviews->where('user_id', auth()->id())->where('order_id', $order->id)->first(); @endphp
                                @if(!$existingReview)
                                    <div class="border-b border-gray-100 pb-4 mb-4">
                                        <p class="text-sm font-medium text-gray-900 mb-3">{{ $item->product->name }}</p>
                                        <div class="flex gap-1 mb-3" x-data="{ rating: 0 }">
                                            <template x-for="i in 5">
                                                <button type="button" @click="rating = i" class="text-2xl transition-colors"
                                                        :class="i <= rating ? 'text-yellow-400' : 'text-gray-200'">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            </template>
                                            <input type="hidden" name="ratings[{{ $item->product_id }}]" x-model="rating">
                                        </div>
                                        <textarea name="reviews[{{ $item->product_id }}]" rows="2" class="input-field text-sm mb-2" placeholder="Tulis ulasan Anda..."></textarea>
                                    </div>
                                @endif
                            @endforeach
                            <button type="submit" class="btn-primary"><i class="fas fa-paper-plane"></i> Kirim Ulasan</button>
                        </form>
                    @else
                        <p class="text-sm text-gray-500"><i class="fas fa-check-circle text-green-500"></i> Anda sudah memberikan ulasan untuk semua produk di pesanan ini. Terima kasih!</p>
                    @endif
                </div>
            @endif

            {{-- Payment Info (after upload) --}}
            @if($order->payment_proof)
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-receipt text-green-500"></i> Bukti Pembayaran
                    </h3>
                    <div class="flex items-center gap-4">
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                           class="w-24 h-24 bg-gray-50 rounded-xl overflow-hidden border border-gray-200 hover:border-amber-300 transition-colors">
                            <img src="{{ asset('storage/' . $order->payment_proof) }}" class="w-full h-full object-cover">
                        </a>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Bukti transfer telah diupload</p>
                            @if($order->payment_verified_at)
                                <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Terverifikasi {{ $order->payment_verified_at->format('d/m/Y H:i') }}
                                </p>
                            @else
                                <p class="text-xs text-orange-500 mt-1 flex items-center gap-1">
                                    <i class="fas fa-hourglass-half"></i> Menunggu konfirmasi admin
                                </p>
                            @endif
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                               class="text-xs text-amber-600 hover:text-amber-700 font-medium mt-2 inline-block">
                                <i class="fas fa-external-link-alt"></i> Lihat Gambar
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">

            {{-- Courier Info --}}
            @if($order->courier)
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-shipping-fast text-amber-500"></i> Pengiriman
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kurir</span>
                            <span class="font-medium text-gray-900">{{ \App\Http\Controllers\Store\CheckoutController::getCourierLabel($order->courier) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Layanan</span>
                            <span class="font-medium text-gray-900">{{ $order->courier_service }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        @if($order->tracking_number)
                            <div class="border-t border-gray-100 pt-2 mt-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">No. Resi</span>
                                    <span class="font-bold text-amber-600 text-sm">{{ $order->tracking_number }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Shipping Address --}}
            @if($order->shipping_address)
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-map-marker-alt text-amber-500"></i> Alamat Pengiriman
                    </h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $order->shipping_address }}</p>
                </div>
            @endif

            {{-- Notes --}}
            @if($order->notes)
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-sticky-note text-amber-500"></i> Catatan
                    </h3>
                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif

            {{-- Status Timeline --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2 text-sm">
                    <i class="fas fa-clock text-amber-500"></i> Status Pesanan
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-white text-[10px]"></i>
                            </div>
                            <div class="w-0.5 h-8 bg-green-200"></div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pesanan Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @php
                        $steps = ['pending', 'waiting_confirmation', 'processing', 'shipped', 'completed'];
                        $currentIdx = array_search($order->status, $steps);
                        $stepLabels = [
                            'pending' => ['Menunggu Pembayaran', 'Menunggu pembayaran dari Anda'],
                            'waiting_confirmation' => ['Menunggu Konfirmasi', 'Bukti pembayaran sedang diperiksa'],
                            'processing' => ['Diproses', 'Pesanan sedang diproses'],
                            'shipped' => ['Dikirim', 'Pesanan dalam perjalanan'],
                            'completed' => ['Selesai', 'Pesanan telah diterima'],
                        ];
                    @endphp
                    @foreach($steps as $i => $step)
                        @if($step === 'pending') @continue @endif
                        @php
                            $done = $currentIdx !== false && $i <= $currentIdx;
                            $current = $currentIdx !== false && $i === $currentIdx;
                            $next = $currentIdx !== false && $i === $currentIdx + 1;
                        @endphp
                        <div class="flex items-start gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full {{ $done ? 'bg-green-500' : ($current ? 'bg-amber-500' : 'bg-gray-200') }} flex items-center justify-center flex-shrink-0">
                                    <i class="fas {{ $done ? 'fa-check' : ($current ? 'fa-hourglass-half' : 'fa-clock') }} text-white text-[10px]"></i>
                                </div>
                                @if(!$loop->last)
                                    <div class="w-0.5 h-8 {{ $done && !$loop->last ? 'bg-green-200' : 'bg-gray-200' }}"></div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium {{ $done || $current ? 'text-gray-900' : 'text-gray-400' }}">
                                    {{ $stepLabels[$step][0] }}
                                </p>
                                <p class="text-xs {{ $done || $current ? 'text-gray-500' : 'text-gray-400' }}">{{ $stepLabels[$step][1] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- WhatsApp --}}
            @if($wa = App\Models\Setting::getValue('whatsapp_number'))
                <a href="https://wa.me/{{ $wa }}?text=Halo%20saya%20ingin%20tanya%20tentang%20pesanan%20%23{{ $order->order_number }}" target="_blank" class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white p-4 rounded-2xl font-medium transition-colors">
                    <i class="fab fa-whatsapp text-xl"></i> Hubungi CS
                </a>
            @endif
        </div>
    </div>

    {{-- Back --}}
    <div class="mt-6">
        <a href="{{ route('orders.index') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium inline-flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Kembali ke Pesanan
        </a>
    </div>
</div>
@endsection
