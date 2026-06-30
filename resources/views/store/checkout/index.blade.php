@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 md:py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <a href="{{ route('cart.index') }}" class="hover:text-amber-600 transition-colors">Keranjang</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">Checkout</span>
    </nav>

    <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

    {{-- Steps --}}
    <div class="flex items-center gap-2 mb-8">
        <div class="flex items-center gap-2 text-amber-600">
            <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-sm font-bold">1</div>
            <span class="text-sm font-medium">Keranjang</span>
        </div>
        <div class="flex-1 h-0.5 bg-amber-500"></div>
        <div class="flex items-center gap-2 text-amber-600">
            <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-sm font-bold">2</div>
            <span class="text-sm font-medium">Checkout</span>
        </div>
        <div class="flex-1 h-0.5 bg-gray-200"></div>
        <div class="flex items-center gap-2 text-gray-400">
            <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-bold">3</div>
            <span class="text-sm font-medium">Selesai</span>
        </div>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- Left Column --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Shipping Form --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-truck text-amber-500"></i> Detail Pengiriman
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($isGuest)
                        <div>
                            <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="guest_name" id="guest_name" value="{{ old('guest_name') }}" required class="input-field" placeholder="Nama lengkap Anda">
                            @error('guest_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="guest_email" id="guest_email" value="{{ old('guest_email') }}" required class="input-field" placeholder="email@contoh.com">
                            @error('guest_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="guest_phone" class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="guest_phone" id="guest_phone" value="{{ old('guest_phone') }}" required class="input-field" placeholder="0812xxxx">
                            @error('guest_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        @else
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" value="{{ auth()->user()->name }}" readonly class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="text" value="{{ auth()->user()->email }}" readonly class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label>
                            <input type="text" value="{{ auth()->user()->phone ?? '-' }}" readonly class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-500 text-sm">
                        </div>
                        @endif
                        <div>
                            <label for="destination_city" class="block text-sm font-medium text-gray-700 mb-1.5">ID Kota Tujuan <span class="text-xs text-gray-400">(RajaOngkir)</span></label>
                            <input type="number" name="destination_city" id="destination_city" min="1"
                                   class="input-field" placeholder="Contoh: 152 (Jakpus)"
                                   value="{{ old('destination_city') }}">
                            @error('destination_city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required class="input-field">{{ old('shipping_address', auth()->check() ? auth()->user()->address : '') }}</textarea>
                            @error('shipping_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Catatan (opsional)</label>
                            <textarea name="notes" id="notes" rows="2" class="input-field" placeholder="Contoh: Kantor, samping gerbang hijau">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Courier Selection --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-shipping-fast text-amber-500"></i> Pilih Kurir
                    </h2>

                    <div class="space-y-4" x-data="{
                        courier: '{{ old('courier') }}',
                        service: '{{ old('courier_service') }}',
                        loading: false,
                        courierRates: {}
                    }">
                        <template x-if="Object.keys(courierRates).length === 0">
                            <div class="text-center py-8">
                                <i class="fas fa-truck text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">Masukkan ID kota tujuan di atas, lalu klik "Cek Ongkir"</p>
                            </div>
                        </template>

                        <template x-for="(data, key) in courierRates" :key="key">
                            <label class="flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200"
                                   :class="courier === key ? 'border-amber-500 bg-amber-50' : 'border-gray-100 hover:border-gray-200'">
                                <input type="radio" name="courier" :value="key"
                                       x-model="courier"
                                       @change="service = ''"
                                       class="mt-1 w-4 h-4 text-amber-600 focus:ring-amber-500">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-gray-900" x-text="data.name"></span>
                                    </div>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <template x-for="(svc, svcKey) in data.services" :key="svcKey">
                                            <label class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border text-sm cursor-pointer transition-colors duration-200"
                                                   :class="courier === key && service === svcKey ? 'border-amber-500 bg-amber-100 text-amber-800' : 'border-gray-100 bg-gray-50 hover:bg-gray-100'"
                                                   @click.stop="service = svcKey">
                                                <input type="radio" name="courier_service" :value="svcKey"
                                                       x-model="service"
                                                       class="sr-only">
                                                <span class="font-medium" x-text="svcKey"></span>
                                                <span class="text-gray-500" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(svc.cost)"></span>
                                                <span class="text-xs text-gray-400" x-show="svc.etd" x-text="'(' + svc.etd + ' hari)'"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </label>
                        </template>
                        <input type="hidden" name="courier_service" x-bind:value="service">
                        @error('courier') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('courier_service') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-credit-card text-amber-500"></i> Metode Pembayaran
                    </h2>

                    <div class="space-y-4" x-data="{ payment: '{{ old('payment_method') }}' }">
                        <label class="flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200"
                               :class="payment === 'bank_transfer' ? 'border-amber-500 bg-amber-50' : 'border-gray-100 hover:border-gray-200'">
                            <input type="radio" name="payment_method" value="bank_transfer"
                                   x-model="payment"
                                   class="mt-1 w-4 h-4 text-amber-600 focus:ring-amber-500">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-university text-blue-600"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900">Transfer Bank</span>
                                        <p class="text-xs text-gray-500">Bayar via transfer ke rekening kami</p>
                                    </div>
                                </div>

                                {{-- Bank Accounts --}}
                                <div x-show="payment === 'bank_transfer'" x-cloak class="mt-4 pt-4 border-t border-gray-100 space-y-3">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Pilih Bank Tujuan:</p>
                                    @forelse($bankAccounts as $bank)
                                        <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors"
                                               :class="payment === 'bank_transfer' && document.querySelector('input[name=bank_account_id]:checked')?.value === '{{ $bank->id }}' ? 'border-amber-500 bg-amber-50' : 'border-gray-100 hover:border-gray-200'">
                                            <input type="radio" name="bank_account_id" value="{{ $bank->id }}"
                                                   {{ old('bank_account_id') == $bank->id ? 'checked' : '' }}
                                                   class="w-4 h-4 text-amber-600 focus:ring-amber-500">
                                            <div class="flex-1">
                                                <span class="font-bold text-gray-900">{{ $bank->bank_name }}</span>
                                                <p class="text-sm text-gray-600">{{ $bank->account_number }} a.n. <strong>{{ $bank->account_holder }}</strong></p>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-400">Belum ada rekening bank tersedia. Hubungi admin.</p>
                                    @endforelse
                                    @error('bank_account_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </label>

                        <label class="flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200"
                               :class="payment === 'midtrans' ? 'border-amber-500 bg-amber-50' : 'border-gray-100 hover:border-gray-200'">
                            <input type="radio" name="payment_method" value="midtrans"
                                   x-model="payment"
                                   class="mt-1 w-4 h-4 text-amber-600 focus:ring-amber-500">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <i class="fas fa-credit-card text-purple-600"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900">Midtrans</span>
                                        <p class="text-xs text-gray-500">Bayar via kartu, e-wallet, atau virtual account</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                 <div class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-28"
                      x-data="{
                          subtotal: {{ $cart->total }},
                          shippingCost: 0,
                          selectedCourier: '{{ old('courier') }}',
                          selectedService: '{{ old('courier_service') }}',
                          discount: {{ session('applied_coupon.discount') ?? 0 }},
                          appliedCoupon: {{ session()->has('applied_coupon') ? 'true' : 'false' }},
                          couponCode: '{{ session('applied_coupon.code') ?? '' }}',
                          couponMessage: '',
                          loadingRates: false,
                          rajaOngkirConfigured: {{ $rajaOngkirConfigured ? 'true' : 'false' }},
                          totalWeight: {{ $totalWeight }},
                          pointsToUse: {{ old('points_to_use', 0) }},
                          @if(!$isGuest)
                          pointsRate: {{ $loyalty->getRedeemRate() }},
                          get pointsDiscount() {
                              return Math.min(this.pointsToUse * this.pointsRate, this.subtotal * 0.5);
                          },
                          useMaxPoints() {
                              this.pointsToUse = {{ $maxRedeemPoints }};
                          },
                          @else
                          pointsRate: 0,
                          get pointsDiscount() { return 0; },
                          useMaxPoints() {},
                          @endif
                          courierRates: {{ json_encode(collect($couriers)->mapWithKeys(fn($c, $k) => [$k => ['name' => $c['name'], 'services' => collect($c['services'])->map(fn($cost, $svc) => ['cost' => $cost, 'service' => $svc, 'etd' => '-'])->keyBy('service')]]) ) }},
                          get shipping() {
                              if (this.selectedCourier && this.selectedService && this.courierRates[this.selectedCourier]) {
                                  const svc = this.courierRates[this.selectedCourier].services[this.selectedService];
                                  return svc ? svc.cost : 0;
                              }
                              return 0;
                          },
                          get total() { return this.subtotal + this.shipping - this.discount - this.pointsDiscount; },
                         fetchRates() {
                             const cityId = document.getElementById('destination_city')?.value;
                             if (!cityId) { return; }
                             this.loadingRates = true;
                             const weight = this.totalWeight;
                             fetch('{{ route('checkout.courier-rates') }}?destination=' + cityId + '&weight=' + weight)
                                 .then(r => r.json())
                                 .then(d => {
                                     this.courierRates = d.couriers;
                                     this.selectedCourier = '';
                                     this.selectedService = '';
                                     this.shippingCost = 0;
                                     this.loadingRates = false;
                                 })
                                 .catch(() => { this.loadingRates = false; });
                         },
                         applyCoupon() {
                             fetch('{{ route('coupon.apply') }}', {
                                 method: 'POST',
                                 headers: {'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}'},
                                 body: JSON.stringify({coupon_code: this.couponCode})
                             }).then(r=>r.json()).then(d=>{
                                 if(d.success) { this.discount = d.discount; this.appliedCoupon = true; this.couponMessage = ''; }
                                 else { this.couponMessage = d.message; this.appliedCoupon = false; }
                             });
                         },
                         removeCoupon() {
                             fetch('{{ route('coupon.remove') }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})
                             .then(()=>{ this.couponCode = ''; this.discount = 0; this.appliedCoupon = false; this.couponMessage = ''; });
                         }
                     }"
                     x-init="$watch('selectedCourier', val => { shippingCost = 0; selectedService = ''; });
                             $watch('selectedService', val => { shippingCost = shipping; })">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-receipt text-amber-500"></i> Ringkasan Pesanan
                    </h2>

                    <div class="space-y-3 mb-4 max-h-60 overflow-y-auto scrollbar-custom pr-1">
                        @foreach($cart->items as $item)
                            <div class="flex gap-3">
                                <div class="w-12 h-12 bg-gray-50 rounded-lg overflow-hidden flex-shrink-0">
                                    @if($item->product->imageUrl)
                                        <img src="{{ $item->product->imageUrl }}" class="w-full h-full object-contain p-1">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-mobile-screen text-gray-200"></i></div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-900 line-clamp-1">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                <p class="text-xs font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-medium">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between text-sm items-center">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <div class="text-right">
                                <span class="font-medium" x-text="shipping > 0 ? 'Rp ' + new Intl.NumberFormat('id-ID').format(shipping) : (selectedCourier ? 'Pilih layanan' : 'Belum dipilih')"></span>
                                <button @click.prevent="fetchRates()" type="button"
                                        x-show="rajaOngkirConfigured"
                                        class="block mt-1 text-xs text-amber-600 hover:text-amber-700"
                                        x-bind:disabled="loadingRates">
                                    <span x-show="!loadingRates"><i class="fas fa-sync-alt"></i> Cek Ongkir</span>
                                    <span x-show="loadingRates"><i class="fas fa-spinner fa-spin"></i> Memuat...</span>
                                </button>
                            </div>
                        </div>

                        {{-- Coupon --}}
                        <div class="border-t border-gray-100 pt-3 mt-3">
                            <template x-if="!appliedCoupon">
                                <div class="flex gap-2">
                                    <input type="text" x-model="couponCode" placeholder="Masukkan kode kupon"
                                           class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500">
                                    <button @click="applyCoupon()" type="button" class="btn-primary !py-2 !px-4 text-sm">Pakai</button>
                                </div>
                            </template>
                            <template x-if="appliedCoupon">
                                <div class="flex items-center justify-between bg-green-50 rounded-lg p-3">
                                    <div>
                                        <p class="text-sm font-medium text-green-700" x-text="'Kupon: ' + couponCode"></p>
                                        <p class="text-xs text-green-600" x-text="'Diskon: Rp ' + new Intl.NumberFormat('id-ID').format(discount)"></p>
                                    </div>
                                    <button @click="removeCoupon()" type="button" class="text-red-500 hover:text-red-700 text-sm"><i class="fas fa-times"></i></button>
                                </div>
                            </template>
                            <p x-show="couponMessage" x-text="couponMessage" class="text-red-500 text-xs mt-1"></p>
                        </div>

                         <div class="flex justify-between text-sm" x-show="discount > 0">
                             <span class="text-green-600">Diskon Kupon</span>
                             <span class="font-medium text-green-600" x-text="'-Rp ' + new Intl.NumberFormat('id-ID').format(discount)"></span>
                         </div>

                         {{-- Points --}}
                         @if($pointsBalance > 0)
                         <div class="border-t border-gray-100 pt-3 mt-3">
                             <div class="flex items-center justify-between mb-2">
                                 <span class="text-sm font-medium text-gray-700"><i class="fas fa-coins text-amber-500 mr-1"></i> Poin Saya</span>
                                 <span class="text-sm font-bold text-amber-600">{{ number_format($pointsBalance, 0, ',', '.') }} poin</span>
                             </div>
                             <p class="text-xs text-gray-400 mb-2">1 poin = Rp {{ number_format($loyalty->getRedeemRate(), 0, ',', '.') }} (maks 50% total)</p>
                             <div class="flex gap-2">
                                 <input type="number" name="points_to_use" x-model="pointsToUse" min="0" max="{{ $maxRedeemPoints }}"
                                        class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500"
                                        placeholder="0">
                                 <button @click="useMaxPoints()" type="button" class="text-xs text-amber-600 hover:text-amber-700 font-medium px-2">Max</button>
                             </div>
                             <p class="text-xs text-gray-400 mt-1">Minimal {{ number_format($loyalty->getMinRedeem(), 0, ',', '.') }} poin</p>
                         </div>
                         @endif
                     </div>

                     <div class="flex justify-between text-sm" x-show="pointsDiscount > 0">
                         <span class="text-blue-600">Diskon Poin</span>
                         <span class="font-medium text-blue-600" x-text="'-Rp ' + new Intl.NumberFormat('id-ID').format(pointsDiscount)"></span>
                     </div>

                    <div class="border-t border-gray-100 pt-4 mt-2">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="text-xl font-bold text-amber-600" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(total)">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full mt-6">
                        <i class="fas fa-check-circle"></i> Buat Pesanan
                    </button>

                    <p class="text-xs text-gray-400 text-center mt-3">
                        <i class="fas fa-lock text-green-500"></i> Data Anda aman & terenkripsi
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush
