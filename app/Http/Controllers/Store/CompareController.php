<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;

class CompareController extends Controller
{
    const SESSION_KEY = 'compare_ids';

    public function index()
    {
        $ids = session()->get(self::SESSION_KEY, []);
        $products = [];
        if (count($ids) > 0) {
            $products = Product::active()->whereIn('id', $ids)->with('category', 'primaryImage')->get()->keyBy('id');
            $products = collect($ids)->map(fn ($id) => $products->get($id))->filter();
        }

        return view('store.compare.index', compact('products'));
    }

    public function toggle(Product $product)
    {
        $ids = session()->get(self::SESSION_KEY, []);
        $maxCompare = 4;

        if (in_array($product->id, $ids)) {
            $ids = array_values(array_diff($ids, [$product->id]));
            $status = 'removed';
        } else {
            if (count($ids) >= $maxCompare) {
                if (request()->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Maksimal '.$maxCompare.' produk untuk perbandingan']);
                }

                return back()->with('error', 'Maksimal '.$maxCompare.' produk untuk perbandingan');
            }
            $ids[] = $product->id;
            $status = 'added';
        }

        session()->put(self::SESSION_KEY, $ids);

        if (request()->wantsJson()) {
            return response()->json([
                'status' => $status,
                'count' => count($ids),
                'html' => $status === 'added' ? '<i class="fas fa-check-circle"></i> Dibandingkan' : '<i class="fas fa-scale-balanced"></i> Bandingkan',
            ]);
        }

        return back();
    }

    public function remove(Product $product)
    {
        $ids = session()->get(self::SESSION_KEY, []);
        $ids = array_values(array_diff($ids, [$product->id]));
        session()->put(self::SESSION_KEY, $ids);

        return redirect()->route('compare.index');
    }

    public function count(): int
    {
        return count(session()->get(self::SESSION_KEY, []));
    }

    public function clear()
    {
        session()->forget(self::SESSION_KEY);

        return redirect()->route('compare.index');
    }
}
