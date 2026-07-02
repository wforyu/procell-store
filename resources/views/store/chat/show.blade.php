@extends('layouts.app')

@section('title', $conversation->subject ?? 'Chat Baru')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('chat.index') }}" class="text-sm text-gray-500 hover:text-amber-600 transition-colors">
            <i class="fas fa-arrow-left"></i> Kembali ke Percakapan
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden" x-data="chatComponent({{ $conversation->id }}, '{{ $conversation->status }}')">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-comment-dots text-amber-600"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900">{{ $conversation->subject ?? 'Percakapan Baru' }}</h2>
                    <p class="text-xs text-gray-500" x-text="statusLabel"></p>
                </div>
            </div>
            <span :class="statusClass" x-text="statusLabel" class="text-xs font-medium px-2.5 py-1 rounded-full"></span>
        </div>

        {{-- Messages --}}
        <div class="h-96 overflow-y-auto p-6 space-y-4 bg-gray-50/50" x-ref="messageContainer">
            @if(isset($messages) && $messages->count() > 0)
                @foreach($messages as $msg)
                    <div class="flex {{ $msg->is_admin ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-[75%] {{ $msg->is_admin ? 'bg-white border border-gray-200' : 'bg-amber-500 text-white' }} rounded-2xl px-4 py-3 {{ $msg->is_admin ? 'rounded-tl-sm' : 'rounded-br-sm' }}">
                            @if($msg->is_admin)
                                <p class="text-xs font-medium text-amber-600 mb-1">{{ $msg->user->name }} (Admin)</p>
                            @endif
                            <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                            <p class="text-xs mt-1.5 {{ $msg->is_admin ? 'text-gray-400' : 'text-amber-100' }}">{{ $msg->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-12" x-show="!messages.length">
                    <div class="w-12 h-12 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-comment text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 text-sm">Belum ada pesan. Mulai percakapan!</p>
                </div>
            @endif

            {{-- New messages from polling --}}
            <template x-for="msg in newMessages" :key="msg.id">
                <div :class="msg.is_admin ? 'flex justify-start' : 'flex justify-end'">
                    <div :class="msg.is_admin ? 'bg-white border border-gray-200 rounded-tl-sm' : 'bg-amber-500 text-white rounded-br-sm'" class="max-w-[75%] rounded-2xl px-4 py-3">
                        <template x-if="msg.is_admin">
                            <p class="text-xs font-medium text-amber-600 mb-1" x-text="msg.sender_name + ' (Admin)'"></p>
                        </template>
                        <p class="text-sm leading-relaxed whitespace-pre-wrap" x-text="msg.message"></p>
                        <p class="text-xs mt-1.5" :class="msg.is_admin ? 'text-gray-400' : 'text-amber-100'" x-text="msg.time"></p>
                    </div>
                </div>
            </template>
        </div>

        {{-- Input --}}
        <div class="border-t border-gray-100 p-4 bg-white" x-show="status === 'open'">
            <form @submit.prevent="sendMessage" class="flex gap-3">
                <textarea x-model="newMessage" @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                          placeholder="Ketik pesan..." rows="1"
                          class="flex-1 rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-500 text-sm resize-none"></textarea>
                <button type="submit" :disabled="!newMessage.trim()"
                        class="px-5 py-2.5 bg-amber-500 text-white rounded-xl hover:bg-amber-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium text-sm flex items-center gap-2">
                    <i class="fas fa-paper-plane"></i>
                    <span class="hidden sm:inline">Kirim</span>
                </button>
            </form>
        </div>
        <div x-show="status === 'closed'" class="border-t border-gray-100 p-4 bg-gray-50 text-center text-sm text-gray-500">
            Percakapan ini sudah ditutup oleh admin.
        </div>
    </div>
</div>

@push('scripts')
<script>
function chatComponent(conversationId, initialStatus) {
    return {
        conversationId: conversationId,
        status: initialStatus,
        messages: [],
        newMessages: [],
        newMessage: '',
        pollingTimer: null,
        lastPoll: '{{ now()->toISOString() }}',

        get statusLabel() {
            return this.status === 'open' ? 'Aktif' : 'Ditutup';
        },
        get statusClass() {
            return this.status === 'open'
                ? 'bg-green-100 text-green-700'
                : 'bg-gray-100 text-gray-500';
        },

        init() {
            this.lastPoll = new Date().toISOString();
            this.startPolling();
        },

        startPolling() {
            this.pollingTimer = setInterval(() => {
                if (this.conversationId) {
                    fetch('/chat/' + this.conversationId + '/poll?since=' + encodeURIComponent(this.lastPoll))
                        .then(r => r.json())
                        .then(data => {
                            if (data.messages && data.messages.length) {
                                data.messages.forEach(m => {
                                    this.newMessages.push(m);
                                });
                                this.lastPoll = new Date().toISOString();
                                this.$nextTick(() => {
                                    const container = this.$refs.messageContainer;
                                    if (container) container.scrollTop = container.scrollHeight;
                                });
                            }
                            if (data.status) {
                                this.status = data.status;
                            }
                        })
                        .catch(() => {});
                }
            }, 5000);
        },

        destroy() {
            if (this.pollingTimer) clearInterval(this.pollingTimer);
        },

        async sendMessage() {
            const text = this.newMessage.trim();
            if (!text || !this.conversationId) return;

            try {
                const formData = new FormData();
                formData.append('message', text);

                const res = await fetch('/chat/' + this.conversationId + '/send', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (res.ok) {
                    this.messages.push({
                        id: Date.now(),
                        message: text,
                        is_admin: false,
                        time: new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}),
                    });
                    this.newMessage = '';
                    this.lastPoll = new Date().toISOString();
                    this.$nextTick(() => {
                        const container = this.$refs.messageContainer;
                        if (container) container.scrollTop = container.scrollHeight;
                    });
                }
            } catch(e) {}
        },
    };
}
</script>
@endpush
@endsection
