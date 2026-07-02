<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between">
                <span>Filter Periode</span>
            </div>
        </x-slot>

        <form wire:submit="filter" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select wire:model="month" class="fi-input block w-full rounded-lg border-gray-300 shadow-sm text-sm">
                    @foreach($this->getMonths() as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select wire:model="year" class="fi-input block w-full rounded-lg border-gray-300 shadow-sm text-sm">
                    @for($y = now()->year - 2; $y <= now()->year; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <x-filament::button type="submit" color="primary">
                    <i class="fas fa-filter mr-1"></i> Tampilkan
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-filament::section class="!p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <x-filament::icon icon="heroicon-m-currency-dollar" class="w-5 h-5 text-green-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Pendapatan Bersih</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($stats['net_revenue'], 0, ',', '.') }}</p>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section class="!p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                    <x-filament::icon icon="heroicon-m-banknotes" class="w-5 h-5 text-red-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">HPP (COGS)</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($stats['total_cogs'], 0, ',', '.') }}</p>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section class="!p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                    <x-filament::icon icon="heroicon-m-arrow-trending-up" class="w-5 h-5 text-amber-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Laba Kotor</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($stats['gross_profit'], 0, ',', '.') }}</p>
                    <p class="text-xs {{ $stats['gross_margin'] > 30 ? 'text-green-600' : 'text-amber-600' }}">Margin {{ $stats['gross_margin'] }}%</p>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section class="!p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <x-filament::icon icon="heroicon-m-circle-stack" class="w-5 h-5 text-blue-600" />
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Laba Bersih</p>
                    <p class="text-lg font-bold {{ $stats['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($stats['net_profit'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs {{ $stats['net_margin'] > 10 ? 'text-green-600' : 'text-amber-600' }}">Margin {{ $stats['net_margin'] }}%</p>
                </div>
            </div>
        </x-filament::section>
    </div>

    {{-- Detail Laba Rugi --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-m-document-text" class="w-5 h-5 text-gray-500" />
                    <span>Rincian Laba Rugi</span>
                </div>
            </x-slot>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Total Pendapatan</span>
                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Biaya Pengiriman</span>
                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($stats['total_shipping'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Diskon</span>
                    <span class="text-sm font-semibold text-red-500">- Rp {{ number_format($stats['total_discount'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 font-medium">
                    <span class="text-sm text-gray-800">Pendapatan Bersih</span>
                    <span class="text-sm font-bold text-gray-900">Rp {{ number_format($stats['net_revenue'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">HPP (COGS)</span>
                    <span class="text-sm font-semibold text-red-600">- Rp {{ number_format($stats['total_cogs'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 font-medium">
                    <span class="text-sm text-gray-800">Laba Kotor</span>
                    <span class="text-sm font-bold text-amber-600">Rp {{ number_format($stats['gross_profit'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Biaya Operasional</span>
                    <span class="text-sm font-semibold text-red-600">- Rp {{ number_format($stats['total_expenses'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 font-medium">
                    <span class="text-sm text-gray-800">Laba Bersih</span>
                    <span class="text-sm font-bold {{ $stats['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($stats['net_profit'], 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500">Total Pesanan</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500">Rata-rata (AOV)</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($stats['aov'], 0, ',', '.') }}</p>
                </div>
            </div>
        </x-filament::section>

        {{-- Arus Kas --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-m-arrows-right-left" class="w-5 h-5 text-gray-500" />
                    <span>Arus Kas</span>
                </div>
            </x-slot>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span> Pemasukan
                    </span>
                    <span class="text-sm font-bold text-green-600">Rp {{ number_format($cashFlow['inflow'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span> Operasional
                    </span>
                    <span class="text-sm font-semibold text-red-600">- Rp {{ number_format($cashFlow['outflow_operational'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span> Pembelian Stok
                    </span>
                    <span class="text-sm font-semibold text-red-600">- Rp {{ number_format($cashFlow['outflow_purchases'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 font-medium">
                    <span class="text-sm text-gray-800">Total Pengeluaran</span>
                    <span class="text-sm font-bold text-red-600">Rp {{ number_format($cashFlow['total_outflow'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 font-medium">
                    <span class="text-sm text-gray-800">Arus Kas Bersih</span>
                    <span class="text-sm font-bold {{ $cashFlow['net_cash_flow'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($cashFlow['net_cash_flow'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </x-filament::section>
    </div>

    {{-- Pendapatan Harian --}}
    @if(count($dailyRevenue) > 0)
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-m-calendar-days" class="w-5 h-5 text-gray-500" />
                    <span>Pendapatan Harian</span>
                </div>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 px-3 font-medium text-gray-500">Tanggal</th>
                            <th class="text-right py-2 px-3 font-medium text-gray-500">Pesanan</th>
                            <th class="text-right py-2 px-3 font-medium text-gray-500">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyRevenue as $day)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-2 px-3 text-gray-900">{{ \Carbon\Carbon::parse($day['date'])->locale('id')->isoFormat('DD MMM') }}</td>
                                <td class="py-2 px-3 text-right text-gray-600">{{ $day['order_count'] }}</td>
                                <td class="py-2 px-3 text-right font-medium text-gray-900">Rp {{ number_format($day['revenue'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    @endif

    {{-- Breakdown Biaya --}}
    @if(count($expenseBreakdown) > 0)
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-m-chart-pie" class="w-5 h-5 text-gray-500" />
                    <span>Rincian Biaya Operasional</span>
                </div>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 px-3 font-medium text-gray-500">Kategori</th>
                            <th class="text-right py-2 px-3 font-medium text-gray-500">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenseBreakdown as $expense)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-2 px-3 text-gray-900">{{ $expense['category'] }}</td>
                                <td class="py-2 px-3 text-right font-medium text-gray-900">Rp {{ number_format($expense['total'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="font-medium bg-gray-50">
                            <td class="py-2 px-3 text-gray-800">Total</td>
                            <td class="py-2 px-3 text-right text-gray-900">Rp {{ number_format($stats['total_expenses'], 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
