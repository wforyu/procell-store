<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function finish(Order $order)
    {
        $bankAccounts = BankAccount::active()->get();
        $isGuest = ! auth()->check();

        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
        }

        if (! auth()->check()) {
            $guestOrders = session('guest_orders', []);
            if (! in_array($order->id, $guestOrders)) {
                abort(403);
            }
        }

        return view('store.checkout.success', compact('order', 'bankAccounts', 'isGuest'));
    }

    public function notification(Request $request, MidtransService $midtrans)
    {
        $order = $midtrans->handleNotification($request->all());

        if (! $order) {
            return response()->json(['status' => 'order_not_found'], 404);
        }

        return response()->json(['status' => 'ok']);
    }
}
