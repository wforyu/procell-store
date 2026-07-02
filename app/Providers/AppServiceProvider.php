<?php

namespace App\Providers;

use App\Http\Controllers\Store\CompareController;
use App\Models\BankAccount;
use App\Models\Banner;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Refund;
use App\Models\Returns;
use App\Models\Setting;
use App\Models\Supplier;
use App\Observers\OrderObserver;
use App\Services\AdminActivityLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    protected function logAdminActivity(string $action, Model $model): void
    {
        if (! auth()->check() || ! auth()->user()->is_admin) {
            return;
        }

        AdminActivityLogger::{$action}($model);
    }

    public function boot(): void
    {
        Order::observe(OrderObserver::class);

        if (Schema::hasTable('settings')) {
            try {
                $mailHost = Setting::getValue('mail_host');
                if ($mailHost) {
                    config()->set('mail.default', Setting::getValue('mail_mailer', 'smtp'));
                    config()->set('mail.mailers.smtp.host', $mailHost);
                    config()->set('mail.mailers.smtp.port', (int) Setting::getValue('mail_port', '587'));
                    config()->set('mail.mailers.smtp.username', Setting::getValue('mail_username'));
                    config()->set('mail.mailers.smtp.password', Setting::getValue('mail_password'));
                    config()->set('mail.mailers.smtp.encryption', Setting::getValue('mail_encryption', 'tls'));
                    config()->set('mail.from.address', Setting::getValue('mail_from_address', config('mail.from.address')));
                    config()->set('mail.from.name', Setting::getValue('mail_from_name', config('mail.from.name')));
                }
            } catch (\Throwable $e) {
                // skip jika tabel settings belum ada atau belum di migrate
            }
        }

        $adminModels = [
            BankAccount::class, Banner::class, Category::class, Coupon::class,
            Expense::class, Product::class, PurchaseOrder::class, Supplier::class,
            Refund::class, Returns::class,
        ];

        foreach ($adminModels as $modelClass) {
            $modelClass::created(fn (Model $model) => $this->logAdminActivity('created', $model));
            $modelClass::updated(fn (Model $model) => $this->logAdminActivity('updated', $model));
            $modelClass::deleted(fn (Model $model) => $this->logAdminActivity('deleted', $model));
        }

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
            $view->with('compareCount', count(session()->get(CompareController::SESSION_KEY, [])));
        });

        View::composer('layouts.app', function ($view) {
            $footerPages = Schema::hasTable('pages') ? Page::active()->get() : collect();
            $view->with('footerPages', $footerPages);
        });
    }
}
