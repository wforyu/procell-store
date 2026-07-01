<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-6 md:py-8">

    
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="<?php echo e(route('home')); ?>" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <a href="<?php echo e(route('orders.index')); ?>" class="hover:text-amber-600 transition-colors">Pesanan Saya</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">#<?php echo e($order->order_number); ?></span>
    </nav>

    
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-receipt text-amber-500"></i>
                    Pesanan #<?php echo e($order->order_number); ?>

                </h1>
                <p class="text-sm text-gray-500 mt-1"><?php echo e($order->created_at->format('d/m/Y H:i')); ?> WIB</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-bold self-start
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
                <?php elseif($order->status == 'processing'): ?> Sedang Diproses
                <?php elseif($order->status == 'shipped'): ?> Dalam Pengiriman
                <?php elseif($order->status == 'completed'): ?> Selesai
                <?php else: ?> Dibatalkan
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        
        <div class="lg:col-span-2 space-y-6">

            
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-box text-amber-500"></i> Item Pesanan
                </h3>
                <div class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="flex items-center gap-4 <?php echo e(!$loop->last ? 'pb-4 border-b border-gray-50' : ''); ?>">
                            <div class="w-16 h-16 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->product->imageUrl): ?>
                                    <img src="<?php echo e($item->product->imageUrl); ?>" class="w-full h-full object-contain p-2">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-mobile-screen text-xl text-gray-200"></i></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900"><?php echo e($item->product->name); ?></p>
                                <p class="text-xs text-gray-500 mt-0.5"><?php echo e($item->quantity); ?> x Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?></p>
                            </div>
                            <p class="text-sm font-bold text-gray-900">Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></p>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>

                
                <div class="border-t border-gray-100 pt-4 mt-4 space-y-1">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium">Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->shipping_cost > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-medium">Rp <?php echo e(number_format($order->shipping_cost, 0, ',', '.')); ?></span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="flex justify-between items-center border-t border-gray-100 pt-2">
                        <span class="font-semibold text-gray-900">Total Pesanan</span>
                        <span class="text-xl font-bold text-amber-600">Rp <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?></span>
                    </div>
                </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($order->status, ['pending'])): ?>
                <div class="bg-white rounded-2xl border border-amber-200 p-6" x-data="{ uploading: false }">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-upload text-amber-500"></i> Upload Bukti Transfer
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Silakan transfer ke rekening yang tertera dan upload bukti pembayaran untuk mempercepat proses konfirmasi.
                    </p>

                    
                    <?php $bankAccounts = App\Models\BankAccount::active()->get(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bankAccounts->count() > 0): ?>
                        <div class="bg-gray-50 rounded-xl p-4 mb-4 space-y-2">
                            <p class="text-xs font-medium text-gray-700">Rekening Tujuan:</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="flex items-center justify-between text-sm">
                                    <div>
                                        <span class="font-semibold text-gray-900"><?php echo e($bank->bank_name); ?></span>
                                        <span class="text-gray-600"> — <?php echo e($bank->account_number); ?></span>
                                        <span class="text-gray-500">a.n. <?php echo e($bank->account_holder); ?></span>
                                    </div>
                                    <button onclick="navigator.clipboard.writeText('<?php echo e($bank->account_number); ?>')" class="text-xs text-amber-600 hover:text-amber-700 font-medium px-2 py-1 rounded border border-amber-200 hover:bg-amber-50 transition-colors">
                                        <i class="fas fa-copy"></i> Salin
                                    </button>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <form action="<?php echo e(route('orders.payment.upload', $order)); ?>" method="POST" enctype="multipart/form-data"
                          @submit="uploading = true">
                        <?php echo csrf_field(); ?>
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <input type="file" name="payment_proof" accept="image/*" required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-colors">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['payment_proof'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <button type="submit" :disabled="uploading"
                                    class="btn-primary text-sm !py-2.5 !px-5" x-text="uploading ? 'Mengupload...' : 'Upload'">
                                Upload
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-2"><i class="fas fa-info-circle"></i> Format: JPG, PNG. Maksimal 2MB</p>
                    </form>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'shipped'): ?>
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
                            
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-green-500 to-emerald-500"></div>

                            
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 mt-2">
                                <i class="fas fa-check-circle text-3xl text-green-600"></i>
                            </div>

                            
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi Pesanan</h3>

                            
                            <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                                Apakah Anda yakin pesanan ini sudah diterima dengan selamat?<br>
                                <span class="text-xs text-gray-400">Tindakan ini tidak dapat dibatalkan.</span>
                            </p>

                            
                            <div class="flex gap-3">
                                <button type="button" @click="confirmModal = false"
                                        class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <form action="<?php echo e(route('orders.confirm-received', $order)); ?>" method="POST" class="flex-1">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                            class="w-full px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-green-600/20">
                                        <i class="fas fa-check-circle"></i> Ya, Konfirmasi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($order->status, ['shipped', 'completed'])): ?>
                <?php
                    $activeRetur = $order->returns->whereIn('status', ['pending', 'approved'])->first();
                ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-undo-alt text-red-500"></i> Retur Barang
                    </h3>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeRetur): ?>
                        <div class="bg-<?php echo e($activeRetur->status === 'approved' ? 'green' : 'orange'); ?>-50 rounded-xl p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-<?php echo e($activeRetur->status === 'approved' ? 'check-circle text-green-600' : 'hourglass-half text-orange-600'); ?>"></i>
                                <span class="font-medium text-sm">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeRetur->status === 'pending'): ?> Menunggu persetujuan admin
                                    <?php elseif($activeRetur->status === 'approved'): ?> Retur disetujui
                                    <?php else: ?> Retur ditolak
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                            </div>
                            <p class="text-xs text-gray-600">No. Retur: #<?php echo e($activeRetur->return_number); ?></p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeRetur->admin_note): ?>
                                <p class="text-xs text-gray-600 mt-1">Catatan admin: <?php echo e($activeRetur->admin_note); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-sm text-gray-600 mb-4">Barang rusak/tidak sesuai? Ajukan retur dalam waktu 3 hari setelah pesanan diterima.</p>
                        <a href="<?php echo e(route('returns.create', $order)); ?>" class="btn-outline text-sm !py-2 !px-4">
                            <i class="fas fa-undo-alt"></i> Ajukan Retur
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'completed'): ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-star text-yellow-500"></i> Beri Ulasan
                    </h3>
                    <?php
                        $allReviewed = $order->items->every(fn($item) => $item->product->reviews->where('user_id', auth()->id())->where('order_id', $order->id)->isNotEmpty());
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$allReviewed): ?>
                        <form action="<?php echo e(route('orders.review.store', $order)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php $existingReview = $item->product->reviews->where('user_id', auth()->id())->where('order_id', $order->id)->first(); ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$existingReview): ?>
                                    <div class="border-b border-gray-100 pb-4 mb-4">
                                        <p class="text-sm font-medium text-gray-900 mb-3"><?php echo e($item->product->name); ?></p>
                                        <div class="flex gap-1 mb-3" x-data="{ rating: 0 }">
                                            <template x-for="i in 5">
                                                <button type="button" @click="rating = i" class="text-2xl transition-colors"
                                                        :class="i <= rating ? 'text-yellow-400' : 'text-gray-200'">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            </template>
                                            <input type="hidden" name="ratings[<?php echo e($item->product_id); ?>]" x-model="rating">
                                        </div>
                                        <textarea name="reviews[<?php echo e($item->product_id); ?>]" rows="2" class="input-field text-sm mb-2" placeholder="Tulis ulasan Anda..."></textarea>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <button type="submit" class="btn-primary"><i class="fas fa-paper-plane"></i> Kirim Ulasan</button>
                        </form>
                    <?php else: ?>
                        <p class="text-sm text-gray-500"><i class="fas fa-check-circle text-green-500"></i> Anda sudah memberikan ulasan untuk semua produk di pesanan ini. Terima kasih!</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->payment_proof): ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-receipt text-green-500"></i> Bukti Pembayaran
                    </h3>
                    <div class="flex items-center gap-4">
                        <a href="<?php echo e(asset('storage/' . $order->payment_proof)); ?>" target="_blank"
                           class="w-24 h-24 bg-gray-50 rounded-xl overflow-hidden border border-gray-200 hover:border-amber-300 transition-colors">
                            <img src="<?php echo e(asset('storage/' . $order->payment_proof)); ?>" class="w-full h-full object-cover">
                        </a>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Bukti transfer telah diupload</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->payment_verified_at): ?>
                                <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Terverifikasi <?php echo e($order->payment_verified_at->format('d/m/Y H:i')); ?>

                                </p>
                            <?php else: ?>
                                <p class="text-xs text-orange-500 mt-1 flex items-center gap-1">
                                    <i class="fas fa-hourglass-half"></i> Menunggu konfirmasi admin
                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <a href="<?php echo e(asset('storage/' . $order->payment_proof)); ?>" target="_blank"
                               class="text-xs text-amber-600 hover:text-amber-700 font-medium mt-2 inline-block">
                                <i class="fas fa-external-link-alt"></i> Lihat Gambar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

        
        <div class="space-y-4">

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->courier): ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-shipping-fast text-amber-500"></i> Pengiriman
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kurir</span>
                            <span class="font-medium text-gray-900"><?php echo e(\App\Http\Controllers\Store\CheckoutController::getCourierLabel($order->courier)); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Layanan</span>
                            <span class="font-medium text-gray-900"><?php echo e($order->courier_service); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-medium text-gray-900">Rp <?php echo e(number_format($order->shipping_cost, 0, ',', '.')); ?></span>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->tracking_number): ?>
                            <div class="border-t border-gray-100 pt-2 mt-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">No. Resi</span>
                                    <span class="font-bold text-amber-600 text-sm"><?php echo e($order->tracking_number); ?></span>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->shipping_address): ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-map-marker-alt text-amber-500"></i> Alamat Pengiriman
                    </h3>
                    <p class="text-sm text-gray-600 leading-relaxed"><?php echo e($order->shipping_address); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->notes): ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-sticky-note text-amber-500"></i> Catatan
                    </h3>
                    <p class="text-sm text-gray-600"><?php echo e($order->notes); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
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
                            <p class="text-xs text-gray-500"><?php echo e($order->created_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                    <?php
                        $steps = ['pending', 'waiting_confirmation', 'processing', 'shipped', 'completed'];
                        $currentIdx = array_search($order->status, $steps);
                        $stepLabels = [
                            'pending' => ['Menunggu Pembayaran', 'Menunggu pembayaran dari Anda'],
                            'waiting_confirmation' => ['Menunggu Konfirmasi', 'Bukti pembayaran sedang diperiksa'],
                            'processing' => ['Diproses', 'Pesanan sedang diproses'],
                            'shipped' => ['Dikirim', 'Pesanan dalam perjalanan'],
                            'completed' => ['Selesai', 'Pesanan telah diterima'],
                        ];
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($step === 'pending'): ?> <?php continue; ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php
                            $done = $currentIdx !== false && $i <= $currentIdx;
                            $current = $currentIdx !== false && $i === $currentIdx;
                            $next = $currentIdx !== false && $i === $currentIdx + 1;
                        ?>
                        <div class="flex items-start gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full <?php echo e($done ? 'bg-green-500' : ($current ? 'bg-amber-500' : 'bg-gray-200')); ?> flex items-center justify-center flex-shrink-0">
                                    <i class="fas <?php echo e($done ? 'fa-check' : ($current ? 'fa-hourglass-half' : 'fa-clock')); ?> text-white text-[10px]"></i>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>
                                    <div class="w-0.5 h-8 <?php echo e($done && !$loop->last ? 'bg-green-200' : 'bg-gray-200'); ?>"></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <p class="text-sm font-medium <?php echo e($done || $current ? 'text-gray-900' : 'text-gray-400'); ?>">
                                    <?php echo e($stepLabels[$step][0]); ?>

                                </p>
                                <p class="text-xs <?php echo e($done || $current ? 'text-gray-500' : 'text-gray-400'); ?>"><?php echo e($stepLabels[$step][1]); ?></p>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wa = App\Models\Setting::getValue('whatsapp_number')): ?>
                <a href="https://wa.me/<?php echo e($wa); ?>?text=Halo%20saya%20ingin%20tanya%20tentang%20pesanan%20%23<?php echo e($order->order_number); ?>" target="_blank" class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white p-4 rounded-2xl font-medium transition-colors">
                    <i class="fab fa-whatsapp text-xl"></i> Hubungi CS
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div class="mt-6">
        <a href="<?php echo e(route('orders.index')); ?>" class="text-sm text-amber-600 hover:text-amber-700 font-medium inline-flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Kembali ke Pesanan
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\pro021\procell-store\resources\views\store\orders\show.blade.php ENDPATH**/ ?>