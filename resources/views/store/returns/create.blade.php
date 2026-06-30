@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-6 md:py-8">

    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-amber-600 transition-colors"><i class="fas fa-home"></i></a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <a href="{{ route('orders.index') }}" class="hover:text-amber-600 transition-colors">Pesanan Saya</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <a href="{{ route('orders.show', $order) }}" class="hover:text-amber-600 transition-colors">#{{ $order->order_number }}</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">Ajukan Retur</span>
    </nav>

    <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
        <i class="fas fa-undo-alt text-amber-500"></i> Ajukan Retur
    </h1>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-100">
            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                <i class="fas fa-receipt text-gray-400"></i>
            </div>
            <div>
                <p class="font-bold text-gray-900">#{{ $order->order_number }}</p>
                <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y') }} — {{ $order->items->count() }} barang</p>
            </div>
        </div>

        <form action="{{ route('returns.store', $order) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                {{-- Reason --}}
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Retur <span class="text-red-500">*</span></label>
                    <select name="reason" id="reason" required class="input-field">
                        <option value="">Pilih alasan retur</option>
                        <option value="defective" {{ old('reason') == 'defective' ? 'selected' : '' }}>Produk Cacat / Rusak</option>
                        <option value="wrong_item" {{ old('reason') == 'wrong_item' ? 'selected' : '' }}>Produk Tidak Sesuai</option>
                        <option value="not_as_described" {{ old('reason') == 'not_as_described' ? 'selected' : '' }}>Tidak Sesuai Deskripsi</option>
                        <option value="damaged" {{ old('reason') == 'damaged' ? 'selected' : '' }}>Barang Rusak Saat Pengiriman</option>
                        <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('reason') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="4" class="input-field" required placeholder="Jelaskan masalah yang Anda alami...">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Photos --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Bukti <span class="text-red-500">*</span></label>
                    <p class="text-xs text-gray-500 mb-3">Unggah minimal 1 foto produk yang bermasalah (maks 5 foto)</p>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-amber-300 transition-colors"
                         x-data="{ previews: [] }"
                         x-init="$el.querySelector('input[type=file]').addEventListener('change', function(e) {
                             previews = [];
                             Array.from(e.target.files).forEach(file => {
                                 const reader = new FileReader();
                                 reader.onload = e => previews.push(e.target.result);
                                 reader.readAsDataURL(file);
                             });
                         })">
                        <input type="file" name="images[]" accept="image/*" multiple required
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-colors">
                        @error('images') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('images.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        {{-- Preview --}}
                        <div class="flex flex-wrap gap-3 mt-4" x-show="previews.length > 0" x-cloak>
                            <template x-for="(preview, idx) in previews" :key="idx">
                                <div class="w-20 h-20 rounded-lg overflow-hidden border border-gray-200">
                                    <img :src="preview" class="w-full h-full object-cover">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> Kirim Pengajuan Retur
                    </button>
                    <a href="{{ route('orders.show', $order) }}" class="btn-outline">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush
