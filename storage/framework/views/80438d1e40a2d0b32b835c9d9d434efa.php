<?php $__env->startSection('title', isset($category) ? $category->name : 'Produk'); ?>



<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-6 md:py-8">

    
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="<?php echo e(route('home')); ?>" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <a href="<?php echo e(route('products.index')); ?>" class="hover:text-amber-600 transition-colors">Produk</a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($category)): ?>
            <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
            <span class="text-gray-900 font-medium"><?php echo e($category->name); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </nav>

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">

        
        <aside class="lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-2xl border border-gray-100 p-5 sticky top-28">

                
                <div class="flex items-center justify-between mb-4 lg:hidden" x-data="{ filterOpen: false }">
                    <button @click="filterOpen = !filterOpen" class="flex items-center gap-2 text-sm font-bold text-gray-900">
                        <i class="fas fa-sliders-h"></i> Filter
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="{'rotate-180': filterOpen}"></i>
                    </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['brand', 'min_price', 'max_price', 'sort'])): ?>
                        <a href="<?php echo e(route('products.index', request()->only(['q', 'category']))); ?>" class="text-xs text-amber-600 hover:text-amber-700">Hapus Filter</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div x-data="{ filterOpen: window.innerWidth >= 1024 }" x-init="window.addEventListener('resize', () => { if(window.innerWidth >= 1024) filterOpen = true })">
                    <div x-show="filterOpen" x-collapse.duration.300ms>
                        <form method="GET" action="<?php echo e(route('products.index')); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('q')): ?>
                                <input type="hidden" name="q" value="<?php echo e(request('q')); ?>">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($category)): ?>
                                <input type="hidden" name="category" value="<?php echo e($category->slug); ?>">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-bold text-gray-900 text-sm">Kategori</h3>
                                    <i class="fas fa-folder text-gray-400 text-xs"></i>
                                </div>
                                <ul class="space-y-0.5">
                                    <li>
                                        <a href="<?php echo e(route('products.index', request()->only(['q', 'brand', 'min_price', 'max_price', 'sort']))); ?>" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm <?php echo e(!isset($category) ? 'bg-amber-50 text-amber-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-amber-600'); ?> transition-colors">
                                            <span>Semua Produk</span>
                                        </a>
                                    </li>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <li>
                                            <a href="<?php echo e(route('products.category', $cat->slug)); ?>" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm <?php echo e(isset($category) && $category->id === $cat->id ? 'bg-amber-50 text-amber-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-amber-600'); ?> transition-colors">
                                                <span><?php echo e($cat->name); ?></span>
                                            </a>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cat->children->count() > 0): ?>
                                                <ul class="ml-3 mt-0.5 space-y-0.5">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cat->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                        <li>
                                                            <a href="<?php echo e(route('products.category', $child->slug)); ?>" class="block px-3 py-1.5 rounded-lg text-xs text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors"><?php echo e($child->name); ?></a>
                                                        </li>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                </ul>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </li>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </ul>
                            </div>

                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($brands->count() > 0): ?>
                                <div class="border-t border-gray-100 pt-4 mb-6">
                                    <h3 class="font-bold text-gray-900 text-sm mb-3">Brand</h3>
                                    <div class="space-y-2 max-h-48 overflow-y-auto">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <input type="checkbox" name="brand[]" value="<?php echo e($brand); ?>"
                                                    <?php echo e(in_array($brand, (array) request('brand', [])) ? 'checked' : ''); ?>

                                                    class="w-4 h-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500"
                                                    onchange="this.closest('form').submit()">
                                                <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors"><?php echo e($brand); ?></span>
                                            </label>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            
                            <div class="border-t border-gray-100 pt-4 mb-6">
                                <h3 class="font-bold text-gray-900 text-sm mb-3">Rentang Harga</h3>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="min_price" placeholder="Min" value="<?php echo e(request('min_price')); ?>"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500">
                                    <span class="text-gray-400">-</span>
                                    <input type="number" name="max_price" placeholder="Maks" value="<?php echo e(request('max_price')); ?>"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500">
                                </div>
                                <button type="submit" class="w-full mt-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg font-medium transition-colors">Terapkan</button>
                            </div>

                            
                            <div class="border-t border-gray-100 pt-4">
                                <h3 class="font-bold text-gray-900 text-sm mb-3">Urutkan</h3>
                                <select name="sort" onchange="this.closest('form').submit()"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-amber-500 focus:ring-amber-500">
                                    <option value="newest" <?php echo e(request('sort') == 'newest' ? 'selected' : ''); ?>>Terbaru</option>
                                    <option value="cheapest" <?php echo e(request('sort') == 'cheapest' ? 'selected' : ''); ?>>Termurah</option>
                                    <option value="most_expensive" <?php echo e(request('sort') == 'most_expensive' ? 'selected' : ''); ?>>Termahal</option>
                                    <option value="best_rating" <?php echo e(request('sort') == 'best_rating' ? 'selected' : ''); ?>>Rating Tertinggi</option>
                                </select>
                            </div>

                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['brand', 'min_price', 'max_price', 'sort'])): ?>
                                <div class="border-t border-gray-100 pt-4 mt-4">
                                    <a href="<?php echo e(route('products.index', request()->only(['q', 'category']))); ?>"
                                        class="text-sm text-red-500 hover:text-red-600 font-medium flex items-center justify-center gap-1">
                                        <i class="fas fa-times"></i> Hapus Semua Filter
                                    </a>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        
        <div class="flex-1 min-w-0">

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($category)): ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900"><?php echo e($category->name); ?></h1>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->description): ?>
                        <p class="text-gray-500 text-sm mt-2"><?php echo e($category->description); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php else: ?>
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 mb-6">
                    <h1 class="text-xl md:text-2xl font-bold text-white">Semua Produk</h1>
                    <p class="text-white/80 text-sm mt-1">Temukan sparepart dan aksesoris HP yang Anda butuhkan</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('q')): ?>
                <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-4 text-sm text-amber-700">
                    <i class="fas fa-search mr-2"></i> Hasil pencarian untuk: "<strong><?php echo e(request('q')); ?></strong>" (<?php echo e($products->total()); ?> produk ditemukan)
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($products->count() > 0): ?>
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="product-card">
                            <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="block">
                                <div class="relative aspect-square bg-gray-50 overflow-hidden">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->imageUrl): ?>
                                        <img src="<?php echo e($product->imageUrl); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-contain p-4 hover:scale-105 transition-transform duration-500" loading="lazy">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-mobile-screen text-4xl text-gray-200"></i>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->stock <= 0): ?>
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                            <span class="bg-red-500 text-white px-4 py-1.5 rounded-full text-sm font-bold">Stok Habis</span>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                                        <button onclick="event.preventDefault(); fetch('<?php echo e(route('wishlist.toggle', $product)); ?>', {method:'POST', headers:{'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>','Accept':'application/json'}}).then(r=>r.json()).then(d=>{this.querySelector('i').className=d.status=='added'?'fas fa-heart text-red-500':'far fa-heart text-gray-400'})" class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow-sm hover:bg-white transition-colors">
                                            <i class="far fa-heart text-gray-400"></i>
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="p-3 md:p-4">
                                    <p class="text-xs text-gray-500 mb-1"><?php echo e($product->category->name); ?></p>
                                    <h3 class="text-sm font-medium text-gray-900 line-clamp-2 min-h-[2.5rem] leading-snug"><?php echo e($product->name); ?></h3>
                                    <p class="text-base md:text-lg font-bold text-amber-600 mt-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_promo): ?>
                                            <span class="line-through text-gray-400 text-sm font-normal">Rp <?php echo e(number_format($product->selling_price, 0, ',', '.')); ?></span>
                                            Rp <?php echo e(number_format($product->promo_price, 0, ',', '.')); ?>

                                        <?php else: ?>
                                            Rp <?php echo e(number_format($product->selling_price, 0, ',', '.')); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>
                                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                        <span class="flex items-center gap-1"><i class="fas fa-star text-yellow-400 text-[10px]"></i> <?php echo e(number_format($product->avg_rating, 1)); ?></span>
                                        <span>|</span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->stock > 0): ?>
                                            <span class="text-green-600"><i class="fas fa-check-circle text-[10px]"></i> Tersedia</span>
                                        <?php else: ?>
                                            <span class="text-red-400"><i class="fas fa-times-circle text-[10px]"></i> Habis</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </a>
                            <div class="px-3 md:px-4 pb-3 md:pb-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->stock > 0): ?>
                                    <form action="<?php echo e(route('cart.add', $product)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full text-sm bg-amber-500 hover:bg-amber-600 text-white py-2 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                            <i class="fas fa-shopping-cart text-xs"></i> + Keranjang
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button disabled class="w-full text-sm bg-gray-100 text-gray-400 py-2 rounded-lg font-medium cursor-not-allowed flex items-center justify-center gap-2">
                                        <i class="fas fa-times-circle text-xs"></i> Stok Habis
                                    </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>

                
                <div class="mt-8">
                    <?php echo e($products->appends(request()->query())->links()); ?>

                </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                    <i class="fas fa-box-open text-6xl text-gray-200 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500 mb-6">Maaf, tidak ada produk yang sesuai dengan kriteria Anda.</p>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn-primary inline-flex">Lihat Semua Produk</a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pro021\procell-store\resources\views/store/products/index.blade.php ENDPATH**/ ?>