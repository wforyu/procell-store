<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-6 md:py-8">

    
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="<?php echo e(route('home')); ?>" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">Pesanan Saya</span>
    </nav>

    <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
        <i class="fas fa-clipboard-list text-amber-500"></i> Pesanan Saya
    </h1>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($orders->count() > 0): ?>
        <div class="space-y-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <a href="<?php echo e(route('orders.show', $order)); ?>" class="block bg-white rounded-2xl border border-gray-100 p-4 md:p-6 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-sm">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">#<?php echo e($order->order_number); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($order->created_at->format('d/m/Y H:i')); ?></p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold self-start sm:self-auto
                            <?php if($order->status == 'pending'): ?> bg-yellow-100 text-yellow-800
                            <?php elseif($order->status == 'waiting_confirmation'): ?> bg-orange-100 text-orange-800
                            <?php elseif($order->status == 'processing'): ?> bg-blue-100 text-blue-800
                            <?php elseif($order->status == 'shipped'): ?> bg-purple-100 text-purple-800
                            <?php elseif($order->status == 'completed'): ?> bg-green-100 text-green-800
                            <?php else: ?> bg-red-100 text-red-800
                            <?php endif; ?>">
                            <i class="fas
                                <?php if($order->status == 'pending'): ?> fa-clock
                                <?php elseif($order->status == 'waiting_confirmation'): ?> fa-hourglass-half
                                <?php elseif($order->status == 'processing'): ?> fa-spinner fa-spin
                                <?php elseif($order->status == 'shipped'): ?> fa-truck
                                <?php elseif($order->status == 'completed'): ?> fa-check-circle
                                <?php else: ?> fa-times-circle
                                <?php endif; ?>
                            "></i>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status == 'pending'): ?> Menunggu Pembayaran
                            <?php elseif($order->status == 'waiting_confirmation'): ?> Menunggu Konfirmasi
                            <?php elseif($order->status == 'processing'): ?> Diproses
                            <?php elseif($order->status == 'shipped'): ?> Dikirim
                            <?php elseif($order->status == 'completed'): ?> Selesai
                            <?php else: ?> Dibatalkan
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </span>
                    </div>

                    
                    <div class="flex gap-2 mb-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->items->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="w-12 h-12 bg-gray-50 rounded-lg overflow-hidden border border-gray-100">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->product->imageUrl): ?>
                                    <img src="<?php echo e($item->product->imageUrl); ?>" class="w-full h-full object-contain p-1">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-mobile-screen text-gray-200 text-sm"></i></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->items->count() > 3): ?>
                            <div class="w-12 h-12 bg-gray-50 rounded-lg border border-gray-100 flex items-center justify-center text-xs text-gray-500 font-medium">
                                +<?php echo e($order->items->count() - 3); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="flex-1"></div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500"><?php echo e($order->items->count()); ?> barang</p>
                            <p class="font-bold text-amber-600">Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></p>
                        </div>
                    </div>
                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        <div class="mt-6">
            <?php echo e($orders->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-16 md:py-20 bg-white rounded-2xl border border-gray-100">
            <div class="w-24 h-24 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-box-open text-4xl text-gray-300"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Anda belum memiliki pesanan apapun. Ayo mulai belanja sekarang!</p>
            <a href="<?php echo e(route('products.index')); ?>" class="btn-primary inline-flex">
                <i class="fas fa-store"></i> Mulai Belanja
            </a>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pro021\procell-store\resources\views\store\orders\index.blade.php ENDPATH**/ ?>