<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::active()->where('slug', $slug)->firstOrFail();

        return view('store.page', compact('page'));
    }
}
