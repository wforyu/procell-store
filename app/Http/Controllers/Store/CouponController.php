<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $coupon = Coupon::active()->where('code', $request->coupon_code)->first();

        if (! $coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Kode kupon tidak valid atau sudah kadaluarsa.',
            ]);
        }

        $cart = auth()->check()
            ? Cart::where('user_id', auth()->id())->with('items')->first()
            : Cart::where('session_id', session()->get('cart_session_id'))->with('items')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang belanja kosong.',
            ]);
        }

        $discount = $coupon->calculateDiscount((float) $cart->total);

        if ($discount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kupon tidak dapat digunakan. Minimal pesanan: Rp '.number_format($coupon->min_order, 0, ',', '.'),
            ]);
        }

        session()->put('applied_coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $discount,
        ]);

        return response()->json([
            'success' => true,
            'discount' => $discount,
            'code' => $coupon->code,
        ]);
    }

    public function remove(Request $request)
    {
        session()->forget('applied_coupon');

        return response()->json([
            'success' => true,
        ]);
    }
}
