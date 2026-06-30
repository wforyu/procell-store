<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->slider()->get();
        $popupBanner = Banner::active()->popup()->latest()->first();
        $categories = Category::active()->parents()->with('children')->get();
        $products = Product::active()->with('primaryImage')->latest()->take(12)->get();

        return view('store.home', compact('banners', 'popupBanner', 'categories', 'products'));
    }
}
