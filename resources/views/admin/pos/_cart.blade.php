@forelse($posCart as $item)
    <div class="pos-cart-item" data-pid="{{ $item['product_id'] }}">
        <div class="item-info">
            <div class="item-name">{{ $item['name'] }}</div>
            <div class="item-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
        </div>
        <div class="qty-control">
            <button onclick="updateQty({{ $item['product_id'] }}, -1)"><i class="fas fa-minus"></i></button>
            <span class="qty-value clickable-qty" data-pid="{{ $item['product_id'] }}" onclick="editQty(this, {{ $item['product_id'] }})">{{ $item['quantity'] }}</span>
            <button onclick="updateQty({{ $item['product_id'] }}, 1)"><i class="fas fa-plus"></i></button>
        </div>
        <div class="item-subtotal">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
        <button class="item-remove" onclick="removeProduct({{ $item['product_id'] }})"><i class="fas fa-times"></i></button>
    </div>
@empty
    <div class="pos-cart-empty">
        <i class="fas fa-shopping-cart"></i>
        <p>Keranjang kosong</p>
        <p style="font-size:0.75rem;margin-top:0.5rem">Klik produk untuk menambahkan</p>
    </div>
@endforelse
