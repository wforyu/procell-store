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
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background: #f0f2f5; height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .pos-header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 0.75rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .pos-header h1 { font-size: 1.125rem; font-weight: 700; letter-spacing: -0.01em; }
        .pos-header h1 i { color: #f59e0b; margin-right: 0.5rem; }
        .pos-header .header-right { display: flex; align-items: center; gap: 1.25rem; font-size: 0.8125rem; color: #94a3b8; }
        .pos-header .header-right a { color: #94a3b8; text-decoration: none; display: flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; border-radius: 8px; transition: all 0.2s; }
        .pos-header .header-right a:hover { color: #fff; background: rgba(255,255,255,0.08); }
        .kbd { display: inline-block; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); color: #94a3b8; font-size: 0.625rem; font-weight: 600; padding: 0.125rem 0.375rem; border-radius: 4px; font-family: inherit; letter-spacing: 0.02em; }
        .pos-body { display: flex; flex: 1; overflow: hidden; }
        .pos-products { flex: 1; display: flex; flex-direction: column; overflow: hidden; padding: 1rem 1rem 0 1rem; gap: 0.75rem; }
        .pos-toolbar { display: flex; gap: 0.75rem; align-items: center; flex-shrink: 0; }
        .pos-toolbar .search-wrap { flex: 1; position: relative; }
        .pos-toolbar .search-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.875rem; pointer-events: none; }
        .pos-toolbar .search-wrap input { width: 100%; padding: 0.625rem 1rem 0.625rem 2.5rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.875rem; outline: none; background: #fff; transition: all 0.2s; }
        .pos-toolbar .search-wrap input:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1); }
        .pos-toolbar select { padding: 0.625rem 2rem 0.625rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.8125rem; outline: none; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2394a3b8'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E") no-repeat right 0.75rem center; background-size: 1.25rem; cursor: pointer; appearance: none; color: #475569; font-weight: 500; transition: border-color 0.2s; }
        .pos-toolbar select:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1); }
        .product-grid { flex: 1; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(155px, 1fr)); gap: 0.75rem; padding: 0.25rem 0 1rem 0; }
        .product-card { background: #fff; border-radius: 12px; border: 1.5px solid #e8ecf1; overflow: hidden; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); position: relative; }
        .product-card:hover { border-color: #f59e0b; box-shadow: 0 8px 24px rgba(245, 158, 11, 0.12); transform: translateY(-3px); }
        .product-card:active { transform: scale(0.96); }
        .product-card .thumb { height: 110px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); display: flex; align-items: center; justify-content: center; padding: 0.75rem; }
        .product-card .thumb img { max-height: 100%; max-width: 100%; object-fit: contain; transition: transform 0.3s; }
        .product-card:hover .thumb img { transform: scale(1.05); }
        .product-card .thumb .no-img { color: #cbd5e1; font-size: 2.25rem; }
        .product-card .info { padding: 0.75rem; }
        .product-card .info .brand { font-size: 0.625rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; font-weight: 600; margin-bottom: 0.125rem; }
        .product-card .info .name { font-size: 0.8125rem; font-weight: 600; color: #0f172a; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-card .info .price { font-size: 0.9375rem; font-weight: 700; color: #d97706; margin-top: 0.25rem; }
        .product-card .info .stock { font-size: 0.6875rem; color: #64748b; margin-top: 0.125rem; }
        .product-card .info .stock.low { color: #ef4444; font-weight: 600; }
        .product-card .stock-badge { position: absolute; top: 0.5rem; right: 0.5rem; background: #dc2626; color: #fff; font-size: 0.625rem; font-weight: 700; padding: 0.125rem 0.375rem; border-radius: 4px; }
        .pos-cart { width: 420px; background: #fff; border-left: 1px solid #e2e8f0; display: flex; flex-direction: column; flex-shrink: 0; }
        .pos-cart-header { padding: 1rem 1.25rem; border-bottom: 1px solid #e8ecf1; display: flex; justify-content: space-between; align-items: center; background: #fafbfc; }
        .pos-cart-header h2 { font-size: 0.9375rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 0.5rem; }
        .pos-cart-header h2 span { background: #f59e0b; color: #fff; font-size: 0.75rem; font-weight: 700; padding: 0.125rem 0.5rem; border-radius: 999px; min-width: 1.5rem; text-align: center; }
        .pos-cart-header button { background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.75rem; font-weight: 600; padding: 0.375rem 0.625rem; border-radius: 6px; transition: all 0.15s; }
        .pos-cart-header button:hover { background: #fef2f2; }
        .pos-cart-items { flex: 1; overflow-y: auto; padding: 0.75rem; }
        .pos-cart-empty { text-align: center; padding: 3rem 1.5rem; color: #94a3b8; }
        .pos-cart-empty i { font-size: 2.5rem; margin-bottom: 0.75rem; color: #cbd5e1; }
        .pos-cart-empty p { font-size: 0.875rem; }
        .pos-cart-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border: 1px solid #f1f3f6; border-radius: 10px; margin-bottom: 0.5rem; transition: all 0.15s; }
        .pos-cart-item:hover { border-color: #e2e8f0; background: #fafbfc; }
        .pos-cart-item .item-info { flex: 1; min-width: 0; }
        .pos-cart-item .item-info .item-name { font-size: 0.8125rem; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .pos-cart-item .item-info .item-price { font-size: 0.75rem; color: #64748b; margin-top: 0.125rem; }
        .clickable-qty { cursor: pointer; border-radius: 4px; transition: background 0.15s; padding: 0 2px; }
        .clickable-qty:hover { background: #fef3c7; }
        .qty-control { display: flex; align-items: center; gap: 0.25rem; }
        .qty-control button { width: 30px; height: 30px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: #475569; transition: all 0.15s; }
        .qty-control button:hover { background: #f59e0b; border-color: #f59e0b; color: #fff; }
        .qty-control span { width: 30px; text-align: center; font-size: 0.9375rem; font-weight: 700; color: #0f172a; }
        .qty-control input.qty-input { width: 50px; text-align: center; font-size: 0.9375rem; font-weight: 700; color: #0f172a; border: 2px solid #f59e0b; border-radius: 6px; padding: 2px 4px; outline: none; background: #fffbeb; }
        .pos-cart-item .item-subtotal { font-size: 0.875rem; font-weight: 700; color: #d97706; min-width: 80px; text-align: right; }
        .pos-cart-item .item-remove { background: none; border: none; color: #cbd5e1; cursor: pointer; padding: 0.25rem; font-size: 0.8125rem; border-radius: 6px; transition: all 0.15s; }
        .pos-cart-item .item-remove:hover { color: #ef4444; background: #fef2f2; }
        .pos-cart-footer { padding: 1rem 1.25rem; border-top: 1px solid #e8ecf1; background: #fafbfc; overflow-y: auto; max-height: 55vh; }
        .pos-cart-footer .total-row { display: flex; justify-content: space-between; align-items: center; }
        .pos-cart-footer .total-row span:first-child { font-size: 0.875rem; font-weight: 600; color: #475569; }
        .pos-cart-footer .total-row .total-amount { font-size: 1.5rem; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; }
        .pos-cart-footer .separator { border: none; border-top: 2px dashed #e2e8f0; margin: 0.75rem 0; }
        .pos-cart-footer select, .pos-cart-footer input:not([type="hidden"]) { width: 100%; padding: 0.5rem 0.75rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.8125rem; margin-bottom: 0.5rem; outline: none; background: #fff; transition: border-color 0.2s; color: #0f172a; }
        .pos-cart-footer select:focus, .pos-cart-footer input:not([type="hidden"]):focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1); }
        .pos-cart-footer .btn-checkout { width: 100%; padding: 0.8125rem; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.9375rem; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .pos-cart-footer .btn-checkout:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3); }
        .pos-cart-footer .btn-checkout:active { transform: translateY(0); }
        .pos-cart-footer .btn-checkout:disabled { opacity: 0.4; cursor: not-allowed; transform: none; box-shadow: none; }
        .pos-notification { position: fixed; top: 1rem; right: 1rem; background: #0f172a; color: #fff; padding: 0.75rem 1.25rem; border-radius: 10px; font-size: 0.8125rem; z-index: 9999; transform: translateX(120%); transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 8px 32px rgba(0,0,0,0.15); max-width: 360px; }
        .pos-notification.show { transform: translateX(0); }
        .pos-notification.error { background: linear-gradient(135deg, #dc2626, #b91c1c); }
        .pos-notification.success { background: linear-gradient(135deg, #059669, #047857); }
        .loading-spinner { display: inline-block; width: 1.125rem; height: 1.125rem; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .search-spinner { display: inline-block; width: 1.25rem; height: 1.25rem; border: 2px solid #e2e8f0; border-top-color: #f59e0b; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes fadeSlideIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
        .bank-options { display: none; }
        .bank-options.show { display: block; animation: fadeSlideIn 0.2s ease; }
        .btn-outline-small { background: #fff; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.8125rem; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.15s; }
        .btn-outline-small:hover { border-color: #f59e0b; color: #f59e0b; background: #fffbeb; }
        .cash-amount { display: none; margin-bottom: 0.5rem; }
        .cash-amount.show { display: block; animation: fadeSlideIn 0.2s ease; }
        .cash-amount .input-wrap { position: relative; }
        .cash-amount .input-wrap .prefix { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 0.875rem; font-weight: 700; color: #0f172a; pointer-events: none; }
        .cash-amount input { width: 100%; padding: 0.625rem 0.75rem 0.625rem 2.25rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 1.25rem; font-weight: 800; outline: none; text-align: right; background: #fff; color: #0f172a; letter-spacing: -0.02em; transition: border-color 0.2s; }
        .cash-amount input:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1); }
        .cash-amount .change-row { display: flex; justify-content: space-between; align-items: center; padding: 0.625rem 0 0 0; font-size: 0.875rem; border-top: 1px solid #f1f3f6; margin-top: 0.5rem; }
        .cash-amount .change-row .change-label { font-weight: 600; color: #475569; }
        .cash-amount .change-row .change-amount { font-size: 1.25rem; font-weight: 800; color: #059669; }
        .cash-amount .change-row .change-amount.negative { color: #dc2626; }
        .btn-icon { background: none; border: none; cursor: pointer; color: #f59e0b; padding: 0.25rem; font-size: 0.875rem; border-radius: 6px; transition: all 0.15s; }
        .btn-icon:hover { background: #fffbeb; }
        .customer-wrap { display: flex; gap: 0.375rem; align-items: stretch; }
        .customer-wrap select { flex: 1; margin-bottom: 0; }
        .customer-wrap .btn-add-customer { width: 40px; flex-shrink: 0; background: #fff; border: 1.5px solid #e2e8f0; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 1rem; transition: all 0.15s; }
        .customer-wrap .btn-add-customer:hover { border-color: #f59e0b; background: #fffbeb; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9998; display: none; align-items: center; justify-content: center; animation: fadeIn 0.15s ease; }
        .modal-overlay.show { display: flex; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-box { background: #fff; border-radius: 14px; padding: 1.5rem; width: 380px; max-width: 90vw; box-shadow: 0 20px 60px rgba(0,0,0,0.15); animation: modalSlide 0.2s ease; }
        @keyframes modalSlide { from { opacity: 0; transform: translateY(-20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .modal-box h3 { font-size: 1.0625rem; font-weight: 700; color: #0f172a; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .modal-box .modal-field { margin-bottom: 0.875rem; }
        .modal-box .modal-field label { display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.04em; }
        .modal-box .modal-field input { width: 100%; padding: 0.625rem 0.75rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; outline: none; transition: border-color 0.2s; }
        .modal-box .modal-field input:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1); }
        .modal-actions { display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1rem; }
        .modal-actions button { padding: 0.5rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: 0.8125rem; cursor: pointer; transition: all 0.15s; border: none; }
        .modal-actions .btn-cancel { background: #f1f3f6; color: #475569; }
        .modal-actions .btn-cancel:hover { background: #e2e8f0; }
        .modal-actions .btn-save { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; }
        .modal-actions .btn-save:hover { box-shadow: 0 4px 12px rgba(245,158,11,0.3); }
        .modal-actions .btn-save:disabled { opacity: 0.5; cursor: not-allowed; box-shadow: none; }
        .discount-section { margin-bottom: 0.5rem; }
        .discount-toggle { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; }
        .discount-toggle label { font-size: 0.8125rem; font-weight: 600; color: #475569; cursor: pointer; display: flex; align-items: center; gap: 0.375rem; user-select: none; }
        .discount-toggle input[type="checkbox"] { width: auto; margin: 0; accent-color: #f59e0b; cursor: pointer; }
        .discount-fields { display: none; animation: fadeSlideIn 0.2s ease; }
        .discount-fields.show { display: block; }
        .discount-row { display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem; }
        .discount-row select { flex: 0 0 120px; margin-bottom: 0; }
        .discount-row input { flex: 1; margin-bottom: 0; }
        .discount-summary { font-size: 0.8125rem; padding: 0.5rem 0.75rem; background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; margin-bottom: 0.5rem; display: none; }
        .discount-summary.show { display: flex; justify-content: space-between; align-items: center; }
        .discount-summary .label { color: #92400e; font-weight: 500; }
        .discount-summary .value { color: #dc2626; font-weight: 700; }
        .discount-summary .remove-discount { background: none; border: none; color: #dc2626; cursor: pointer; font-size: 0.75rem; padding: 0.125rem 0.375rem; border-radius: 4px; }
        .discount-summary .remove-discount:hover { background: #fef2f2; }
        .grand-total { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; margin-bottom: 0.5rem; border-radius: 8px; }
        .grand-total.has-discount { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .grand-total .label { font-size: 0.875rem; font-weight: 700; color: #166534; }
        .grand-total .value { font-size: 1.375rem; font-weight: 800; color: #166534; letter-spacing: -0.02em; }
        .pos-history { border-top: 1px solid #e2e8f0; background: #fff; flex-shrink: 0; }
        .pos-history-header { padding: 0.5rem 1rem; background: #f8fafc; cursor: pointer; display: flex; justify-content: space-between; align-items: center; user-select: none; transition: background 0.15s; }
        .pos-history-header:hover { background: #f1f5f9; }
        .pos-history-header h3 { font-size: 0.8125rem; font-weight: 600; color: #475569; display: flex; align-items: center; gap: 0.5rem; }
        .pos-history-header h3 span { background: #e2e8f0; color: #475569; font-size: 0.6875rem; font-weight: 700; padding: 0.0625rem 0.375rem; border-radius: 999px; }
        .pos-history-header .toggle-icon { color: #94a3b8; font-size: 0.75rem; transition: transform 0.2s; }
        .pos-history-header .toggle-icon.open { transform: rotate(180deg); }
        .pos-history-body { max-height: 0; overflow: hidden; transition: max-height 0.25s ease; }
        .pos-history-body.open { max-height: 280px; overflow-y: auto; }
        .history-list { padding: 0.5rem 0; }
        .history-item { display: flex; justify-content: space-between; align-items: center; padding: 0.625rem 1rem; text-decoration: none; transition: background 0.1s; border-bottom: 1px solid #f8fafc; }
        .history-item:hover { background: #f8fafc; }
        .history-item:last-child { border-bottom: none; }
        .history-left .history-number { font-size: 0.75rem; font-weight: 600; color: #0f172a; }
        .history-left .history-customer { font-size: 0.6875rem; color: #64748b; margin-top: 0.125rem; }
        .history-right { text-align: right; }
        .history-right .history-total { font-size: 0.8125rem; font-weight: 700; color: #d97706; }
        .history-right .history-meta { display: flex; gap: 0.375rem; align-items: center; justify-content: flex-end; margin-top: 0.125rem; }
        .history-right .history-time { font-size: 0.625rem; color: #94a3b8; }
        .history-right .history-payment { font-size: 0.625rem; color: #fff; background: #64748b; padding: 0.0625rem 0.375rem; border-radius: 3px; font-weight: 600; }
        .history-empty { text-align: center; padding: 1.5rem 1rem; color: #94a3b8; }
        .history-empty i { font-size: 1.5rem; margin-bottom: 0.375rem; color: #cbd5e1; }
        .history-empty p { font-size: 0.75rem; }
        .numpad-toggle-btn { background: none; border: none; color: #64748b; cursor: pointer; font-size: 0.875rem; padding: 0.375rem 0.5rem; border-radius: 6px; transition: all 0.15s; }
        .numpad-toggle-btn:hover { background: #e2e8f0; color: #0f172a; }
        .numpad-toggle-btn.active { background: #f59e0b; color: #fff; }
        .numpad-toggle-btn.active:hover { background: #d97706; }
        .numpad-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: #f8fafc; border-top: 2px solid #e2e8f0; padding: 0.75rem; z-index: 50; transform: translateY(100%); transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 -8px 24px rgba(0,0,0,0.08); }
        .numpad-overlay.show { transform: translateY(0); }
        .numpad-overlay .numpad-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.375rem; }
        .numpad-overlay .numpad-btn { height: 44px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; font-size: 1rem; font-weight: 700; color: #0f172a; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.1s; user-select: none; }
        .numpad-overlay .numpad-btn:hover { background: #fef3c7; border-color: #f59e0b; }
        .numpad-overlay .numpad-btn:active { transform: scale(0.95); background: #fde68a; }
        .numpad-overlay .numpad-btn.fn { font-size: 0.75rem; font-weight: 600; color: #475569; }
        .numpad-overlay .numpad-btn.fn:hover { background: #fee2e2; border-color: #ef4444; color: #dc2626; }
        .numpad-overlay .numpad-btn.enter { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; border-color: #d97706; }
        .numpad-overlay .numpad-btn.enter:hover { box-shadow: 0 2px 8px rgba(245,158,11,0.3); }
        .numpad-overlay .numpad-label { font-size: 0.625rem; color: #94a3b8; text-align: center; padding-bottom: 0.375rem; display: flex; align-items: center; justify-content: center; gap: 0.25rem; }
        .pos-cart { position: relative; overflow: visible; }
    </style>
</head>
<body>
    <div class="pos-header">
        <h1><i class="fas fa-cash-register"></i> POS - ProCell Store</h1>
        <div class="header-right">
            <span><span class="kbd">F2</span> Cari <span class="kbd">F3</span> Bayar <span class="kbd">Esc</span> Hapus</span>
            <span><i class="fas fa-user"></i> {{ auth()->user()->name }}</span>
            @unless(auth()->user()->hasRole('Kasir'))
                <a href="{{ url('/admin') }}"><i class="fas fa-th-large"></i> Dashboard</a>
            @endunless
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" style="background:none;border:none;color:#94a3b8;cursor:pointer;display:flex;align-items:center;gap:0.375rem;padding:0.375rem 0.75rem;border-radius:8px;font-size:0.8125rem;font-family:inherit;transition:all 0.2s" onmouseover="this.style.color='#fff';this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.color='#94a3b8';this.style.background='none'">
                    <i class="fas fa-right-from-bracket"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <div class="pos-body">
        {{-- Product Grid --}}
        <div class="pos-products">
            <div class="pos-toolbar">
                <div class="search-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" id="search-pos" placeholder="Cari produk, SKU, atau brand..." autofocus>
                </div>
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
                <div style="display:flex;align-items:center;gap:0.25rem">
                    <button class="numpad-toggle-btn" onclick="toggleNumpad()" id="numpad-toggle" title="Keypad Numerik"><i class="fas fa-keyboard"></i></button>
                    <button onclick="clearCart()" id="clear-cart-btn" {{ empty($posCart) ? 'style=display:none' : '' }}><i class="fas fa-trash-alt"></i> Hapus Semua</button>
                </div>
            </div>
            <div id="pos-cart-items" class="pos-cart-items">
                @include('admin.pos._cart', ['posCart' => $posCart])
            </div>
            {{-- Floating Numpad Overlay --}}
            <div class="numpad-overlay" id="pos-numpad">
                <div class="numpad-label"><i class="fas fa-keyboard"></i> Keypad Numerik</div>
                <div class="numpad-grid">
                    <button type="button" class="numpad-btn" onclick="numpadKey('7')">7</button>
                    <button type="button" class="numpad-btn" onclick="numpadKey('8')">8</button>
                    <button type="button" class="numpad-btn" onclick="numpadKey('9')">9</button>
                    <button type="button" class="numpad-btn fn" onclick="numpadBksp()"><i class="fas fa-delete-left"></i></button>
                    <button type="button" class="numpad-btn" onclick="numpadKey('4')">4</button>
                    <button type="button" class="numpad-btn" onclick="numpadKey('5')">5</button>
                    <button type="button" class="numpad-btn" onclick="numpadKey('6')">6</button>
                    <button type="button" class="numpad-btn fn" onclick="numpadClear()"><i class="fas fa-eraser"></i></button>
                    <button type="button" class="numpad-btn" onclick="numpadKey('1')">1</button>
                    <button type="button" class="numpad-btn" onclick="numpadKey('2')">2</button>
                    <button type="button" class="numpad-btn" onclick="numpadKey('3')">3</button>
                    <button type="button" class="numpad-btn fn" onclick="numpadKey('00')">00</button>
                    <button type="button" class="numpad-btn fn" onclick="numpadKey('0')">0</button>
                    <button type="button" class="numpad-btn" onclick="numpadKey(',')">,</button>
                    <button type="button" class="numpad-btn fn" onclick="numpadTab()" title="Pindah field"><i class="fas fa-arrow-right-arrow-left"></i></button>
                    <button type="button" class="numpad-btn enter" onclick="numpadEnter()"><i class="fas fa-check"></i></button>
                </div>
            </div>
            <div class="pos-cart-footer">
                <form id="checkout-form" method="POST" action="{{ route('admin.pos.checkout') }}">
                    @csrf
                    <div class="total-row">
                        <span>Total</span>
                        <span class="total-amount" id="cart-total">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                    </div>

                    <hr class="separator">

                    {{-- Diskon --}}
                    <div class="discount-section">
                        <div class="discount-toggle">
                            <label>
                                <input type="checkbox" id="discount-enable" onchange="toggleDiscount()">
                                <i class="fas fa-tag"></i> Diskon
                            </label>
                        </div>
                        <div class="discount-fields" id="discount-fields">
                            <div class="discount-row">
                                <select id="discount-type" onchange="calculateDiscount()">
                                    <option value="percentage">Persen (%)</option>
                                    <option value="nominal">Nominal (Rp)</option>
                                </select>
                                <input type="text" id="discount-value" placeholder="0" oninput="calculateDiscount()">
                            </div>
                            <div class="discount-summary" id="discount-summary">
                                <span class="label">Diskon</span>
                                <span>
                                    <span class="value" id="discount-display">Rp 0</span>
                                    <button type="button" class="remove-discount" onclick="removeDiscount()"><i class="fas fa-times"></i></button>
                                </span>
                            </div>
                            <input type="hidden" name="discount_type" id="input-discount-type" value="">
                            <input type="hidden" name="discount_value" id="input-discount-value" value="0">
                            <input type="hidden" name="discount_amount" id="input-discount-amount" value="0">
                        </div>
                    </div>

                    <div class="grand-total" id="grand-total">
                        <span class="label">Grand Total</span>
                        <span class="value" id="grand-total-value">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                    </div>

                    {{-- Customer --}}
                    <div class="customer-wrap">
                        <select name="customer_id" id="pos-customer">
                            <option value="">Walk-in Customer</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} {{ $c->phone ? '- '.$c->phone : '' }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn-add-customer" onclick="openCustomerModal()" title="Tambah Pelanggan Baru">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

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

                    <div id="cash-amount" class="cash-amount">
                        <div class="input-wrap">
                            <span class="prefix">Rp</span>
                            <input type="text" id="amount-paid" placeholder="0" inputmode="numeric">
                            <input type="hidden" name="amount_paid" id="amount-paid-raw" value="0">
                        </div>
                        <div class="change-row">
                            <span class="change-label">Kembalian</span>
                            <span class="change-amount" id="change-amount">Rp 0</span>
                        </div>
                    </div>

                    <input type="text" name="notes" placeholder="Catatan (opsional)">

                    <button type="submit" class="btn-checkout" id="submit-checkout" disabled>
                        <i class="fas fa-check-circle"></i> Proses Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Riwayat POS Hari Ini --}}
    <div class="pos-history" id="pos-history">
        <div class="pos-history-header" onclick="toggleHistory()">
            <h3><i class="fas fa-clock-rotate"></i> Riwayat POS Hari Ini <span id="history-count">{{ $todayOrders->count() }}</span></h3>
            <i class="fas fa-chevron-down toggle-icon" id="history-toggle-icon"></i>
        </div>
        <div class="pos-history-body" id="pos-history-body">
            <div class="history-list" id="history-list">
                @include('admin.pos._history', ['orders' => $todayOrders])
            </div>
        </div>
    </div>

    {{-- Quick Customer Modal --}}
    <div class="modal-overlay" id="customer-modal">
        <div class="modal-box">
            <h3><i class="fas fa-user-plus" style="color:#f59e0b"></i> Pelanggan Baru</h3>
            <div class="modal-field">
                <label>Nama <span style="color:#ef4444">*</span></label>
                <input type="text" id="modal-customer-name" placeholder="Nama pelanggan" autofocus>
            </div>
            <div class="modal-field">
                <label>Telepon</label>
                <input type="text" id="modal-customer-phone" placeholder="08xxxxxxxxxx">
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeCustomerModal()">Batal</button>
                <button class="btn-save" id="modal-customer-save" onclick="saveCustomer()">Simpan</button>
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
            recalcAfterCartUpdate();
            toggleCheckout();
        }

        function recalcAfterCartUpdate() {
            const totalText = document.getElementById('cart-total').textContent.replace(/[^0-9]/g, '');
            const total = parseInt(totalText) || 0;
            updateGrandTotal(total);
            calculateChange();
            if (document.getElementById('discount-enable').checked) calculateDiscount();
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
            document.getElementById('cash-amount').className = 'cash-amount' + (val === 'cash' ? ' show' : '');
            toggleCheckout();
        }

        function formatRupiah(input) {
            let val = input.value.replace(/\D/g, '');
            document.getElementById('amount-paid-raw').value = val;
            if (val) {
                val = parseInt(val, 10);
                input.value = new Intl.NumberFormat('id-ID').format(val);
            } else {
                input.value = '';
            }
            calculateChange();
        }

        function getRawPaid() {
            return parseInt(document.getElementById('amount-paid').value.replace(/\D/g, '')) || 0;
        }

        function calculateChange() {
            const totalText = document.getElementById('grand-total-value').textContent.replace(/[^0-9]/g, '');
            const total = parseInt(totalText) || 0;
            const paid = getRawPaid();
            const change = paid - total;
            const changeEl = document.getElementById('change-amount');
            changeEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.max(0, change));
            changeEl.className = 'change-amount' + (change < 0 ? ' negative' : '');
        }

        document.getElementById('pos-payment').addEventListener('change', toggleCheckout);
        document.getElementById('amount-paid').addEventListener('input', function() { formatRupiah(this); });

        function clearCart() {
            document.getElementById('pos-cart-items').innerHTML =
                '<div class="pos-cart-empty"><i class="fas fa-shopping-cart"></i><p>Keranjang kosong</p></div>';
            document.getElementById('cart-count').textContent = '0';
            document.getElementById('cart-total').textContent = 'Rp 0';
            document.getElementById('clear-cart-btn').style.display = 'none';
            removeDiscount();
            recalcAfterCartUpdate();
            toggleCheckout();
            fetch('{{ route('admin.pos.clear') }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': csrf}
            });
        }

        // --- Diskon ---
        function toggleDiscount() {
            const checked = document.getElementById('discount-enable').checked;
            document.getElementById('discount-fields').className = 'discount-fields' + (checked ? ' show' : '');
            if (!checked) removeDiscount();
            else document.getElementById('discount-value').focus();
        }

        function calculateDiscount() {
            const totalText = document.getElementById('cart-total').textContent.replace(/[^0-9]/g, '');
            const total = parseInt(totalText) || 0;
            const type = document.getElementById('discount-type').value;
            const raw = document.getElementById('discount-value').value.replace(/\D/g, '');
            const val = parseInt(raw) || 0;
            let amount = 0;
            if (type === 'percentage') {
                amount = Math.min(total, Math.round(total * Math.min(val, 100) / 100));
            } else {
                amount = Math.min(total, val);
            }
            const summary = document.getElementById('discount-summary');
            const display = document.getElementById('discount-display');
            display.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            summary.className = 'discount-summary' + (amount > 0 ? ' show' : '');
            document.getElementById('input-discount-type').value = amount > 0 ? type : '';
            document.getElementById('input-discount-value').value = amount > 0 ? val : 0;
            document.getElementById('input-discount-amount').value = amount;
            updateGrandTotal(total);
            calculateChange();
        }

        function removeDiscount() {
            document.getElementById('discount-enable').checked = false;
            document.getElementById('discount-fields').className = 'discount-fields';
            document.getElementById('discount-summary').className = 'discount-summary';
            document.getElementById('discount-value').value = '';
            document.getElementById('input-discount-type').value = '';
            document.getElementById('input-discount-value').value = 0;
            document.getElementById('input-discount-amount').value = 0;
            const totalText = document.getElementById('cart-total').textContent.replace(/[^0-9]/g, '');
            const total = parseInt(totalText) || 0;
            updateGrandTotal(total);
            calculateChange();
        }

        function updateGrandTotal(total) {
            const discAmount = parseInt(document.getElementById('input-discount-amount').value) || 0;
            const grandTotal = total - discAmount;
            const grandEl = document.getElementById('grand-total-value');
            const grandBox = document.getElementById('grand-total');
            grandEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
            grandBox.className = 'grand-total' + (discAmount > 0 ? ' has-discount' : '');
        }

        // --- Quick Customer Modal ---
        function openCustomerModal() {
            document.getElementById('customer-modal').classList.add('show');
            document.getElementById('modal-customer-name').value = '';
            document.getElementById('modal-customer-phone').value = '';
            setTimeout(() => document.getElementById('modal-customer-name').focus(), 100);
        }

        function closeCustomerModal() {
            document.getElementById('customer-modal').classList.remove('show');
        }

        function saveCustomer() {
            const name = document.getElementById('modal-customer-name').value.trim();
            if (!name) { notify('Nama pelanggan wajib diisi!', 'error'); return; }
            const phone = document.getElementById('modal-customer-phone').value.trim();
            const btn = document.getElementById('modal-customer-save');
            btn.disabled = true;
            btn.innerHTML = '<span class="loading-spinner" style="width:1rem;height:1rem;border-width:2px"></span>';
            fetch('{{ route('admin.pos.customer-add') }}', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf},
                body: JSON.stringify({name: name, phone: phone})
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    const opt = document.createElement('option');
                    opt.value = d.customer.id;
                    opt.textContent = d.customer.name + (d.customer.phone ? ' - ' + d.customer.phone : '');
                    opt.selected = true;
                    document.getElementById('pos-customer').appendChild(opt);
                    closeCustomerModal();
                    notify('Pelanggan ' + d.customer.name + ' berhasil ditambahkan', 'success');
                } else {
                    notify(d.message || 'Gagal menambahkan pelanggan', 'error');
                }
            })
            .catch(() => notify('Gagal menambahkan pelanggan', 'error'))
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'Simpan';
            });
        }

        document.getElementById('customer-modal').addEventListener('click', function(e) {
            if (e.target === this) closeCustomerModal();
        });
        document.getElementById('modal-customer-phone').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') saveCustomer();
        });
        document.getElementById('modal-customer-name').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') document.getElementById('modal-customer-phone').focus();
        });

        // --- Riwayat Transaksi ---
        function toggleHistory() {
            const body = document.getElementById('pos-history-body');
            const icon = document.getElementById('history-toggle-icon');
            const isOpen = body.classList.toggle('open');
            icon.className = 'toggle-icon fas fa-chevron-down' + (isOpen ? ' open' : '');
        }

        function refreshHistory() {
            fetch('{{ route('admin.pos.history') }}')
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        document.getElementById('history-list').innerHTML = d.html;
                        document.getElementById('history-count').textContent = d.count;
                    }
                });
        }

        // --- Qty Direct Input ---
        function editQty(el, productId) {
            const current = parseInt(el.textContent);
            const input = document.createElement('input');
            input.type = 'number';
            input.className = 'qty-input';
            input.value = current;
            input.min = 1;
            el.replaceWith(input);
            input.focus();
            input.select();
            const commit = function() {
                const newQty = parseInt(input.value) || current;
                if (newQty !== current && newQty > 0) {
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
                // Restore span
                const span = document.createElement('span');
                span.className = 'qty-value clickable-qty';
                span.dataset.pid = productId;
                span.textContent = newQty;
                span.onclick = function() { editQty(this, productId); };
                input.replaceWith(span);
            };
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); commit(); }
                if (e.key === 'Escape') { input.value = current; commit(); }
            });
            input.addEventListener('blur', commit);
        }

        // --- Keyboard Shortcuts ---
        document.addEventListener('keydown', function(e) {
            // F2 - fokus search
            if (e.key === 'F2') {
                e.preventDefault();
                document.getElementById('search-pos').focus();
                document.getElementById('search-pos').select();
            }
            // F3 - fokus payment
            if (e.key === 'F3') {
                e.preventDefault();
                document.getElementById('pos-payment').focus();
            }
            // Escape - clear search + blur
            if (e.key === 'Escape') {
                const search = document.getElementById('search-pos');
                if (document.activeElement === search) {
                    search.value = '';
                    search.blur();
                    loadProducts(1);
                }
                // Close modal if open
                if (document.getElementById('customer-modal').classList.contains('show')) {
                    closeCustomerModal();
                }
            }
        });

        // Barcode/SKU scanner
        document.getElementById('search-pos').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const val = this.value.trim();
                if (val === '') return;
                e.preventDefault();
                fetch('{{ route('admin.pos.sku-add') }}', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf},
                    body: JSON.stringify({sku: val})
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        updateCart(d);
                        this.value = '';
                        notify('Produk ditambahkan', 'success');
                    } else {
                        notify(d.message, 'error');
                    }
                })
                .catch(() => notify('Gagal mencari SKU', 'error'));
            }
        });

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
                    document.getElementById('search-pos').focus();
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

        // --- Numpad Toggle ---
        function toggleNumpad() {
            const overlay = document.getElementById('pos-numpad');
            const btn = document.getElementById('numpad-toggle');
            const isOpen = overlay.classList.toggle('show');
            btn.classList.toggle('active', isOpen);
            if (isOpen) {
                // Focus search so numpad keys work immediately
                document.getElementById('search-pos').focus();
            }
        }

        // Close numpad when clicking outside the cart
        document.addEventListener('click', function(e) {
            const overlay = document.getElementById('pos-numpad');
            const btn = document.getElementById('numpad-toggle');
            if (overlay.classList.contains('show') && !e.target.closest('.pos-cart')) {
                overlay.classList.remove('show');
                btn.classList.remove('active');
            }
        });

        // --- Virtual Numpad ---
        function numpadKey(key) {
            const el = document.activeElement;
            if (!el || !(el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.isContentEditable)) {
                // If no input focused, send to search
                const search = document.getElementById('search-pos');
                search.focus();
                search.value += key;
                search.dispatchEvent(new Event('input'));
                return;
            }
            if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                const start = el.selectionStart;
                const end = el.selectionEnd;
                const val = el.value;
                el.value = val.substring(0, start) + key + val.substring(end);
                el.selectionStart = el.selectionEnd = start + key.length;
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }

        function numpadBksp() {
            const el = document.activeElement;
            if (!el || !(el.tagName === 'INPUT' || el.tagName === 'TEXTAREA')) {
                return;
            }
            const start = el.selectionStart;
            const end = el.selectionEnd;
            if (end - start > 0) {
                el.value = el.value.substring(0, start) + el.value.substring(end);
                el.selectionStart = el.selectionEnd = start;
            } else if (start > 0) {
                el.value = el.value.substring(0, start - 1) + el.value.substring(start);
                el.selectionStart = el.selectionEnd = start - 1;
            }
            el.dispatchEvent(new Event('input', { bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));
        }

        function numpadClear() {
            const el = document.activeElement;
            if (!el || !(el.tagName === 'INPUT' || el.tagName === 'TEXTAREA')) {
                return;
            }
            el.value = '';
            el.dispatchEvent(new Event('input', { bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));
        }

        function numpadEnter() {
            const el = document.activeElement;
            if (!el) return;
            el.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));
            // If it's direct qty input, blur to commit
            if (el.classList.contains('qty-input')) el.blur();
        }

        function numpadTab() {
            const inputs = document.querySelectorAll('#pos-cart-footer input, #pos-cart-footer select, #search-pos, #category-filter, #amount-paid, .qty-input');
            const active = document.activeElement;
            let idx = -1;
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i] === active) { idx = i; break; }
            }
            // If the active is a numpad-input target, cycle; otherwise focus search
            const next = idx >= 0 ? inputs[(idx + 1) % inputs.length] : document.getElementById('search-pos');
            if (next) next.focus();
        }

        let searchTimer;
        let currentSearchPage = 1;
        document.getElementById('search-pos').addEventListener('input', function() {
            clearTimeout(searchTimer);
            currentSearchPage = 1;
            showSearchLoading();
            searchTimer = setTimeout(function() { loadProducts(1); }, 300);
        });
        document.getElementById('category-filter').addEventListener('change', function() {
            currentSearchPage = 1;
            showSearchLoading();
            loadProducts(1);
        });

        function showSearchLoading() {
            const grid = document.getElementById('product-grid');
            grid.innerHTML = '<div class="col-span-full text-center py-12 text-gray-400"><div class="search-spinner" style="margin:0 auto 1rem"></div><p>Mencari produk...</p></div>';
        }

        function loadProducts(page) {
            if (!page) page = currentSearchPage;
            const q = document.getElementById('search-pos').value;
            const cat = document.getElementById('category-filter').value;
            const url = '{{ route('admin.pos.search') }}?q=' + encodeURIComponent(q) + '&category_id=' + cat + '&page=' + page;
            fetch(url)
                .then(r => r.json())
                .then(d => {
                    if (page === 1) {
                        document.getElementById('product-grid').innerHTML = d.html;
                    } else {
                        const existingBtn = document.getElementById('pos-load-more');
                        if (existingBtn) existingBtn.remove();
                        document.getElementById('product-grid').insertAdjacentHTML('beforeend', d.html);
                    }
                    if (d.has_more) {
                        const grid = document.getElementById('product-grid');
                        const loadMore = document.createElement('div');
                        loadMore.id = 'pos-load-more';
                        loadMore.className = 'col-span-full text-center py-4';
                        loadMore.innerHTML = '<button onclick="loadProducts(' + d.next_page + ')" class="btn-outline-small"><i class="fas fa-chevron-down"></i> Muat Lainnya</button>';
                        grid.appendChild(loadMore);
                        currentSearchPage = d.next_page;
                    } else {
                        currentSearchPage = 0;
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleCheckout();
            toggleBank();
            // Auto-open history if there are transactions
            @if($todayOrders->count() > 0)
                toggleHistory();
            @endif
        });

        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const payment = document.getElementById('pos-payment').value;
            if (!payment) { e.preventDefault(); notify('Pilih metode pembayaran!', 'error'); return; }
            if (payment === 'cash') {
                const paid = parseInt(document.getElementById('amount-paid').value.replace(/\D/g, '')) || 0;
                const totalText = document.getElementById('grand-total-value').textContent.replace(/[^0-9]/g, '');
                const total = parseInt(totalText) || 0;
                if (paid < total) {
                    e.preventDefault();
                    notify('Jumlah dibayar kurang dari total belanja!', 'error');
                    return;
                }
            }
            const btn = document.getElementById('submit-checkout');
            btn.disabled = true;
            btn.innerHTML = '<span class="loading-spinner"></span> Memproses...';
        });
    </script>
</body>
</html>