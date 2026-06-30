<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $storeName = App\Models\Setting::getValue('store_name', 'ProCell Store');
        $storeDesc = App\Models\Setting::getValue('store_description', 'Toko Sparepart & Aksesoris HP Terlengkap');
        $metaDesc = App\Models\Setting::getValue('meta_description', 'Toko sparepart dan aksesoris HP terlengkap.');
        $metaKeywords = App\Models\Setting::getValue('meta_keywords', 'sparepart hp, aksesoris hp');
        $pageTitle = trim(View::yieldContent('title'));
        $fullTitle = $pageTitle ? "$pageTitle - $storeName" : "$storeName - $storeDesc";
        $storePhone = App\Models\Setting::getValue('store_phone', '0812-3456-7890');
        $storeHours = App\Models\Setting::getValue('store_hours', 'Sen-Sab 08:00 - 17:00');
        $storeIsClosed = App\Models\Setting::getValue('store_is_closed', 'false') === 'true';
        $storeClosedMessage = App\Models\Setting::getValue('store_closed_message', 'Toko sedang libur');
        $flashSaleText = App\Models\Setting::getValue('flash_sale_text', '');
        $orgSchema = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $storeName,
            'description' => $storeDesc,
            'url' => url('/'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+' . App\Models\Setting::getValue('whatsapp_number', '6281234567890'),
                'contactType' => 'customer service',
            ],
        ]);
    @endphp
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="@yield('meta_description', $metaDesc)">
    <meta name="keywords" content="@yield('meta_keywords', $metaKeywords)">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="@yield('meta_description', $metaDesc)">
    <meta property="og:image" content="@yield('og_image', asset('favicon.svg'))">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="robots" content="index, follow">
    @yield('breadcrumb_schema')
    @yield('product_schema')
    <script type="application/ld+json">{{ $orgSchema }}</script>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50" x-data="{ mobileMenu: false, searchOpen: false, cartCount: {{ $cartCount ?? 0 }} }">

    {{-- Overlay — Mobile Menu --}}
    <div x-show="mobileMenu" x-cloak x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 z-50" @click="mobileMenu = false"></div>

    {{-- Mobile Sidebar --}}
    <div x-show="mobileMenu" x-cloak x-transition:enter="transition-transform ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed left-0 top-0 bottom-0 w-80 bg-white z-50 overflow-y-auto shadow-2xl">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <span class="text-xl font-bold"><span class="text-amber-500">ProCell</span> Store</span>
            <button @click="mobileMenu = false" class="p-2 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-4 border-b border-gray-100">
            @auth
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div>
                        <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            @else
                <div class="flex gap-2">
                    <a href="{{ route('login') }}" class="btn-primary text-sm flex-1 px-4 py-2">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-outline text-sm flex-1 px-4 py-2">Daftar</a>
                </div>
            @endauth
        </div>
        <nav class="p-4 space-y-1">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-amber-50 hover:text-amber-600 font-medium transition-colors">
                <i class="fas fa-home w-5 text-center text-gray-400"></i> Beranda
            </a>
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-amber-50 hover:text-amber-600 font-medium transition-colors">
                <i class="fas fa-box w-5 text-center text-gray-400"></i> Semua Produk
            </a>
            @php($mobileCategories = App\Models\Category::active()->parents()->with('children')->get())
            @foreach($mobileCategories as $cat)
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg text-gray-700 hover:bg-amber-50 hover:text-amber-600 font-medium transition-colors">
                        <span class="flex items-center gap-3">
                            @switch($cat->slug)
                                @case('lcd-display') <i class="fas fa-mobile-screen w-5 text-center text-gray-400"></i> @break
                                @case('battery') <i class="fas fa-battery-three-quarters w-5 text-center text-gray-400"></i> @break
                                @case('flexible-cable') <i class="fas fa-plug w-5 text-center text-gray-400"></i> @break
                                @case('mainboard-ic') <i class="fas fa-microchip w-5 text-center text-gray-400"></i> @break
                                @case('button-switch') <i class="fas fa-toggle-on w-5 text-center text-gray-400"></i> @break
                                @case('charger-adapter') <i class="fas fa-charging-station w-5 text-center text-gray-400"></i> @break
                                @case('data-cable') <i class="fas fa-cable-car w-5 text-center text-gray-400"></i> @break
                                @default <i class="fas fa-folder w-5 text-center text-gray-400"></i>
                            @endswitch
                            {{ $cat->name }}
                        </span>
                        @if($cat->children->count() > 0)
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        @endif
                    </button>
                    @if($cat->children->count() > 0)
                        <div x-show="open" x-cloak x-transition class="ml-8 mt-1 space-y-1">
                            @foreach($cat->children as $child)
                                <a href="{{ route('products.category', $child->slug) }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-amber-600 rounded-lg hover:bg-amber-50">{{ $child->name }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
            <div class="border-t border-gray-100 my-3 pt-3">
                <a href="{{ route('cart.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-amber-50 hover:text-amber-600 font-medium transition-colors">
                    <i class="fas fa-shopping-cart w-5 text-center text-gray-400"></i> Keranjang
                </a>
                @auth
                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-amber-50 hover:text-amber-600 font-medium transition-colors">
                        <i class="fas fa-clipboard-list w-5 text-center text-gray-400"></i> Pesanan Saya
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-amber-50 hover:text-amber-600 font-medium transition-colors">
                        <i class="fas fa-heart w-5 text-center text-red-400"></i> Wishlist
                    </a>
                @endauth
            </div>
        </nav>
    </div>

    <div class="min-h-screen flex flex-col">

        {{-- TOP BAR --}}
        <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white text-xs sm:text-sm">
            <div class="max-w-7xl mx-auto px-4 h-9 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="hidden sm:flex items-center gap-1.5 text-gray-300">
                        <i class="fas fa-phone-alt text-amber-400 text-xs"></i>
                        {{ $storePhone }}
                    </span>
                    <span class="hidden md:flex items-center gap-1.5 text-gray-300">
                        <i class="{{ $storeIsClosed ? 'fas fa-store-slash' : 'far fa-clock' }} text-amber-400 text-xs"></i>
                        {{ $storeIsClosed ? $storeClosedMessage : $storeHours }}
                    </span>
                </div>
                <div class="flex items-center gap-1 sm:gap-4">
                    @if($flashSaleText)
                        <span class="hidden sm:inline-flex items-center gap-1.5 text-amber-400 font-medium">
                            <i class="fas fa-bolt"></i> {{ $flashSaleText }}
                        </span>
                    @endif
                    @auth
                        <a href="{{ route('orders.index') }}" class="hover:text-amber-400 transition-colors flex items-center gap-1"><i class="fas fa-box"></i> <span class="hidden sm:inline">Pesanan</span></a>
                        <span class="text-gray-600">|</span>
                        <a href="{{ route('wishlist.index') }}" class="hover:text-amber-400 transition-colors flex items-center gap-1"><i class="fas fa-heart"></i> <span class="hidden sm:inline">Wishlist</span></a>
                        <span class="text-gray-600">|</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-amber-400 transition-colors flex items-center gap-1"><i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Keluar</span></button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-amber-400 transition-colors flex items-center gap-1"><i class="fas fa-user"></i> <span class="hidden sm:inline">Masuk</span></a>
                        <span class="text-gray-600">|</span>
                        <a href="{{ route('register') }}" class="hover:text-amber-400 transition-colors flex items-center gap-1"><i class="fas fa-user-plus"></i> <span class="hidden sm:inline">Daftar</span></a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- MAIN HEADER --}}
        <header class="bg-white shadow-md sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex items-center h-16 md:h-20 gap-3 md:gap-6">

                    {{-- Mobile Menu Button --}}
                    <button @click="mobileMenu = true" class="md:hidden p-2 -ml-2 text-gray-600 hover:text-amber-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2">
                        <div class="w-9 h-9 md:w-10 md:h-10 rounded-lg gradient-primary flex items-center justify-center text-white font-bold text-sm md:text-base">PC</div>
                        <div class="hidden xs:block">
                            <span class="text-lg md:text-xl font-bold text-gray-900">ProCell</span>
                            <span class="text-lg md:text-xl font-bold text-amber-500">Store</span>
                        </div>
                    </a>

                    {{-- Search Bar --}}
                    <div class="flex-1 max-w-2xl mx-auto" x-data="{ query: '{{ request('q') }}' }">
                        <form action="{{ route('products.index') }}" method="GET" class="relative">
                            <div class="flex">
                                <div class="relative flex-1">
                                    <input
                                        type="text"
                                        name="q"
                                        x-model="query"
                                        placeholder="Cari LCD, baterai, charger..."
                                        class="w-full h-10 md:h-12 pl-4 pr-10 border-2 border-gray-200 rounded-l-xl focus:border-amber-500 focus:ring-0 text-sm outline-none transition-colors"
                                        value="{{ request('q') }}"
                                    >
                                    <button type="button" x-show="query.length > 0" @click="query = ''; $el.closest('form').querySelector('input[name=q]').value = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                                <button type="submit" class="h-10 md:h-12 px-5 md:px-8 gradient-primary text-white rounded-r-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 flex items-center gap-2 font-medium">
                                    <i class="fas fa-search"></i>
                                    <span class="hidden sm:inline text-sm">Cari</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Right Actions --}}
                    <div class="flex items-center gap-2 md:gap-4 flex-shrink-0">

                        {{-- WhatsApp --}}
                        @if($wa = App\Models\Setting::getValue('whatsapp_number'))
                            <a href="https://wa.me/{{ $wa }}" target="_blank" class="hidden lg:flex items-center gap-2 px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                                <i class="fab fa-whatsapp text-lg"></i>
                                <span class="hidden xl:inline">CS WhatsApp</span>
                            </a>
                        @endif

                        {{-- Cart --}}
                        <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-amber-500 transition-colors">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span x-text="cartCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold min-w-[18px] h-[18px] flex items-center justify-center rounded-full"></span>
                        </a>

                        {{-- User Dropdown (Desktop) --}}
                        @auth
                            <div class="hidden md:relative md:flex" x-data="{ userOpen: false }">
                                <button @click="userOpen = !userOpen" @click.outside="userOpen = false" class="flex items-center gap-2 p-2 text-gray-600 hover:text-amber-500 transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-semibold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</div>
                                    <span class="hidden lg:inline text-sm font-medium">{{ auth()->user()->name }}</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                <div x-show="userOpen" x-cloak @click.outside="userOpen = false" x-transition class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="font-medium text-gray-900 text-sm">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                        <i class="fas fa-clipboard-list w-4 text-center text-gray-400"></i> Pesanan Saya
                                    </a>
                                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                        <i class="fas fa-heart w-4 text-center text-red-400"></i> Wishlist
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                        <i class="fas fa-user-cog w-4 text-center text-gray-400"></i> Profil
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="hidden md:flex items-center gap-2 px-4 py-2 border-2 border-amber-500 text-amber-600 rounded-lg hover:bg-amber-50 transition-colors text-sm font-semibold">
                                <i class="fas fa-user"></i> Masuk
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- NAVIGATION --}}
            <nav class="hidden md:block border-t border-gray-100 bg-white">
                <div class="max-w-7xl mx-auto px-4 flex items-center h-11">
                    {{-- Mega Menu Kategori --}}
                    <div class="relative" x-data="{ catOpen: false }" @mouseenter="catOpen = true" @mouseleave="catOpen = false">
                        <button class="flex items-center gap-2 h-11 px-4 bg-amber-500 text-white font-semibold text-sm rounded-t hover:bg-amber-600 transition-colors">
                            <i class="fas fa-bars"></i>
                            <span>Kategori</span>
                            <i class="fas fa-chevron-down text-xs ml-1" :class="{'rotate-180': catOpen}"></i>
                        </button>
                        <div x-show="catOpen" x-cloak x-transition class="absolute left-0 top-full w-[700px] bg-white rounded-b-xl shadow-2xl border border-gray-100 grid grid-cols-3 p-6 gap-6 z-50">
                            @php($navCategories = App\Models\Category::active()->parents()->with('children')->get())
                            @foreach($navCategories as $cat)
                                <div>
                                    <a href="{{ route('products.category', $cat->slug) }}" class="flex items-center gap-2 font-semibold text-gray-900 hover:text-amber-600 mb-2 text-sm">
                                        @switch($cat->slug)
                                            @case('lcd-display') <i class="fas fa-mobile-screen text-amber-500"></i> @break
                                            @case('battery') <i class="fas fa-battery-three-quarters text-amber-500"></i> @break
                                            @case('flexible-cable') <i class="fas fa-plug text-amber-500"></i> @break
                                            @case('mainboard-ic') <i class="fas fa-microchip text-amber-500"></i> @break
                                            @case('button-switch') <i class="fas fa-toggle-on text-amber-500"></i> @break
                                            @case('charger-adapter') <i class="fas fa-charging-station text-amber-500"></i> @break
                                            @case('data-cable') <i class="fas fa-cable-car text-amber-500"></i> @break
                                            @default <i class="fas fa-folder text-amber-500"></i>
                                        @endswitch
                                        {{ $cat->name }}
                                    </a>
                                    @if($cat->children->count() > 0)
                                        <ul class="space-y-1">
                                            @foreach($cat->children as $child)
                                                <li>
                                                    <a href="{{ route('products.category', $child->slug) }}" class="text-xs text-gray-600 hover:text-amber-600 transition-colors">{{ $child->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Nav Links --}}
                    <div class="flex items-center gap-1 ml-4 overflow-x-auto scrollbar-hide">
                        <a href="{{ route('home') }}" class="nav-link px-3 h-11 flex items-center text-sm {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Beranda</a>
                        <a href="{{ route('products.index') }}" class="nav-link px-3 h-11 flex items-center text-sm {{ request()->routeIs('products.*') ? 'nav-link-active' : '' }}">Semua Produk</a>
                        @foreach($navCategories->take(4) as $cat)
                            <a href="{{ route('products.category', $cat->slug) }}" class="nav-link px-3 h-11 flex items-center text-sm whitespace-nowrap">{{ $cat->name }}</a>
                        @endforeach
                        <a href="{{ route('cart.index') }}" class="nav-link px-3 h-11 flex items-center text-sm">Keranjang</a>
                    </div>
                </div>
            </nav>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition>
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3 text-sm">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="flex-1">{{ session('success') }}</span>
                        <button @click="show = false" class="text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3 text-sm">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span class="flex-1">{{ session('error') }}</span>
                        <button @click="show = false" class="text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            @endif
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>

        {{-- FOOTER --}}
        <footer class="bg-gray-900 text-gray-300 mt-16">
            {{-- Main Footer --}}
            <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Company --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-9 h-9 rounded-lg gradient-primary flex items-center justify-center text-white font-bold text-sm">PC</div>
                        <span class="text-lg font-bold text-white">ProCell <span class="text-amber-500">Store</span></span>
                    </div>
                    <p class="text-sm leading-relaxed text-gray-400 mb-4">{{ App\Models\Setting::getValue('footer_description', 'Toko sparepart dan aksesoris HP terpercaya. Menyediakan berbagai macam suku cadang smartphone original dan berkualitas.') }}</p>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-amber-500 transition-colors text-gray-400 hover:text-white"><i class="fab fa-facebook-f text-sm"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-amber-500 transition-colors text-gray-400 hover:text-white"><i class="fab fa-instagram text-sm"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-amber-500 transition-colors text-gray-400 hover:text-white"><i class="fab fa-tiktok text-sm"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 flex items-center justify-center hover:bg-amber-500 transition-colors text-gray-400 hover:text-white"><i class="fab fa-youtube text-sm"></i></a>
                    </div>
                </div>

                {{-- Categories --}}
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Kategori</h3>
                    <ul class="space-y-2.5">
                        @foreach(App\Models\Category::active()->parents()->get() as $cat)
                            <li><a href="{{ route('products.category', $cat->slug) }}" class="text-sm text-gray-400 hover:text-amber-400 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-amber-500"></i>{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Customer Service --}}
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Layanan Pelanggan</h3>
                    <ul class="space-y-2.5">
                        @forelse($footerPages ?? [] as $fp)
                            <li><a href="{{ route('page.show', $fp->slug) }}" class="text-sm text-gray-400 hover:text-amber-400 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-amber-500"></i>{{ $fp->title }}</a></li>
                        @empty
                            <li><span class="text-sm text-gray-600">Belum ada halaman</span></li>
                        @endforelse
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Kontak Kami</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <i class="fab fa-whatsapp text-green-400 mt-0.5"></i>
                            <span>{{ App\Models\Setting::getValue('store_phone', '0812-3456-7890') }}</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <i class="far fa-envelope text-amber-400 mt-0.5"></i>
                            <span>{{ App\Models\Setting::getValue('store_email', 'info@procell.com') }}</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <i class="fas fa-map-marker-alt text-red-400 mt-0.5"></i>
                            <span>{{ App\Models\Setting::getValue('store_address', 'Jakarta, Indonesia') }}</span>
                        </li>
                    </ul>
                    @if($wa = App\Models\Setting::getValue('whatsapp_number'))
                        <a href="https://wa.me/{{ $wa }}" target="_blank" class="inline-flex items-center gap-2 mt-4 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            <i class="fab fa-whatsapp text-lg"></i> Chat Via WhatsApp
                        </a>
                    @endif
                </div>
            </div>

            {{-- Payment Methods --}}
            <div class="border-t border-gray-800">
                <div class="max-w-7xl mx-auto px-4 py-6">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-sm text-gray-500">Metode Pembayaran:</p>
                        <div class="flex items-center gap-4 text-2xl text-gray-500">
                            <i class="fab fa-cc-visa" title="Visa"></i>
                            <i class="fab fa-cc-mastercard" title="Mastercard"></i>
                            <span class="text-sm font-bold bg-gray-800 px-3 py-1 rounded">BCA</span>
                            <span class="text-sm font-bold bg-gray-800 px-3 py-1 rounded">Mandiri</span>
                            <span class="text-sm font-bold bg-gray-800 px-3 py-1 rounded">BNI</span>
                            <span class="text-sm font-bold bg-gray-800 px-3 py-1 rounded">BRI</span>
                            <i class="fas fa-mobile-alt text-xl" title="OVO / GoPay / Dana"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Copyright --}}
            <div class="border-t border-gray-800">
                <div class="max-w-7xl mx-auto px-4 py-4 text-center text-sm text-gray-500">
                    {{ App\Models\Setting::getValue('footer_text', '© 2026 ProCell Store. All rights reserved.') }}
                </div>
            </div>
        </footer>
    </div>

    {{-- Back to Top Button --}}
    <button x-data="{ show: false }" x-show="show" x-cloak @scroll.window="show = window.scrollY > 400" @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-transition class="fixed bottom-6 right-6 w-12 h-12 rounded-full gradient-primary text-white shadow-lg hover:shadow-xl transition-all flex items-center justify-center z-50">
        <i class="fas fa-arrow-up"></i>
    </button>

    @stack('scripts')
</body>
</html>
