<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $posCart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
    <div class="pos-cart-item" data-pid="<?php echo e($item['product_id']); ?>">
        <div class="item-info">
            <div class="item-name"><?php echo e($item['name']); ?></div>
            <div class="item-price">Rp <?php echo e(number_format($item['price'], 0, ',', '.')); ?></div>
        </div>
        <div class="qty-control">
            <button onclick="updateQty(<?php echo e($item['product_id']); ?>, -1)"><i class="fas fa-minus"></i></button>
            <span class="qty-value" data-pid="<?php echo e($item['product_id']); ?>"><?php echo e($item['quantity']); ?></span>
            <button onclick="updateQty(<?php echo e($item['product_id']); ?>, 1)"><i class="fas fa-plus"></i></button>
        </div>
        <div class="item-subtotal">Rp <?php echo e(number_format($item['price'] * $item['quantity'], 0, ',', '.')); ?></div>
        <button class="item-remove" onclick="removeProduct(<?php echo e($item['product_id']); ?>)"><i class="fas fa-times"></i></button>
    </div>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    <div class="pos-cart-empty">
        <i class="fas fa-shopping-cart"></i>
        <p>Keranjang kosong</p>
        <p style="font-size:0.75rem;margin-top:0.5rem">Klik produk untuk menambahkan</p>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\pro021\procell-store\resources\views/admin/pos/_cart.blade.php ENDPATH**/ ?>