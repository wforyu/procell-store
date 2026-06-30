<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg shadow-amber-200 hover:shadow-xl hover:shadow-amber-300 hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    <span class="mr-2">{{ $slot }}</span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
</button>
