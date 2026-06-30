<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = auth()->user()->wishlist()->with('product.primaryImage', 'product.category')->paginate(12);

        return view('store.wishlist.index', compact('wishlist'));
    }

    public function toggle(Product $product)
    {
        $existing = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
            ]);
            $status = 'added';
        }

        $count = Wishlist::where('user_id', auth()->id())->count();

        if (request()->wantsJson()) {
            return response()->json([
                'status' => $status,
                'count' => $count,
            ]);
        }

        return back()->with('success', $status === 'added' ? 'Produk ditambahkan ke wishlist!' : 'Produk dihapus dari wishlist!');
    }
}
