<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900">Wishlist</h3>
                    <p class="mt-1 text-sm text-gray-600">Lihat produk yang sudah Anda simpan.</p>
                    <div class="mt-4">
                        <a href="{{ route('wishlist.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-heart text-red-400"></i> Buka Wishlist
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @php
                        $user = auth()->user();
                        $refCode = $user->referralCode;
                    @endphp
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="fas fa-coins text-amber-500"></i> Poin & Referral
                    </h3>

                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div class="bg-amber-50 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-amber-600">{{ number_format($user->points_balance, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">Poin Saya</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($refCode?->total_referrals ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">Referral Berhasil</p>
                        </div>
                    </div>

                    @if ($refCode)
                    <div class="mt-4">
                        <label class="text-sm font-medium text-gray-700">Kode Referral Saya</label>
                        <div class="flex gap-2 mt-1">
                            <input type="text" value="{{ $refCode->code }}" readonly
                                   class="flex-1 border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-mono font-bold text-amber-700 bg-amber-50"
                                   id="refCodeInput">
                            <button onclick="navigator.clipboard.writeText('{{ $refCode->code }}').then(() => { this.innerText = 'Tersalin!'; setTimeout(() => this.innerText = 'Salin', 2000); })"
                                    class="px-4 py-2.5 bg-amber-500 text-white rounded-lg text-sm font-medium hover:bg-amber-600 transition-colors">
                                Salin
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Bagikan kode ini ke teman untuk dapat bonus poin!</p>
                    </div>
                    @endif

                    @if ($user->loyaltyPoint && $user->loyaltyPoint->transactions->isNotEmpty())
                    <div class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Riwayat Poin</h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($user->loyaltyPoint->transactions->sortByDesc('created_at')->take(20) as $tx)
                            <div class="flex items-center justify-between text-sm py-1.5 border-b border-gray-50 last:border-0">
                                <div>
                                    <span class="{{ $tx->points > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                        {{ $tx->points > 0 ? '+' : '' }}{{ number_format($tx->points, 0, ',', '.') }}
                                    </span>
                                    <span class="text-gray-500 text-xs ml-2">{{ $tx->description }}</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ $tx->created_at->diffForHumans() }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
