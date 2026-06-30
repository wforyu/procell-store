<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    const COURIERS = [
        'jne' => ['name' => 'JNE', 'services' => ['REG' => 12000, 'YES' => 25000, 'OKE' => 8000]],
        'jnt' => ['name' => 'J&T', 'services' => ['REG' => 11000, 'YES' => 22000]],
        'sicepat' => ['name' => 'SiCepat', 'services' => ['REG' => 13000, 'BEST' => 28000]],
        'ninja' => ['name' => 'Ninja Express', 'services' => ['REG' => 14000, '2DAY' => 10000]],
    ];

    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())->with('items.product.primaryImage')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
        }

        $bankAccounts = BankAccount::active()->get();
        $couriers = static::COURIERS;

        $appliedCoupon = session('applied_coupon');

        return view('store.checkout.index', compact('cart', 'bankAccounts', 'couriers', 'appliedCoupon'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'courier' => 'required|string|in:jne,jnt,sicepat,ninja',
            'courier_service' => 'required|string',
            'payment_method' => 'required|string|in:bank_transfer',
            'bank_account_id' => 'required_if:payment_method,bank_transfer|exists:bank_accounts,id',
            'notes' => 'nullable|string',
        ]);

        $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
        }

        // Validate courier service
        $courierData = static::COURIERS[$request->courier] ?? null;
        if (! $courierData || ! isset($courierData['services'][$request->courier_service])) {
            return back()->withErrors(['courier_service' => 'Layanan kurir tidak valid.']);
        }

        $shippingCost = $courierData['services'][$request->courier_service];

        // Handle applied coupon
        $discountAmount = 0;
        $couponId = null;

        $appliedCoupon = session('applied_coupon');
        if ($appliedCoupon && isset($appliedCoupon['id'])) {
            $coupon = Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->isValid()) {
                $calculatedDiscount = $coupon->calculateDiscount((float) $cart->total);
                if ($calculatedDiscount > 0) {
                    $discountAmount = $calculatedDiscount;
                    $couponId = $coupon->id;
                }
            }
        }

        $customer = Customer::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone,
                'address' => $request->shipping_address,
            ]
        );

        $order = Order::create([
            'order_number' => 'ORD-'.date('Ymd').'-'.strtoupper(Str::random(6)),
            'user_id' => auth()->id(),
            'customer_id' => $customer->id,
            'status' => 'pending',
            'total_amount' => $cart->total,
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
            'payment_method' => $request->payment_method,
            'courier' => $request->courier,
            'courier_service' => $request->courier_service,
            'shipping_cost' => $shippingCost,
            'coupon_id' => $couponId,
            'discount_amount' => $discountAmount,
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ]);

            $product = $item->product;
            $stockBefore = $product->stock;
            $product->decrement('stock', $item->quantity);

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => 'out',
                'quantity' => $item->quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $product->fresh()->stock,
                'reference_type' => 'order',
                'reference_id' => $order->id,
                'note' => 'Pesanan #'.$order->order_number,
            ]);
        }

        // Record coupon usage if applied
        if ($couponId) {
            $coupon = Coupon::find($couponId);
            $coupon->increment('used_count');

            CouponUsage::create([
                'coupon_id' => $couponId,
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'used_at' => now(),
            ]);

            session()->forget('applied_coupon');
        }

        $cart->items()->delete();
        $cart->delete();

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $bankAccounts = BankAccount::active()->get();

        return view('store.checkout.success', compact('order', 'bankAccounts'));
    }

    public static function getCourierLabel($courier): string
    {
        return static::COURIERS[$courier]['name'] ?? $courier;
    }
}
