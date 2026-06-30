<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS - ProCell Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #f1f5f9; height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
        .pos-header { background: #0f172a; color: #fff; padding: 0.75rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .pos-header h1 { font-size: 1.125rem; font-weight: 700; }
        .pos-header h1 i { color: #f59e0b; margin-right: 0.5rem; }
        .pos-header .header-right { display: flex; align-items: center; gap: 1rem; font-size: 0.8125rem; color: #94a3b8; }
        .pos-header .header-right a { color: #94a3b8; text-decoration: none; }
        .pos-header .header-right a:hover { color: #f59e0b; }
        .pos-body { display: flex; flex: 1; overflow: hidden; }
        .pos-products { flex: 1; display: flex; flex-direction: column; overflow: hidden; padding: 1rem 1rem 0 1rem; gap: 0.75rem; }
        .pos-toolbar { display: flex; gap: 0.75rem; align-items: center; flex-shrink: 0; }
        .pos-toolbar input { flex: 1; padding: 0.625rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.875rem; outline: none; transition: border-color 0.2s; }
        .pos-toolbar input:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1); }
        .pos-toolbar select { padding: 0.625rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.875rem; outline: none; background: #fff; cursor: pointer; }
        .product-grid { flex: 1; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.75rem; padding-bottom: 1rem; }
        .product-card { background: #fff; border-radius: 12px; border: 1.5px solid #e2e8f0; overflow: hidden; cursor: pointer; transition: all 0.2s; }
        .product-card:hover { border-color: #f59e0b; box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-2px); }
        .product-card:active { transform: scale(0.97); }
        .product-card .thumb { height: 100px; background: #f8fafc; display: flex; align-items: center; justify-content: center; padding: 0.5rem; }
        .product-card .thumb img { max-height: 100%; max-width: 100%; object-fit: contain; }
        .product-card .thumb .no-img { color: #cbd5e1; font-size: 2rem; }
        .product-card .info { padding: 0.625rem; }
        .product-card .info .brand { font-size: 0.6875rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
        .product-card .info .name { font-size: 0.8125rem; font-weight: 600; color: #0f172a; margin: 0.125rem 0; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-card .info .price { font-size: 0.9375rem; font-weight: 700; color: #d97706; }
        .product-card .info .stock { font-size: 0.6875rem; color: #64748b; }
        .product-card .info .stock.low { color: #ef4444; }
        .pos-cart { width: 380px; background: #fff; border-left: 1px solid #e2e8f0; display: flex; flex-direction: column; flex-shrink: 0; }
        .pos-cart-header { padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .pos-cart-header h2 { font-size: 1rem; font-weight: 700; }
        .pos-cart-header h2 span { background: #f59e0b; color: #fff; font-size: 0.75rem; padding: 0.125rem 0.5rem; border-radius: 999px; }
        .pos-cart-header button { background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.8125rem; }
        .pos-cart-header button:hover { text-decoration: underline; }
        .pos-cart-items { flex: 1; overflow-y: auto; padding: 0.75rem; }
        .pos-cart-empty { text-align: center; padding: 3rem 1rem; color: #94a3b8; }
        .pos-cart-empty i { font-size: 2.5rem; margin-bottom: 0.75rem; }
        .pos-cart-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem; border: 1px solid #f1f5f9; border-radius: 10px; margin-bottom: 0.5rem; }
        .pos-cart-item .item-info { flex: 1; min-width: 0; }
        .pos-cart-item .item-info .item-name { font-size: 0.8125rem; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .pos-cart-item .item-info .item-price { font-size: 0.75rem; color: #64748b; }
        .pos-cart-item .qty-control { display: flex; align-items: center; gap: 0.25rem; }
        .pos-cart-item .qty-control button { width: 28px; height: 28px; border: 1px solid #e2e8f0; border-radius: 6px; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: #475569; transition: all 0.15s; }
        .pos-cart-item .qty-control button:hover { background: #f1f5f9; border-color: #cbd5e1; }
        .pos-cart-item .qty-control span { width: 28px; text-align: center; font-size: 0.875rem; font-weight: 600; }
        .pos-cart-item .item-subtotal { font-size: 0.8125rem; font-weight: 700; color: #d97706; min-width: 70px; text-align: right; }
        .pos-cart-item .item-remove { background: none; border: none; color: #cbd5e1; cursor: pointer; padding: 0.25rem; font-size: 0.75rem; }
        .pos-cart-item .item-remove:hover { color: #ef4444; }
        .pos-cart-footer { padding: 1rem 1.25rem; border-top: 1px solid #e2e8f0; }
        .pos-cart-footer .total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; }
        .pos-cart-footer .total-row span:first-child { font-size: 0.875rem; font-weight: 600; color: #475569; }
        .pos-cart-footer .total-row .total-amount { font-size: 1.375rem; font-weight: 800; color: #0f172a; }
        .pos-cart-footer select, .pos-cart-footer input { width: 100%; padding: 0.5rem 0.75rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.8125rem; margin-bottom: 0.5rem; outline: none; }
        .pos-cart-footer select:focus, .pos-cart-footer input:focus { border-color: #f59e0b; }
        .pos-cart-footer .btn-checkout { width: 100%; padding: 0.75rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.9375rem; cursor: pointer; transition: all 0.2s; }
        .pos-cart-footer .btn-checkout:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3); }
        .pos-cart-footer .btn-checkout:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .pos-notification { position: fixed; top: 1rem; right: 1rem; background: #0f172a; color: #fff; padding: 0.75rem 1.25rem; border-radius: 10px; font-size: 0.8125rem; z-index: 9999; transform: translateX(120%); transition: transform 0.3s; box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
        .pos-notification.show { transform: translateX(0); }
        .pos-notification.error { background: #dc2626; }
        .pos-notification.success { background: #059669; }
        .loading-spinner { display: inline-block; width: 1rem; height: 1rem; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        #submit-checkout:disabled { opacity: 0.6; cursor: not-allowed; }
        .bank-options { display: none; }
        .bank-options.show { display: block; }
        .btn-outline-small { background: none; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.25rem 0.5rem; font-size: 0.75rem; color: #64748b; cursor: pointer; }
        .btn-outline-small:hover { border-color: #94a3b8; }
    </style>
</head>
<body>
    <div class="pos-header">
        <h1><i class="fas fa-cash-register"></i> POS - ProCell Store</h1>
        <div class="header-right">
            <span><i class="fas fa-user"></i> {{ auth()->user()->name }}</span>
            <a href="{{ url('/admin') }}"><i class="fas fa-th-large"></i> Dashboard</a>
        </div>
    </div>

    <div class="pos-body">
        {{-- Product Grid --}}
        <div class="pos-products">
            <div class="pos-toolbar">
                <input type="text" id="search-pos" placeholder="Cari produk, SKU, atau brand..." autofocus>
                <select id="category-filter">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div id="product-grid" class="product-grid">
                @include('admin.pos._products', ['products' => $products])
            </div>
        </div>

        {{-- Cart --}}
        <div class="pos-cart">
            <div class="pos-cart-header">
                <h2><i class="fas fa-shopping-cart"></i> Pesanan <span id="cart-count">{{ count($posCart) }}</span></h2>
                <button onclick="clearCart()" id="clear-cart-btn" {{ empty($posCart) ? 'style=display:none' : '' }}><i class="fas fa-trash-alt"></i> Hapus Semua</button>
            </div>
            <div id="pos-cart-items" class="pos-cart-items">
                @include('admin.pos._cart', ['posCart' => $posCart])
            </div>
            <div class="pos-cart-footer">
                <form id="checkout-form" method="POST" action="{{ route('admin.pos.checkout') }}">
                    @csrf
                    <div class="total-row">
                        <span>Total</span>
                        <span class="total-amount" id="cart-total">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                    </div>

                    <select name="customer_id" id="pos-customer">
                        <option value="">Walk-in Customer</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} {{ $c->phone ? '- '.$c->phone : '' }}</option>
                        @endforeach
                    </select>

                    <select name="payment_method" id="pos-payment" onchange="toggleBank()">
                        <option value="">-- Pilih Pembayaran --</option>
                        <option value="cash">Tunai</option>
                        <option value="bank_transfer">Transfer Bank</option>
                    </select>

                    <div id="bank-options" class="bank-options">
                        <select name="bank_account_id">
                            <option value="">-- Pilih Bank --</option>
                            @foreach($bankAccounts as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->bank_name }} - {{ $bank->account_number }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="text" name="notes" placeholder="Catatan (opsional)">

                    <button type="submit" class="btn-checkout" id="submit-checkout" disabled>
                        <i class="fas fa-check-circle"></i> Proses Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="notification" class="pos-notification"></div>

    <script>
    @if($errors->any())
        var errorMessages = @json($errors->all());
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() { notify(errorMessages.join('. '), 'error'); }, 200);
        });
    @endif

    @if(session('error'))
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() { notify('{{ session('error') }}', 'error'); }, 200);
        });
    @endif

    @if(session('success'))
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() { notify('{{ session('success') }}', 'success'); }, 200);
        });
    @endif
    </script>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        function notify(msg, type = 'success') {
            const el = document.getElementById('notification');
            el.textContent = msg;
            el.className = 'pos-notification ' + type + ' show';
            setTimeout(() => el.classList.remove('show'), 2500);
        }

        function updateCart(data) {
            document.getElementById('pos-cart-items').innerHTML = data.cart_html;
            document.getElementById('cart-count').textContent = data.count;
            document.getElementById('cart-total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total);
            const clearBtn = document.getElementById('clear-cart-btn');
            clearBtn.style.display = data.count > 0 ? 'inline' : 'none';
            toggleCheckout();
        }

        function toggleCheckout() {
            const btn = document.getElementById('submit-checkout');
            const count = parseInt(document.getElementById('cart-count').textContent);
            const payment = document.getElementById('pos-payment').value;
            btn.disabled = !(count > 0 && payment);
        }

        function toggleBank() {
            const val = document.getElementById('pos-payment').value;
            document.getElementById('bank-options').className = 'bank-options' + (val === 'bank_transfer' ? ' show' : '');
            toggleCheckout();
        }

        document.getElementById('pos-payment').addEventListener('change', toggleCheckout);

        function clearCart() {
            document.getElementById('pos-cart-items').innerHTML =
                '<div class="pos-cart-empty"><i class="fas fa-shopping-cart"></i><p>Keranjang kosong</p></div>';
            document.getElementById('cart-count').textContent = '0';
            document.getElementById('cart-total').textContent = 'Rp 0';
            document.getElementById('clear-cart-btn').style.display = 'none';
            toggleCheckout();
            fetch('{{ route('admin.pos.remove') }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf},
                body: JSON.stringify({product_id: 0})
            });
        }

        function addProduct(productId, name) {
            fetch('{{ route('admin.pos.add') }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf},
                body: JSON.stringify({product_id: productId, quantity: 1})
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    updateCart(d);
                } else {
                    notify(d.message, 'error');
                }
            })
            .catch(() => notify('Gagal menambahkan produk', 'error'));
        }

        function updateQty(productId, delta) {
            const qtyEl = document.querySelector(`.qty-value[data-pid="${productId}"]`);
            if (!qtyEl) return;
            const newQty = Math.max(1, parseInt(qtyEl.textContent) + delta);
            qtyEl.textContent = newQty;
            fetch('{{ route('admin.pos.update') }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf},
                body: JSON.stringify({product_id: productId, quantity: newQty})
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) updateCart(d);
                else notify(d.message, 'error');
            })
            .catch(() => notify('Gagal update jumlah', 'error'));
        }

        function removeProduct(productId) {
            fetch('{{ route('admin.pos.remove') }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf},
                body: JSON.stringify({product_id: productId})
            })
            .then(r => r.json())
            .then(d => { if (d.success) updateCart(d); })
            .catch(() => notify('Gagal hapus produk', 'error'));
        }

        let searchTimer;
        document.getElementById('search-pos').addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(loadProducts, 300);
        });
        document.getElementById('category-filter').addEventListener('change', loadProducts);

        function loadProducts(page = 1) {
            const q = document.getElementById('search-pos').value;
            const cat = document.getElementById('category-filter').value;
            const url = '{{ route('admin.pos.search') }}?q=' + encodeURIComponent(q) + '&category_id=' + cat + '&page=' + page;
            fetch(url)
                .then(r => r.json())
                .then(d => {
                    document.getElementById('product-grid').innerHTML = d.html;
                    if (d.has_more) {
                        const loadMore = document.createElement('div');
                        loadMore.className = 'col-span-full text-center py-4';
                        loadMore.innerHTML = '<button onclick="loadProducts(' + d.next_page + ')" class="btn-outline-small">Muat Lainnya</button>';
                        document.getElementById('product-grid').appendChild(loadMore);
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleCheckout();
            toggleBank();
        });

        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const payment = document.getElementById('pos-payment').value;
            if (!payment) { e.preventDefault(); notify('Pilih metode pembayaran!', 'error'); return; }
            const btn = document.getElementById('submit-checkout');
            btn.disabled = true;
            btn.innerHTML = '<span class="loading-spinner"></span> Memproses...';
        });
    </script>
</body>
</html>