<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'ProCell Store')); ?> - Toko Sparepart & Aksesoris HP</title>
    <meta name="description" content="Toko sparepart dan aksesoris HP terlengkap. LCD, baterai, flex cable, charger dan aksesoris smartphone berkualitas.">

    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased text-gray-900 min-h-screen lg:flex">

    
    <div class="hidden lg:flex lg:w-[480px] xl:w-[560px] min-h-screen relative overflow-hidden bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900 flex-col items-center justify-center flex-shrink-0">
        
        <div class="absolute inset-0">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-amber-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-orange-600/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] border border-amber-500/10 rounded-full"></div>
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 40 40%22><path d=%22M0 40V0h40%22 fill=%22none%22 stroke=%22%23f59e0b%22 stroke-width=%221%22/></svg>'); background-size: 60px 60px;"></div>
        </div>

        <div class="relative z-10 text-center px-12">
            
            <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl shadow-2xl flex items-center justify-center mx-auto mb-6">
                <span class="text-3xl font-black text-white tracking-tight">PC</span>
            </div>

            <h1 class="text-3xl font-extrabold text-white mb-3">
                <span class="text-amber-400">ProCell</span> Store
            </h1>
            <p class="text-base text-gray-400 leading-relaxed mb-10">
                Toko Sparepart & Aksesoris HP Terpercaya
            </p>

            
            <div class="space-y-5 text-left max-w-xs mx-auto">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 rounded-lg bg-amber-500/20 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-microchip text-amber-400 text-xs"></i>
                    </div>
                    <span class="text-sm text-gray-300">Sparepart original berkualitas tinggi</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 rounded-lg bg-amber-500/20 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-truck text-amber-400 text-xs"></i>
                    </div>
                    <span class="text-sm text-gray-300">Pengiriman cepat ke seluruh Indonesia</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 rounded-lg bg-amber-500/20 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-headset text-amber-400 text-xs"></i>
                    </div>
                    <span class="text-sm text-gray-300">Dukungan customer service 24/7</span>
                </div>
            </div>

            <p class="mt-10 text-xs text-gray-600">&copy; <?php echo e(date('Y')); ?> ProCell Store</p>
        </div>
    </div>

    
    <div class="flex-1 min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-amber-50 via-white to-orange-50 p-4 sm:p-6 relative">
        
        <div class="absolute top-0 right-0 w-72 h-72 bg-amber-200/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-orange-200/30 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

        
        <div class="lg:hidden text-center mb-6">
            <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl shadow-lg flex items-center justify-center mx-auto mb-2">
                <span class="text-xl font-black text-white">PC</span>
            </div>
            <h2 class="text-xl font-bold text-gray-900">
                <span class="text-amber-500">ProCell</span> Store
            </h2>
        </div>

        
        <div class="w-full max-w-[420px] relative z-10">
            <div class="bg-white rounded-2xl shadow-xl shadow-amber-100/40 border border-amber-100/40 p-7 sm:p-8">
                <?php echo e($slot); ?>

            </div>

            <p class="text-center mt-5 text-xs text-gray-400 lg:hidden">&copy; <?php echo e(date('Y')); ?> ProCell Store</p>
        </div>
    </div>

</body>
</html>
<?php /**PATH C:\Users\pro021\procell-store\resources\views\layouts\guest.blade.php ENDPATH**/ ?>