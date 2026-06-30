<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk POS - {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; background: #f1f5f9; display: flex; justify-content: center; padding: 2rem; }
        .receipt { width: 380px; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; }
        .receipt-header { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; padding: 1.5rem; text-align: center; }
        .receipt-header h1 { font-size: 1.25rem; font-weight: 800; }
        .receipt-header p { font-size: 0.75rem; opacity: 0.9; margin-top: 0.25rem; }
        .receipt-body { padding: 1.25rem; }
        .receipt-body .info { font-size: 0.8125rem; color: #475569; margin-bottom: 0.75rem; }
        .receipt-body .info div { display: flex; justify-content: space-between; padding: 0.125rem 0; }
        .receipt-divider { border: none; border-top: 1px dashed #cbd5e1; margin: 0.75rem 0; }
        .receipt-item { display: flex; justify-content: space-between; font-size: 0.8125rem; padding: 0.25rem 0; color: #0f172a; }
        .receipt-item .item-left { flex: 1; }
        .receipt-item .item-left .item-name { font-weight: 600; }
        .receipt-item .item-left .item-meta { font-size: 0.6875rem; color: #64748b; }
        .receipt-item .item-right { font-weight: 700; text-align: right; white-space: nowrap; }
        .receipt-total { padding-top: 0.75rem; border-top: 2px solid #0f172a; display: flex; justify-content: space-between; font-size: 1.125rem; font-weight: 800; }
        .receipt-footer { text-align: center; padding: 1.25rem; font-size: 0.75rem; color: #64748b; border-top: 1px dashed #cbd5e1; }
        .receipt-actions { padding: 1rem 1.25rem; display: flex; gap: 0.75rem; }
        .receipt-actions button, .receipt-actions a { flex: 1; padding: 0.625rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; transition: all 0.2s; }
        .btn-print { background: #0f172a; color: #fff; border: none; }
        .btn-print:hover { background: #1e293b; }
        .btn-new { background: #f59e0b; color: #fff; border: none; }
        .btn-new:hover { background: #d97706; }
        .btn-dash { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .btn-dash:hover { background: #e2e8f0; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt { box-shadow: none; border-radius: 0; }
            .receipt-actions { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1>ProCell Store</h1>
            <p>Sparepart & Aksesoris HP</p>
        </div>

        <div class="receipt-body">
            <div class="info">
                <div><span>No. Pesanan</span><span>{{ $order->order_number }}</span></div>
                <div><span>Tanggal</span><span>{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
                <div><span>Kasir</span><span>{{ $order->user?->name ?? '-' }}</span></div>
                <div><span>Pelanggan</span><span>{{ $order->customer?->name ?? 'Walk-in' }}</span></div>
                <div><span>Pembayaran</span><span>{{ $order->payment_method === 'cash' ? 'Tunai' : 'Transfer Bank' }}</span></div>
                @if($order->notes)
                    <div style="margin-top:0.5rem;padding:0.5rem;background:#f8fafc;border-radius:6px">
                        <span style="font-size:0.75rem;color:#64748b">{{ $order->notes }}</span>
                    </div>
                @endif
            </div>

            <hr class="receipt-divider">

            @foreach($order->items as $item)
                <div class="receipt-item">
                    <div class="item-left">
                        <div class="item-name">{{ $item->product?->name ?? 'Produk' }}</div>
                        <div class="item-meta">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                    </div>
                    <div class="item-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
            @endforeach

            <hr class="receipt-divider">

            <div class="receipt-total">
                <span>Total</span>
                <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="receipt-footer">
            <p>Terima kasih telah berbelanja!</p>
            <p style="margin-top:0.25rem;font-size:0.6875rem">{{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <div class="receipt-actions">
            <button class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
            <a href="{{ route('admin.pos.index') }}" class="btn-new"><i class="fas fa-plus"></i> POS Baru</a>
            <a href="{{ url('/admin') }}" class="btn-dash"><i class="fas fa-th-large"></i> Dashboard</a>
        </div>
    </div>
</body>
</html>