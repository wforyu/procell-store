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

        return view('admin.pos.index', compact('products', 'categories', 'posCart', 'customers', 'bankAccounts') + [
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
            'bank_account_id' => 'required_if:payment_method,bank_transfer|exists:bank_accounts,id',
            'notes' => 'nullable|string',
        ]);

        $posCart = session('pos_cart', []);

        if (empty($posCart)) {
            return back()->with('error', 'Keranjang POS kosong!');
        }

        $totalAmount = $this->cartTotal($posCart);
        $userId = auth()->id();

        $customerId = $request->customer_id;
        if (! $customerId) {
            $walkIn = Customer::firstOrCreate(
                ['name' => 'Walk-in Customer'],
                ['user_id' => null, 'email' => null, 'phone' => null, 'address' => 'Pembelian di toko']
            );
            $customerId = $walkIn->id;
        }

        $order = Order::create([
            'order_number' => 'POS-'.date('Ymd').'-'.strtoupper(Str::random(6)),
            'user_id' => $userId,
            'customer_id' => $customerId,
            'status' => 'completed',
            'total_amount' => $totalAmount,
            'shipping_address' => 'Pembelian di toko',
            'notes' => $request->notes ? '[POS] '.$request->notes : 'Pembelian di toko',
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

    protected function cartTotal(array $posCart): float
    {
        return array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $posCart));
    }
}
