@extends('layouts.app')

@section('title', 'Percakapan Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8" x-data="{ showForm: false }">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Percakapan Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Riwayat chat dengan admin toko</p>
        </div>
        <button @click="showForm = !showForm" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-colors font-medium text-sm">
            <i class="fas fa-plus"></i> Chat Baru
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- New Chat Form --}}
    <div x-show="showForm" x-cloak x-transition class="mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Mulai Percakapan Baru</h2>
            <form action="{{ route('chat.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                    <input type="text" name="subject" placeholder="Misal: Informasi stok, Garansi, dll"
                           class="w-full rounded-lg border-gray-200 focus:border-amber-500 focus:ring-amber-500 text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                    <textarea name="message" rows="4" required
                              placeholder="Tulis pesan Anda di sini..."
                              class="w-full rounded-lg border-gray-200 focus:border-amber-500 focus:ring-amber-500 text-sm"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-colors font-medium text-sm">
                        <i class="fas fa-paper-plane"></i> Kirim Pesan
                    </button>
                    <button type="button" @click="showForm = false" class="px-5 py-2.5 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-colors font-medium text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($conversations->count() > 0)
        <div class="space-y-3">
            @foreach($conversations as $conv)
                <a href="{{ route('chat.show', $conv) }}" class="block bg-white rounded-xl border border-gray-200 p-4 hover:border-amber-300 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-comment-dots text-amber-600"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $conv->subject }}</p>
                                @if($conv->latestMessage)
                                    <p class="text-sm text-gray-500 truncate">{{ $conv->latestMessage->message }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            @if($conv->status === 'open')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    Selesai
                                </span>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ $conv->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-comments text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Belum Ada Percakapan</h3>
            <p class="text-sm text-gray-500 mb-6">Mulai chat dengan admin untuk bertanya atau minta bantuan</p>
            <button @click="showForm = true" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-colors font-medium text-sm">
                <i class="fas fa-plus"></i> Chat Baru
            </button>
        </div>
    @endif
</div>
@endsection
