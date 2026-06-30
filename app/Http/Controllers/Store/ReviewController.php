<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'completed') {
            return back()->with('error', 'Ulasan hanya dapat diberikan untuk pesanan yang sudah selesai.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $productIds = $order->items->pluck('product_id')->toArray();

        foreach ($productIds as $productId) {
            $existing = ProductReview::where('product_id', $productId)
                ->where('user_id', auth()->id())
                ->where('order_id', $order->id)
                ->first();

            $imagePaths = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = $image->store('review-images', 'public');
                }
            }

            if ($existing) {
                $existing->update([
                    'rating' => $request->rating,
                    'review' => $request->review,
                    'images' => count($imagePaths) > 0 ? $imagePaths : $existing->images,
                ]);
            } else {
                ProductReview::create([
                    'product_id' => $productId,
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'rating' => $request->rating,
                    'review' => $request->review,
                    'images' => $imagePaths,
                ]);
            }
        }

        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $stats = ProductReview::where('product_id', $productId)
                    ->where('is_approved', true)
                    ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as review_count')
                    ->first();
                $product->update([
                    'avg_rating' => $stats->avg_rating ?? 0,
                    'review_count' => $stats->review_count ?? 0,
                ]);
            }
        }

        return back()->with('success', 'Ulasan berhasil dikirim!');
    }
}
