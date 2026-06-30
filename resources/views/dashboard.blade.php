<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if(auth()->user()->hasAnyRole(['KASIR', 'Super Admin', 'Stok', 'Keuangan']))
                <a href="{{ route('admin.pos.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow p-6 flex items-center gap-4">
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-cash-register text-2xl text-amber-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">POS (Point of Sale)</h3>
                        <p class="text-sm text-gray-500">Antarmuka kasir untuk transaksi offline</p>
                    </div>
                </a>
                @endif

                @if(auth()->user()->hasAnyRole(['Super Admin', 'Stok', 'Keuangan']))
                <a href="{{ url('/admin') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow p-6 flex items-center gap-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-th-large text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Admin Panel</h3>
                        <p class="text-sm text-gray-500">Manajemen toko, produk, pesanan</p>
                    </div>
                </a>
                @endif

                <a href="{{ route('products.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow p-6 flex items-center gap-4">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-store text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Toko</h3>
                        <p class="text-sm text-gray-500">Lihat toko dari sisi pelanggan</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
