<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::active()->where('stock', '>', 0)
            ->with('category', 'primaryImage')
            ->orderBy('name')
            ->paginate(20);

        $categories = Category::active()->orderBy('name')->get();

        $posCart = session('pos_cart', []);

        $customers = Customer::orderBy('name')->get(['id', 'name', 'email', 'phone']);

        $bankAccounts = BankAccount::active()->get();

        $todayOrders = Order::where('order_number', 'like', 'POS-%')
            ->whereDate('created_at', today())
            ->with('customer:id,name')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn ($o) => [
                'id' => $o->id,
                'order_number' => $o->order_number,
                'customer_name' => $o->customer?->name ?? 'Walk-in',
                'grand_total' => $o->grand_total,
                'payment_method' => $o->payment_method,
                'created_at' => $o->created_at->format('H:i'),
            ]);

        return view('admin.pos.index', compact('products', 'categories', 'posCart', 'customers', 'bankAccounts', 'todayOrders') + [
            'cartTotal' => $this->cartTotal($posCart),
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $categoryId = $request->get('category_id');

        $products = Product::active()->where('stock', '>', 0)
            ->with('category', 'primaryImage')
            ->when($query, fn ($q) => $q->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%")
                    ->orWhere('brand', 'like', "%{$query}%");
            }))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->orderBy('name')
            ->paginate(20);

        $html = view('admin.pos._products', compact('products'))->render();

        return response()->json([
            'html' => $html,
            'has_more' => $products->hasMorePages(),
            'next_page' => $products->currentPage() + 1,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => "Stok {$product->name} tersisa {$product->stock}",
            ]);
        }

        $posCart = session('pos_cart', []);

        $found = false;
        foreach ($posCart as &$item) {
            if ($item['product_id'] === $request->product_id) {
                $newQty = $item['quantity'] + $request->quantity;
                if ($newQty > $product->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$product->name} tersisa {$product->stock}",
                    ]);
                }
                $item['quantity'] = $newQty;
                $found = true;
                break;
            }
        }
        unset($item);

        if (! $found) {
            $posCart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'brand' => $product->brand,
                'price' => (float) $product->selling_price,
                'quantity' => $request->quantity,
                'stock' => $product->stock,
                'image' => $product->image_url,
            ];
        }

        session(['pos_cart' => $posCart]);

        return response()->json([
            'success' => true,
            'cart_html' => view('admin.pos._cart', ['posCart' => $posCart])->render(),
            'count' => count($posCart),
            'total' => $this->cartTotal($posCart),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $posCart = session('pos_cart', []);

        foreach ($posCart as &$item) {
            if ($item['product_id'] === $request->product_id) {
                if ($request->quantity > $product->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$product->name} tersisa {$product->stock}",
                    ]);
                }
                $item['quantity'] = $request->quantity;
                break;
            }
        }
        unset($item);

        session(['pos_cart' => $posCart]);

        return response()->json([
            'success' => true,
            'cart_html' => view('admin.pos._cart', ['posCart' => $posCart])->render(),
            'count' => count($posCart),
            'total' => $this->cartTotal($posCart),
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $posCart = session('pos_cart', []);
        $posCart = array_values(array_filter($posCart, fn ($item) => $item['product_id'] !== $request->product_id));

        session(['pos_cart' => $posCart]);

        return response()->json([
            'success' => true,
            'cart_html' => view('admin.pos._cart', ['posCart' => $posCart])->render(),
            'count' => count($posCart),
            'total' => $this->cartTotal($posCart),
        ]);
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'payment_method' => 'required|string|in:cash,bank_transfer',
            'bank_account_id' => 'exclude_if:payment_method,cash|required|integer|exists:bank_accounts,id',
            'notes' => 'nullable|string',
            'amount_paid' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|string|in:percentage,nominal',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        $posCart = session('pos_cart', []);

        if (empty($posCart)) {
            return back()->with('error', 'Keranjang POS kosong!');
        }

        $totalAmount = $this->cartTotal($posCart);
        $discountAmount = (float) ($request->discount_amount ?? 0);
        $userId = auth()->id();

        $customerId = $request->customer_id;
        if (! $customerId) {
            $walkIn = Customer::firstOrCreate(
                ['name' => 'Walk-in Customer'],
                ['user_id' => null, 'email' => null, 'phone' => null, 'address' => 'Pembelian di toko']
            );
            $customerId = $walkIn->id;
        }

        $notes = $request->notes ?? '';
        $notesParts = ['[POS]'];
        if ($notes) {
            $notesParts[] = $notes;
        }
        if ($discountAmount > 0) {
            $discountLabel = $request->discount_type === 'percentage'
                ? (float) $request->discount_value.'%'
                : 'Rp '.number_format((int) $request->discount_value, 0, ',', '.');
            $notesParts[] = 'Diskon: '.$discountLabel.' (Rp '.number_format((int) $discountAmount, 0, ',', '.').')';
        }
        if ($request->payment_method === 'cash' && $request->amount_paid) {
            $notesParts[] = 'Bayar: Rp '.number_format((int) $request->amount_paid, 0, ',', '.');
        }
        $notesText = implode(' | ', $notesParts);

        $order = Order::create([
            'order_number' => 'POS-'.date('Ymd').'-'.strtoupper(Str::random(6)),
            'user_id' => $userId,
            'customer_id' => $customerId,
            'status' => 'completed',
            'total_amount' => $totalAmount,
            'discount_amount' => $discountAmount,
            'shipping_address' => 'Pembelian di toko',
            'notes' => $notesText ?: 'Pembelian di toko',
            'payment_method' => $request->payment_method,
            'shipping_cost' => 0,
        ]);

        foreach ($posCart as $item) {
            $subtotal = $item['price'] * $item['quantity'];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $subtotal,
            ]);

            $product = Product::find($item['product_id']);
            if ($product) {
                $stockBefore = $product->stock;
                $product->decrement('stock', $item['quantity']);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $userId,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $product->fresh()->stock,
                    'reference_type' => 'pos_order',
                    'reference_id' => $order->id,
                    'note' => 'POS #'.$order->order_number,
                ]);
            }
        }

        session()->forget('pos_cart');

        return redirect()->route('admin.pos.receipt', $order);
    }

    public function receipt(Order $order)
    {
        $order->load('items.product', 'customer', 'user');

        return view('admin.pos.receipt', compact('order'));
    }

    public function clearCart()
    {
        session()->forget('pos_cart');

        return response()->json([
            'success' => true,
            'cart_html' => view('admin.pos._cart', ['posCart' => []])->render(),
            'count' => 0,
            'total' => 0,
        ]);
    }

    public function customerAdd(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer = Customer::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'user_id' => null,
        ]);

        return response()->json([
            'success' => true,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
            ],
        ]);
    }

    public function history()
    {
        $todayOrders = Order::where('order_number', 'like', 'POS-%')
            ->whereDate('created_at', today())
            ->with('customer:id,name')
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn ($o) => [
                'id' => $o->id,
                'order_number' => $o->order_number,
                'customer_name' => $o->customer?->name ?? 'Walk-in',
                'grand_total' => $o->grand_total,
                'payment_method' => $o->payment_method,
                'created_at' => $o->created_at->format('H:i'),
            ]);

        $html = view('admin.pos._history', ['orders' => $todayOrders])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $todayOrders->count(),
        ]);
    }

    public function skuAdd(Request $request)
    {
        $request->validate(['sku' => 'required|string']);

        $product = Product::active()->where('sku', $request->sku)->first();

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => "Produk dengan SKU '{$request->sku}' tidak ditemukan",
            ]);
        }

        if ($product->stock < 1) {
            return response()->json([
                'success' => false,
                'message' => "Stok {$product->name} habis",
            ]);
        }

        // Re-use add logic
        $request->merge(['product_id' => $product->id, 'quantity' => 1]);

        return $this->add($request);
    }

    protected function cartTotal(array $posCart): float
    {
        return array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $posCart));
    }
}
