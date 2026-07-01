<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusChanged;
use App\Notifications\PaymentUploaded;
use App\Services\FonnteService;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('store.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product.images', 'returns.images');

        return view('store.orders.show', compact('order'));
    }

    public function uploadPayment(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dalam status menunggu pembayaran.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'status' => 'waiting_confirmation',
        ]);

        if ($order->user) {
            $order->user->notify(new OrderStatusChanged($order, 'pending', 'waiting_confirmation'));
        }
        app(FonnteService::class)->sendOrderStatus($order, 'pending', 'waiting_confirmation');

        $customerName = $order->customer?->name ?? $order->user?->name ?? 'Guest';
        $admins = User::role(['Super Admin', 'Stok', 'Keuangan'])->get();
        Notification::make()
            ->title('Bukti Pembayaran Diupload')
            ->body('Pesanan #'.$order->order_number.' oleh '.$customerName.' menunggu konfirmasi pembayaran.')
            ->icon('heroicon-o-currency-dollar')
            ->sendToDatabase($admins);
        $admins->each->notify(new PaymentUploaded($order));
        app(FonnteService::class)->notifyAdmins(
            'Bukti Pembayaran Diupload',
            'Pesanan #'.$order->order_number.' oleh '.$order->user->name.' menunggu konfirmasi pembayaran.'
        );

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');
    }

    public function confirmReceived(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'shipped') {
            return back()->with('error', 'Pesanan belum dikirim.');
        }

        $oldStatus = $order->status;
        $order->update([
            'status' => 'completed',
            'received_at' => now(),
        ]);

        if ($order->user) {
            $order->user->notify(new OrderStatusChanged($order, $oldStatus, 'completed'));
        }
        app(FonnteService::class)->sendOrderStatus($order, $oldStatus, 'completed');

        return back()->with('success', 'Pesanan telah diterima. Terima kasih!');
    }
}
