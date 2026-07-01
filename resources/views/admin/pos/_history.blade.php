@forelse($orders as $order)
    <a href="{{ route('admin.pos.receipt', $order['id']) }}" target="_blank" class="history-item" data-id="{{ $order['id'] }}">
        <div class="history-left">
            <div class="history-number">{{ $order['order_number'] }}</div>
            <div class="history-customer">{{ $order['customer_name'] }}</div>
        </div>
        <div class="history-right">
                    <div class="history-total">Rp {{ number_format($order['grand_total'], 0, ',', '.') }}</div>
            <div class="history-meta">
                <span class="history-time">{{ $order['created_at'] }}</span>
                <span class="history-payment">{{ $order['payment_method'] === 'cash' ? 'Tunai' : 'Transfer' }}</span>
            </div>
        </div>
    </a>
@empty
    <div class="history-empty">
        <i class="fas fa-receipt"></i>
        <p>Belum ada transaksi POS hari ini</p>
    </div>
@endforelse
