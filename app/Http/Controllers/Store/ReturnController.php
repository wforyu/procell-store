<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Returns;
use App\Models\User;
use App\Notifications\ReturnSubmitted;
use App\Services\FonnteService;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReturnController extends Controller
{
    public function create(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($order->status, ['shipped', 'completed'])) {
            return back()->with('error', 'Pesanan belum bisa diretur.');
        }

        $existing = $order->returns()->whereIn('status', ['pending', 'approved'])->first();
        if ($existing) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Anda sudah memiliki pengajuan retur untuk pesanan ini.');
        }

        return view('store.returns.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($order->status, ['shipped', 'completed'])) {
            return back()->with('error', 'Pesanan belum bisa diretur.');
        }

        $existing = $order->returns()->whereIn('status', ['pending', 'approved'])->first();
        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki pengajuan retur untuk pesanan ini.');
        }

        $request->validate([
            'reason' => 'required|in:defective,wrong_item,not_as_described,damaged,other',
            'description' => 'required|string|max:2000',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $retur = Returns::create([
            'return_number' => 'RET-'.date('Ymd').'-'.strtoupper(Str::random(6)),
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        foreach ($request->file('images', []) as $image) {
            $path = $image->store('return-images', 'public');
            $retur->images()->create(['image' => $path]);
        }

        $admins = User::role(['Super Admin', 'Stok', 'Keuangan'])->get();
        Notification::make()
            ->title('Retur Baru Diajukan')
            ->body('Retur #'.$retur->return_number.' oleh '.auth()->user()->name.' menunggu diproses.')
            ->icon('heroicon-o-arrow-uturn-left')
            ->sendToDatabase($admins);
        $admins->each->notify(new ReturnSubmitted($retur));
        app(FonnteService::class)->notifyAdmins(
            'Retur Baru Diajukan',
            'Retur #'.$retur->return_number.' oleh '.auth()->user()->name.' menunggu diproses.'
        );

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pengajuan retur berhasil dikirim. Admin akan memprosesnya.');
    }
}
