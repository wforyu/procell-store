<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-6 md:py-8">

    
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="<?php echo e(route('home')); ?>" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <a href="<?php echo e(route('cart.index')); ?>" class="hover:text-amber-600 transition-colors">Keranjang</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">Checkout</span>
    </nav>

    <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

    
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

    <form action="<?php echo e(route('checkout.process')); ?>" method="POST" id="checkoutForm">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            
            <div class="lg:col-span-2 space-y-6">

                
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-truck text-amber-500"></i> Detail Pengiriman
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" value="<?php echo e(auth()->user()->name); ?>" readonly class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="text" value="<?php echo e(auth()->user()->email); ?>" readonly class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label>
                            <input type="text" value="<?php echo e(auth()->user()->phone ?? '-'); ?>" readonly class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-500 text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Pengiriman <span class="text-red-500">*</span></label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required class="input-field"><?php echo e(old('shipping_address', auth()->user()->address)); ?></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['shipping_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Catatan (opsional)</label>
                            <textarea name="notes" id="notes" rows="2" class="input-field" placeholder="Contoh: Kantor, samping gerbang hijau"><?php echo e(old('notes')); ?></textarea>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-shipping-fast text-amber-500"></i> Pilih Kurir
                    </h2>

                    <div class="space-y-4" x-data="{ courier: '<?php echo e(old('courier')); ?>', service: '<?php echo e(old('courier_service')); ?>' }">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $couriers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $courier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <label class="flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200"
                                   :class="courier === '<?php echo e($key); ?>' ? 'border-amber-500 bg-amber-50' : 'border-gray-100 hover:border-gray-200'">
                                <input type="radio" name="courier" value="<?php echo e($key); ?>"
                                       x-model="courier"
                                       @change="service = ''"
                                       class="mt-1 w-4 h-4 text-amber-600 focus:ring-amber-500">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-gray-900"><?php echo e($courier['name']); ?></span>
                                    </div>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $courier['services']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svcKey => $svcCost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <label class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border text-sm cursor-pointer transition-colors duration-200"
                                                   :class="courier === '<?php echo e($key); ?>' && service === '<?php echo e($svcKey); ?>' ? 'border-amber-500 bg-amber-100 text-amber-800' : 'border-gray-100 bg-gray-50 hover:bg-gray-100'"
                                                   @click.stop="service = '<?php echo e($svcKey); ?>'">
                                                <input type="radio" name="courier_service" value="<?php echo e($svcKey); ?>"
                                                       x-model="service"
                                                       class="sr-only">
                                                <span class="font-medium"><?php echo e($svcKey); ?></span>
                                                <span class="text-gray-500">Rp <?php echo e(number_format($svcCost, 0, ',', '.')); ?></span>
                                            </label>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </div>
                                </div>
                            </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <input type="hidden" name="courier_service" x-bind:value="service">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['courier'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['courier_service'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-credit-card text-amber-500"></i> Metode Pembayaran
                    </h2>

                    <div class="space-y-4" x-data="{ payment: '<?php echo e(old('payment_method')); ?>' }">
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

                                
                                <div x-show="payment === 'bank_transfer'" x-cloak class="mt-4 pt-4 border-t border-gray-100 space-y-3">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Pilih Bank Tujuan:</p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors"
                                               :class="payment === 'bank_transfer' && document.querySelector('input[name=bank_account_id]:checked')?.value === '<?php echo e($bank->id); ?>' ? 'border-amber-500 bg-amber-50' : 'border-gray-100 hover:border-gray-200'">
                                            <input type="radio" name="bank_account_id" value="<?php echo e($bank->id); ?>"
                                                   <?php echo e(old('bank_account_id') == $bank->id ? 'checked' : ''); ?>

                                                   class="w-4 h-4 text-amber-600 focus:ring-amber-500">
                                            <div class="flex-1">
                                                <span class="font-bold text-gray-900"><?php echo e($bank->bank_name); ?></span>
                                                <p class="text-sm text-gray-600"><?php echo e($bank->account_number); ?> a.n. <strong><?php echo e($bank->account_holder); ?></strong></p>
                                            </div>
                                        </label>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <p class="text-sm text-gray-400">Belum ada rekening bank tersedia. Hubungi admin.</p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['bank_account_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-28"
                     x-data="{
                         subtotal: <?php echo e($cart->total); ?>,
                         shippingCost: 0,
                         courierRates: <?php echo e(json_encode(collect($couriers)->mapWithKeys(fn($c, $k) => [$k => $c['services']]))); ?>,
                         selectedCourier: '<?php echo e(old('courier')); ?>',
                         selectedService: '<?php echo e(old('courier_service')); ?>',
                         discount: <?php echo e(session('applied_coupon.discount') ?? 0); ?>,
                         appliedCoupon: <?php echo e(session()->has('applied_coupon') ? 'true' : 'false'); ?>,
                         couponCode: '<?php echo e(session('applied_coupon.code') ?? ''); ?>',
                         couponMessage: '',
                         get shipping() {
                             if (this.selectedCourier && this.selectedService && this.courierRates[this.selectedCourier]) {
                                 return this.courierRates[this.selectedCourier][this.selectedService] || 0;
                             }
                             return 0;
                         },
                         get total() { return this.subtotal + this.shipping - this.discount; },
                         applyCoupon() {
                             fetch('<?php echo e(route('coupon.apply')); ?>', {
                                 method: 'POST',
                                 headers: {'Content-Type':'application/json', 'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'},
                                 body: JSON.stringify({coupon_code: this.couponCode})
                             }).then(r=>r.json()).then(d=>{
                                 if(d.success) { this.discount = d.discount; this.appliedCoupon = true; this.couponMessage = ''; }
                                 else { this.couponMessage = d.message; this.appliedCoupon = false; }
                             });
                         },
                         removeCoupon() {
                             fetch('<?php echo e(route('coupon.remove')); ?>', {method:'POST', headers:{'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'}})
                             .then(()=>{ this.couponCode = ''; this.discount = 0; this.appliedCoupon = false; this.couponMessage = ''; });
                         }
                     }"
                     x-init="$watch('selectedCourier', val => { shippingCost = 0; selectedService = ''; });
                             $watch('selectedService', val => { shippingCost = shipping; })">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-receipt text-amber-500"></i> Ringkasan Pesanan
                    </h2>

                    <div class="space-y-3 mb-4 max-h-60 overflow-y-auto scrollbar-custom pr-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="flex gap-3">
                                <div class="w-12 h-12 bg-gray-50 rounded-lg overflow-hidden flex-shrink-0">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->product->imageUrl): ?>
                                        <img src="<?php echo e($item->product->imageUrl); ?>" class="w-full h-full object-contain p-1">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-mobile-screen text-gray-200"></i></div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-900 line-clamp-1"><?php echo e($item->product->name); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($item->quantity); ?> x Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?></p>
                                </div>
                                <p class="text-xs font-semibold text-gray-900">Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></p>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-medium">Rp <?php echo e(number_format($cart->total, 0, ',', '.')); ?></span>
                        </div>

                        
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

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-medium" x-text="shipping > 0 ? 'Rp ' + new Intl.NumberFormat('id-ID').format(shipping) : (selectedCourier ? 'Pilih layanan' : 'Belum dipilih')"></span>
                        </div>
                        <div class="flex justify-between text-sm" x-show="discount > 0">
                            <span class="text-green-600">Diskon Kupon</span>
                            <span class="font-medium text-green-600" x-text="'-Rp ' + new Intl.NumberFormat('id-ID').format(discount)"></span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mt-2">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="text-xl font-bold text-amber-600" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(total)">Rp <?php echo e(number_format($cart->total, 0, ',', '.')); ?></span>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    [x-cloak] { display: none !important; }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pro021\procell-store\resources\views/store/checkout/index.blade.php ENDPATH**/ ?>