<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto px-4 py-12 md:py-16">
    <div class="bg-white rounded-2xl border border-gray-100 p-6 md:p-10 text-center animate-fade-in">

        
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check text-3xl text-green-600"></i>
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Pesanan Berhasil Dibuat!</h1>
        <p class="text-gray-500 mb-8">Terima kasih telah berbelanja di <strong class="text-amber-600">ProCell Store</strong></p>

        
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 mb-8 text-left">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">No. Pesanan</span>
                    <span class="font-bold text-gray-900">#<?php echo e($order->order_number); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Tanggal</span>
                    <span class="font-medium text-gray-900"><?php echo e($order->created_at->format('d/m/Y H:i')); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Status</span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock"></i> Menunggu Pembayaran
                    </span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->shipping_cost > 0): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Ongkos Kirim</span>
                        <span class="font-medium text-gray-900">Rp <?php echo e(number_format($order->shipping_cost, 0, ',', '.')); ?></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="border-t border-amber-200/50 pt-3 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Total Pembayaran</span>
                    <span class="text-xl font-bold text-amber-600">Rp <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?></span>
                </div>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->payment_method === 'bank_transfer' && $bankAccounts->count() > 0): ?>
            <div class="bg-white rounded-2xl border border-amber-200 p-6 mb-8 text-left">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-university text-amber-500"></i> Silakan Transfer ke Rekening Berikut
                </h3>
                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <div>
                                <p class="font-bold text-gray-900"><?php echo e($bank->bank_name); ?></p>
                                <p class="text-sm text-gray-600"><?php echo e($bank->account_number); ?> a.n. <?php echo e($bank->account_holder); ?></p>
                            </div>
                            <button onclick="navigator.clipboard.writeText('<?php echo e($bank->account_number); ?>')" class="text-xs text-amber-600 hover:text-amber-700 font-medium px-3 py-1.5 rounded-lg border border-amber-200 hover:bg-amber-50 transition-colors">
                                <i class="fas fa-copy"></i> Salin
                            </button>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
                <p class="text-xs text-gray-500 mt-3 flex items-center gap-1">
                    <i class="fas fa-info-circle text-amber-500"></i>
                    Konfirmasi pembayaran dengan mengunggah bukti transfer di halaman detail pesanan.
                </p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="bg-gray-50 rounded-2xl p-6 mb-8 text-left">
            <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <i class="fas fa-info-circle text-amber-500"></i> Langkah Selanjutnya
            </h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                    <span>Lakukan pembayaran sesuai total yang tertera</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                    <span>Unggah bukti transfer di halaman detail pesanan</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                    <span>Tim kami akan memproses pesanan setelah pembayaran dikonfirmasi</span>
                </li>
            </ul>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isGuest): ?>
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 mb-8 text-left">
            <h3 class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
                <i class="fas fa-user-plus text-amber-500"></i> Simpan Pesanan Anda
            </h3>
            <p class="text-sm text-gray-600 mb-4">
                Buat akun untuk melacak pesanan, mendapatkan poin loyalitas, dan kemudahan belanja berikutnya.
                Gunakan email <strong><?php echo e($order->customer->email); ?></strong> saat mendaftar agar pesanan Anda tertaut.
            </p>
            <a href="<?php echo e(route('register')); ?>" class="btn-primary inline-flex items-center gap-2">
                <i class="fas fa-user-plus"></i> Buat Akun Sekarang
            </a>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isGuest): ?>
            <a href="<?php echo e(route('orders.show', $order)); ?>" class="btn-primary">
                <i class="fas fa-clipboard-list"></i> Detail Pesanan
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <a href="<?php echo e(route('products.index')); ?>" class="btn-outline">
                <i class="fas fa-store"></i> Belanja Lagi
            </a>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wa = App\Models\Setting::getValue('whatsapp_number')): ?>
            <div class="mt-6 p-4 bg-green-50 rounded-xl">
                <p class="text-sm text-gray-600 mb-2">Ada pertanyaan tentang pesanan? Hubungi kami via WhatsApp</p>
                <a href="https://wa.me/<?php echo e($wa); ?>?text=Halo%20saya%20ingin%20tanya%20tentang%20pesanan%20%23<?php echo e($order->order_number); ?>" target="_blank" class="inline-flex items-center gap-2 text-green-600 font-medium hover:text-green-700 transition-colors">
                    <i class="fab fa-whatsapp text-xl"></i> Chat via WhatsApp
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pro021\procell-store\resources\views\store\checkout\success.blade.php ENDPATH**/ ?>