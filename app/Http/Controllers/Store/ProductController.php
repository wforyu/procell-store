<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('category', 'primaryImage');

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($brand = $request->input('brand')) {
            if (is_array($brand)) {
                $query->whereIn('brand', $brand);
            } else {
                $query->where('brand', $brand);
            }
        }

        if ($categorySlug = $request->category) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($minPrice = $request->min_price) {
            $query->where('selling_price', '>=', $minPrice);
        }

        if ($maxPrice = $request->max_price) {
            $query->where('selling_price', '<=', $maxPrice);
        }

        switch ($request->sort) {
            case 'cheapest':
                $query->orderBy('selling_price');
                break;
            case 'most_expensive':
                $query->orderByDesc('selling_price');
                break;
            case 'best_rating':
                $query->orderByDesc('avg_rating');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->parents()->with('children')->get();
        $brands = Product::active()->select('brand')->distinct()->orderBy('brand')->pluck('brand');

        return view('store.products.index', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::active()->where('slug', $slug)->with('category', 'images', 'approvedReviews.user')->firstOrFail();
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('primaryImage')
            ->take(4)
            ->get();

        $frequentlyBoughtTogether = Product::active()
            ->where('id', '!=', $product->id)
            ->whereIn('id', function ($q) use ($product) {
                $q->select('oi2.product_id')
                    ->from('order_items', 'oi1')
                    ->join('order_items as oi2', 'oi1.order_id', '=', 'oi2.order_id')
                    ->where('oi1.product_id', $product->id)
                    ->whereColumn('oi2.product_id', '!=', 'oi1.product_id');
            })
            ->with('primaryImage')
            ->take(4)
            ->get();

        return view('store.products.show', compact('product', 'relatedProducts', 'frequentlyBoughtTogether'));
    }

    public function byCategory($slug)
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();
        $products = Product::active()
            ->where('category_id', $category->id)
            ->with('category', 'primaryImage')
            ->paginate(12);
        $categories = Category::active()->parents()->with('children')->get();
        $brands = Product::active()->select('brand')->distinct()->orderBy('brand')->pluck('brand');

        return view('store.products.index', compact('products', 'categories', 'category', 'brands'));
    }

    public function searchSuggestions(Request $request)
    {
        $q = $request->input('q');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $products = Product::active()
            ->with('primaryImage')
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            })
            ->take(8)
            ->get()
            ->map(fn ($p) => [
                'slug' => $p->slug,
                'name' => $p->name,
                'price' => $p->selling_price,
                'price_formatted' => 'Rp '.number_format($p->selling_price, 0, ',', '.'),
                'image' => $p->imageUrl,
                'stock' => $p->stock,
                'brand' => $p->brand,
            ]);

        return response()->json($products);
    }

    public function quickBuy(Product $product)
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        $existing = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->increment('quantity');
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->selling_price,
            ]);
        }

        return redirect()->route('checkout.index');
    }
}
