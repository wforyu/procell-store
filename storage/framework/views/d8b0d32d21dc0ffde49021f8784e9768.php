<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php
        $storeName = App\Models\Setting::getValue('store_name', 'ProCell Store');
        $storeDesc = App\Models\Setting::getValue('store_description', 'Toko Sparepart & Aksesoris HP Terlengkap');
        $metaDesc = App\Models\Setting::getValue('meta_description', 'Toko sparepart dan aksesoris HP terlengkap.');
        $metaKeywords = App\Models\Setting::getValue('meta_keywords', 'sparepart hp, aksesoris hp');
        $pageTitle = trim(View::yieldContent('title'));
        $fullTitle = $pageTitle ? "$pageTitle - $storeName" : "$storeName - $storeDesc";
        $storePhone = App\Models\Setting::getValue('store_phone', '0812-3456-7890');
        $storeHours = App\Models\Setting::getValue('store_hours', 'Sen-Sab 08:00 - 17:00');
        $storeIsClosed = App\Models\Setting::getValue('store_is_closed', 'false') === 'true';
        $storeClosedMessage = App\Models\Setting::getValue('store_closed_message', 'Toko sedang libur');
        $flashSaleText = App\Models\Setting::getValue('flash_sale_text', '');
        $orgSchema = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $storeName,
            'description' => $storeDesc,
            'url' => url('/'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+' . App\Models\Setting::getValue('whatsapp_number', '6281234567890'),
                'contactType' => 'customer service',
            ],
        ]);
    ?>
    <title><?php echo e($fullTitle); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', $metaDesc); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords', $metaKeywords); ?>">
    <meta property="og:title" content="<?php echo e($fullTitle); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('meta_description', $metaDesc); ?>">
    <meta property="og:image" content="<?php echo $__env->yieldContent('og_image', asset('favicon.svg')); ?>">
    <meta property="og:type" content="<?php echo $__env->yieldContent('og_type', 'website'); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta name="robots" content="index, follow">
    <?php echo $__env->yieldContent('breadcrumb_schema'); ?>
    <?php echo $__env->yieldContent('product_schema'); ?>
    <script type="application/ld+json"><?php echo e($orgSchema); ?></script>

    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50" x-data="{ mobileMenu: false, searchOpen: false, cartCount: <?php echo e($cartCount ?? 0); ?> }">

    
    <div x-show="mobileMenu" x-cloak x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 z-50" @click="mobileMenu = false"></div>

    
    <div x-show="mobileMenu" x-cloak x-transition:enter="transition-transform ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed left-0 top-0 bottom-0 w-80 bg-white z-50 overflow-y-auto shadow-2xl">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <span class="text-xl font-bold"><span class="text-amber-500">ProCell</span> Store</span>
            <button @click="mobileMenu = false" class="p-2 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-4 border-b border-gray-100">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-semibold"><?php echo e(substr(auth()->user()->name, 0, 1)); ?></div>
                    <div>
                        <p class="font-medium text-gray-900"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e(auth()->user()->email); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="flex gap-2">
                    <a href="<?php echo e(route('login')); ?>" class="btn-primary text-sm flex-1 px-4 py-2">Masuk</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn-outline text-sm flex-1 px-4 py-2">Daftar</a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <nav class="p-4 space-y-1">
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-amber-50 hover:text-amber-600 font-medium transition-colors">
                <i class="fas fa-home w-5 text-center text-gray-400"></i> Beranda
            </a>
            <a href="<?php echo e(route('products.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-amber-50 hover:text-amber-600 font-medium transition-colors">
                <i class="fas fa-box w-5 text-center text-gray-400"></i> Semua Produk
            </a>
            <?php ($mobileCategories = App\Models\Category::active()->parents()->with('children')->get()); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $mobileCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg text-gray-700 hover:bg-amber-50 hover:text-amber-600 font-medium transition-colors">
                        <span class="flex items-center gap-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($cat->slug):
                                case ('lcd-display'): ?> <i class="fas fa-mobile-screen w-5 text-center text-gray-400"></i> <?php break; ?>
                                <?php case ('battery'): ?> <i class="fas fa-battery-three-quarters w-5 text-center text-gray-400"></i> <?php break; ?>
                                <?php case ('flexible-cable'): ?> <i class="fas fa-plug w-5 text-center text-gray-400"></i> <?php break; ?>
                                <?php case ('mainboard-ic'): ?> <i class="fas fa-microchip w-5 text-center text-gray-400"></i> <?php break; ?>
                                <?php case ('button-switch'): ?> <i class="fas fa-toggle-on w-5 text-center text-gray-400"></i> <?php break; ?>
                                <?php case ('charger-adapter'): ?> <i class="fas fa-charging-station w-5 text-center text-gray-400"></i> <?php break; ?>
                                <?php case ('data-cable'): ?> <i class="fas fa-cable-car w-5 text-center text-gray-400"></i> <?php break; ?>
                                <?php default: ?> <i class="fas fa-folder w-5 text-center text-gray-400"></i>
                            <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php echo e($cat->name); ?>

                        </span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cat->children->count() > 0): ?>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cat->children->count() > 0): ?>
                        <div x-show="open" x-cloak x-transition class="ml-8 mt-1 space-y-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cat->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <a href="<?php echo e(route('products.category', $child->slug)); ?>" class="block px-3 py-2 text-sm text-gray-600 hover:text-amber-600 rounded-lg hover:bg-amber-50"><?php echo e($child->name); ?></a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="border-t border-gray-100 my-3 pt-3">
                <a href="<?php echo e(route('cart.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-amber-50 hover:text-amber-600 font-medium transition-colors">
                    <i class="fas fa-shopping-cart w-5 text-center text-gray-400"></i> Keranjang
                </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('orders.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-amber-50 hover:text-amber-600 font-medium transition-colors">
                        <i class="fas fa-clipboard-list w-5 text-center text-gray-400"></i> Pesanan Saya
                    </a>
                    <a href="<?php echo e(route('wishlist.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-amber-50 hover:text-amber-600 font-medium transition-colors">
                        <i class="fas fa-heart w-5 text-center text-red-400"></i> Wishlist
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </nav>
    </div>

    <div class="min-h-screen flex flex-col">

        
        <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white text-xs sm:text-sm">
            <div class="max-w-7xl mx-auto px-4 h-9 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="hidden sm:flex items-center gap-1.5 text-gray-300">
                        <i class="fas fa-phone-alt text-amber-400 text-xs"></i>
                        <?php echo e($storePhone); ?>

                    </span>
                    <span class="hidden md:flex items-center gap-1.5 text-gray-300">
                        <i class="<?php echo e($storeIsClosed ? 'fas fa-store-slash' : 'far fa-clock'); ?> text-amber-400 text-xs"></i>
                        <?php echo e($storeIsClosed ? $storeClosedMessage : $storeHours); ?>

                    </span>
                </div>
                <div class="flex items-center gap-1 sm:gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flashSaleText): ?>
                        <span class="hidden sm:inline-flex items-center gap-1.5 text-amber-400 font-medium">
                            <i class="fas fa-bolt"></i> <?php echo e($flashSaleText); ?>

                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('orders.index')); ?>" class="hover:text-amber-400 transition-colors flex items-center gap-1"><i class="fas fa-box"></i> <span class="hidden sm:inline">Pesanan</span></a>
                        <span class="text-gray-600">|</span>
                        <a href="<?php echo e(route('wishlist.index')); ?>" class="hover:text-amber-400 transition-colors flex items-center gap-1"><i class="fas fa-heart"></i> <span class="hidden sm:inline">Wishlist</span></a>
                        <span class="text-gray-600">|</span>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="hover:text-amber-400 transition-colors flex items-center gap-1"><i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Keluar</span></button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="hover:text-amber-400 transition-colors flex items-center gap-1"><i class="fas fa-user"></i> <span class="hidden sm:inline">Masuk</span></a>
                        <span class="text-gray-600">|</span>
                        <a href="<?php echo e(route('register')); ?>" class="hover:text-amber-400 transition-colors flex items-center gap-1"><i class="fas fa-user-plus"></i> <span class="hidden sm:inline">Daftar</span></a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        
        <header class="bg-white shadow-md sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex items-center h-16 md:h-20 gap-3 md:gap-6">

                    
                    <button @click="mobileMenu = true" class="md:hidden p-2 -ml-2 text-gray-600 hover:text-amber-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    
                    <a href="<?php echo e(route('home')); ?>" class="flex-shrink-0 flex items-center gap-2">
                        <div class="w-9 h-9 md:w-10 md:h-10 rounded-lg gradient-primary flex items-center justify-center text-white font-bold text-sm md:text-base">PC</div>
                        <div class="hidden xs:block">
                            <span class="text-lg md:text-xl font-bold text-gray-900">ProCell</span>
                            <span class="text-lg md:text-xl font-bold text-amber-500">Store</span>
                        </div>
                    </a>

                    
                    <div class="flex-1 max-w-2xl mx-auto" x-data="{ query: '<?php echo e(request('q')); ?>' }">
                        <form action="<?php echo e(route('products.index')); ?>" method="GET" class="relative">
                            <div class="flex">
                                <div class="relative flex-1">
                                    <input
                                        type="text"
                                        name="q"
                                        x-model="query"
                                        placeholder="Cari LCD, baterai, charger..."
                                        class="w-full h-10 md:h-12 pl-4 pr-10 border-2 border-gray-200 rounded-l-xl focus:border-amber-500 focus:ring-0 text-sm outline-none transition-colors"
                                        value="<?php echo e(request('q')); ?>"
                                    >
                                    <button type="button" x-show="query.length > 0" @click="query = ''; $el.closest('form').querySelector('input[name=q]').value = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                                <button type="submit" class="h-10 md:h-12 px-5 md:px-8 gradient-primary text-white rounded-r-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 flex items-center gap-2 font-medium">
                                    <i class="fas fa-search"></i>
                                    <span class="hidden sm:inline text-sm">Cari</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    
                    <div class="flex items-center gap-2 md:gap-4 flex-shrink-0">

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wa = App\Models\Setting::getValue('whatsapp_number')): ?>
                            <a href="https://wa.me/<?php echo e($wa); ?>" target="_blank" class="hidden lg:flex items-center gap-2 px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                                <i class="fab fa-whatsapp text-lg"></i>
                                <span class="hidden xl:inline">CS WhatsApp</span>
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <a href="<?php echo e(route('cart.index')); ?>" class="relative p-2 text-gray-600 hover:text-amber-500 transition-colors">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span x-text="cartCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold min-w-[18px] h-[18px] flex items-center justify-center rounded-full"></span>
                        </a>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                            <div class="hidden md:relative md:flex" x-data="{ userOpen: false }">
                                <button @click="userOpen = !userOpen" @click.outside="userOpen = false" class="flex items-center gap-2 p-2 text-gray-600 hover:text-amber-500 transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-semibold text-sm"><?php echo e(substr(auth()->user()->name, 0, 1)); ?></div>
                                    <span class="hidden lg:inline text-sm font-medium"><?php echo e(auth()->user()->name); ?></span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                <div x-show="userOpen" x-cloak @click.outside="userOpen = false" x-transition class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="font-medium text-gray-900 text-sm"><?php echo e(auth()->user()->name); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo e(auth()->user()->email); ?></p>
                                    </div>
                                    <a href="<?php echo e(route('orders.index')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                        <i class="fas fa-clipboard-list w-4 text-center text-gray-400"></i> Pesanan Saya
                                    </a>
                                    <a href="<?php echo e(route('wishlist.index')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                        <i class="fas fa-heart w-4 text-center text-red-400"></i> Wishlist
                                    </a>
                                    <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                        <i class="fas fa-user-cog w-4 text-center text-gray-400"></i> Profil
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="hidden md:flex items-center gap-2 px-4 py-2 border-2 border-amber-500 text-amber-600 rounded-lg hover:bg-amber-50 transition-colors text-sm font-semibold">
                                <i class="fas fa-user"></i> Masuk
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            
            <nav class="hidden md:block border-t border-gray-100 bg-white">
                <div class="max-w-7xl mx-auto px-4 flex items-center h-11">
                    
                    <div class="relative" x-data="{ catOpen: false }" @mouseenter="catOpen = true" @mouseleave="catOpen = false">
                        <button class="flex items-center gap-2 h-11 px-4 bg-amber-500 text-white font-semibold text-sm rounded-t hover:bg-amber-600 transition-colors">
                            <i class="fas fa-bars"></i>
                            <span>Kategori</span>
                            <i class="fas fa-chevron-down text-xs ml-1" :class="{'rotate-180': catOpen}"></i>
                        </button>
                        <div x-show="catOpen" x-cloak x-transition class="absolute left-0 top-full w-[700px] bg-white rounded-b-xl shadow-2xl border border-gray-100 grid grid-cols-3 p-6 gap-6 z-50">
                            <?php ($navCategories = App\Models\Category::active()->parents()->with('children')->get()); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $navCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div>
                                    <a href="<?php echo e(route('products.category', $cat->slug)); ?>" class="flex items-center gap-2 font-semibold text-gray-900 hover:text-amber-600 mb-2 text-sm">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($cat->slug):
                                            case ('lcd-display'): ?> <i class="fas fa-mobile-screen text-amber-500"></i> <?php break; ?>
                                            <?php case ('battery'): ?> <i class="fas fa-battery-three-quarters text-amber-500"></i> <?php break; ?>
                                            <?php case ('flexible-cable'): ?> <i class="fas fa-plug text-amber-500"></i> <?php break; ?>
                                            <?php case ('mainboard-ic'): ?> <i class="fas fa-microchip text-amber-500"></i> <?php break; ?>
                                            <?php case ('button-switch'): ?> <i class="fas fa-toggle-on text-amber-500"></i> <?php break; ?>
                                            <?php case ('charger-adapter'): ?> <i class="fas fa-charging-station text-amber-500"></i> <?php break; ?>
                                            <?php case ('data-cable'): ?> <i class="fas fa-cable-car text-amber-500"></i> <?php break; ?>
                                            <?php default: ?> <i class="fas fa-folder text-amber-500"></i>
                                        <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php echo e($cat->name); ?>

                                    </a>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cat->children->count() > 0): ?>
                                        <ul class="space-y-1">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cat->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <li>
                                                    <a href="<?php echo e(route('products.category', $child->slug)); ?>" class="text-xs text-gray-600 hover:text-amber-600 transition-colors"><?php echo e($child->name); ?></a>
                                                </li>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </ul>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="flex items-center gap-1 ml-4 overflow-x-auto scrollbar-hide">
                        <a href="<?php echo e(route('home')); ?>" class="nav-link px-3 h-11 flex items-center text-sm <?php echo e(request()->routeIs('home') ? 'nav-link-active' : ''); ?>">Beranda</a>
                        <a href="<?php echo e(route('products.index')); ?>" class="nav-link px-3 h-11 flex items-center text-sm <?php echo e(request()->routeIs('products.*') ? 'nav-link-active' : ''); ?>">Semua Produk</a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $navCategories->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <a href="<?php echo e(route('products.category', $cat->slug)); ?>" class="nav-link px-3 h-11 flex items-center text-sm whitespace-nowrap"><?php echo e($cat->name); ?></a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <a href="<?php echo e(route('cart.index')); ?>" class="nav-link px-3 h-11 flex items-center text-sm">Keranjang</a>
                    </div>
                </div>
            </nav>
        </header>

        
        <main class="flex-1">
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="max-w-7xl mx-auto px-4 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3 text-sm">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="flex-1"><?php echo e(session('success')); ?></span>
                        <button @click="show = false" class="text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                <div class="max-w-7xl mx-auto px-4 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3 text-sm">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span class="flex-1"><?php echo e(session('error')); ?></span>
                        <button @click="show = false" class="text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($slot)): ?>
                <?php echo e($slot); ?>

            <?php else: ?>
                <?php echo $__env->yieldContent('content'); ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </main>

        
        <footer class="bg-gray-900 text-gray-300 mt-16">
            
            <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-9 h-9 rounded-lg gradient-primary flex items-center justify-center text-white font-bold text-sm">PC</div>
                        <span class="text-lg font-bold text-white">ProCell <span class="text-amber-500">Store</span></span>
                    </div>
                    <p class="text-sm leading-relaxed text-gray-400 mb-4"><?php echo e(App\Models\Setting::getValue('footer_description', 'Toko sparepart dan aksesoris HP terpercaya. Menyediakan berbagai macam suku cadang smartphone original dan berkualitas.')); ?></p>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-amber-500 transition-colors text-gray-400 hover:text-white"><i class="fab fa-facebook-f text-sm"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-amber-500 transition-colors text-gray-400 hover:text-white"><i class="fab fa-instagram text-sm"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-amber-500 transition-colors text-gray-400 hover:text-white"><i class="fab fa-tiktok text-sm"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-amber-500 transition-colors text-gray-400 hover:text-white"><i class="fab fa-youtube text-sm"></i></a>
                    </div>
                </div>

                
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Kategori</h3>
                    <ul class="space-y-2.5">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = App\Models\Category::active()->parents()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <li><a href="<?php echo e(route('products.category', $cat->slug)); ?>" class="text-sm text-gray-400 hover:text-amber-400 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-amber-500"></i><?php echo e($cat->name); ?></a></li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </ul>
                </div>

                
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Layanan Pelanggan</h3>
                    <ul class="space-y-2.5">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $footerPages ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <li><a href="<?php echo e(route('page.show', $fp->slug)); ?>" class="text-sm text-gray-400 hover:text-amber-400 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-amber-500"></i><?php echo e($fp->title); ?></a></li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <li><span class="text-sm text-gray-600">Belum ada halaman</span></li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>

                
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Kontak Kami</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <i class="fab fa-whatsapp text-green-400 mt-0.5"></i>
                            <span><?php echo e(App\Models\Setting::getValue('store_phone', '0812-3456-7890')); ?></span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <i class="far fa-envelope text-amber-400 mt-0.5"></i>
                            <span><?php echo e(App\Models\Setting::getValue('store_email', 'info@procell.com')); ?></span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <i class="fas fa-map-marker-alt text-red-400 mt-0.5"></i>
                            <span><?php echo e(App\Models\Setting::getValue('store_address', 'Jakarta, Indonesia')); ?></span>
                        </li>
                    </ul>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wa = App\Models\Setting::getValue('whatsapp_number')): ?>
                        <a href="https://wa.me/<?php echo e($wa); ?>" target="_blank" class="inline-flex items-center gap-2 mt-4 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            <i class="fab fa-whatsapp text-lg"></i> Chat Via WhatsApp
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="border-t border-gray-800">
                <div class="max-w-7xl mx-auto px-4 py-6">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-sm text-gray-500">Metode Pembayaran:</p>
                        <div class="flex items-center gap-4 text-2xl text-gray-500">
                            <i class="fab fa-cc-visa" title="Visa"></i>
                            <i class="fab fa-cc-mastercard" title="Mastercard"></i>
                            <span class="text-sm font-bold bg-gray-800 px-3 py-1 rounded">BCA</span>
                            <span class="text-sm font-bold bg-gray-800 px-3 py-1 rounded">Mandiri</span>
                            <span class="text-sm font-bold bg-gray-800 px-3 py-1 rounded">BNI</span>
                            <span class="text-sm font-bold bg-gray-800 px-3 py-1 rounded">BRI</span>
                            <i class="fas fa-mobile-alt text-xl" title="OVO / GoPay / Dana"></i>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="border-t border-gray-800">
                <div class="max-w-7xl mx-auto px-4 py-4 text-center text-sm text-gray-500">
                    <?php echo e(App\Models\Setting::getValue('footer_text', '© 2026 ProCell Store. All rights reserved.')); ?>

                </div>
            </div>
        </footer>
    </div>

    
    <button x-data="{ show: false }" x-show="show" x-cloak @scroll.window="show = window.scrollY > 400" @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-transition class="fixed bottom-6 right-6 w-12 h-12 rounded-full gradient-primary text-white shadow-lg hover:shadow-xl transition-all flex items-center justify-center z-50">
        <i class="fas fa-arrow-up"></i>
    </button>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\pro021\procell-store\resources\views/layouts/app.blade.php ENDPATH**/ ?>