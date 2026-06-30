<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $count = 0;
            if (auth()->check()) {
                $cart = Cart::where('user_id', auth()->id())->withCount('items')->first();
                $count = $cart ? $cart->items_count : 0;
            } elseif ($sessionId = session()->get('cart_session_id')) {
                $cart = Cart::where('session_id', $sessionId)->withCount('items')->first();
                $count = $cart ? $cart->items_count : 0;
            }
            $view->with('cartCount', $count);
        });

        View::composer('layouts.app', function ($view) {
            $footerPages = Schema::hasTable('pages') ? Page::active()->get() : collect();
            $view->with('footerPages', $footerPages);
        });
    }
}
