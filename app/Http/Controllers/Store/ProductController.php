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

        return view('store.products.show', compact('product', 'relatedProducts'));
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
