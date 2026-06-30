<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->hasAnyRole(['KASIR', 'Super Admin', 'Stok', 'Keuangan'])): ?>
                <a href="<?php echo e(route('admin.pos.index')); ?>" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow p-6 flex items-center gap-4">
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-cash-register text-2xl text-amber-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">POS (Point of Sale)</h3>
                        <p class="text-sm text-gray-500">Antarmuka kasir untuk transaksi offline</p>
                    </div>
                </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->hasAnyRole(['Super Admin', 'Stok', 'Keuangan'])): ?>
                <a href="<?php echo e(url('/admin')); ?>" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow p-6 flex items-center gap-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-th-large text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Admin Panel</h3>
                        <p class="text-sm text-gray-500">Manajemen toko, produk, pesanan</p>
                    </div>
                </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <a href="<?php echo e(route('products.index')); ?>" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow p-6 flex items-center gap-4">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-store text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Toko</h3>
                        <p class="text-sm text-gray-500">Lihat toko dari sisi pelanggan</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\pro021\procell-store\resources\views/dashboard.blade.php ENDPATH**/ ?>