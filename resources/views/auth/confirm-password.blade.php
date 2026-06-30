<x-guest-layout>
    <div class="text-center mb-8">
        <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Konfirmasi Kata Sandi</h2>
        <p class="text-sm text-gray-500 mt-1">Ini adalah area aman. Harap konfirmasi kata sandi Anda sebelum melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" required />
            <x-password-input id="password" class="block mt-1 w-full" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi Anda" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button>
            {{ __('Konfirmasi') }}
        </x-primary-button>
    </form>
</x-guest-layout>
