<?php

use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\ChatController;
use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\Store\CompareController;
use App\Http\Controllers\Store\CouponController;
use App\Http\Controllers\Store\HomeController;
use App\Http\Controllers\Store\MidtransController;
use App\Http\Controllers\Store\OrderController;
use App\Http\Controllers\Store\PageController;
use App\Http\Controllers\Store\ProductController;
use App\Http\Controllers\Store\ReturnController;
use App\Http\Controllers\Store\ReviewController;
use App\Http\Controllers\Store\WishlistController;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search/suggestions', [ProductController::class, 'searchSuggestions'])->name('products.suggestions');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
Route::post('/compare/toggle/{product}', [CompareController::class, 'toggle'])->name('compare.toggle');
Route::post('/compare/remove/{product}', [CompareController::class, 'remove'])->name('compare.remove');
Route::post('/compare/clear', [CompareController::class, 'clear'])->name('compare.clear');
Route::get('/categories/{slug}', [ProductController::class, 'byCategory'])->name('products.category');

Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

Route::get('/sitemap.xml', function () {
    $products = Product::select('slug', 'updated_at')->get();
    $categories = Category::select('slug', 'updated_at')->get();
    $pages = Page::active()->select('slug', 'updated_at')->get();

    return response()->view('store.sitemap', compact('products', 'categories', 'pages'))->header('Content-Type', 'text/xml');
})->name('sitemap');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/courier-rates', [CheckoutController::class, 'courierRates'])->name('checkout.courier-rates');

Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
Route::post('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');

Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');
Route::get('/midtrans/finish/{order}', [MidtransController::class, 'finish'])->name('midtrans.finish');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/payment-upload', [OrderController::class, 'uploadPayment'])->name('orders.payment.upload');
    Route::post('/orders/{order}/confirm-received', [OrderController::class, 'confirmReceived'])->name('orders.confirm-received');
    Route::post('/orders/{order}/review', [ReviewController::class, 'store'])->name('orders.review.store');

    Route::get('/orders/{order}/retur', [ReturnController::class, 'create'])->name('returns.create');
    Route::post('/orders/{order}/retur', [ReturnController::class, 'store'])->name('returns.store');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/start', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/{conversation}/poll', [ChatController::class, 'poll'])->name('chat.poll');

    Route::post('/products/{product}/quick-buy', [ProductController::class, 'quickBuy'])->name('products.quick-buy');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('export/orders/csv', [ExportController::class, 'ordersCsv'])->name('export.orders.csv');
    Route::get('export/products/csv', [ExportController::class, 'productsCsv'])->name('export.products.csv');
    Route::get('export/suppliers/csv', [ExportController::class, 'suppliersCsv'])->name('export.suppliers.csv');
    Route::get('reports/profit-loss/csv', [ExportController::class, 'profitLossCsv'])->name('reports.profit-loss.csv');

    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/search', [PosController::class, 'search'])->name('pos.search');
    Route::post('/pos/add', [PosController::class, 'add'])->name('pos.add');
    Route::post('/pos/update', [PosController::class, 'update'])->name('pos.update');
    Route::post('/pos/remove', [PosController::class, 'remove'])->name('pos.remove');
    Route::post('/pos/clear', [PosController::class, 'clearCart'])->name('pos.clear');
    Route::post('/pos/sku-add', [PosController::class, 'skuAdd'])->name('pos.sku-add');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::post('/pos/customer-add', [PosController::class, 'customerAdd'])->name('pos.customer-add');
    Route::get('/pos/history', [PosController::class, 'history'])->name('pos.history');
    Route::get('/pos/receipt/{order}', [PosController::class, 'receipt'])->name('pos.receipt');
    Route::get('/backup/download/{filename}', [ExportController::class, 'downloadBackup'])->name('backup.download');
});
