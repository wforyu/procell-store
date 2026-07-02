@php
    $messages = $getRecord()->messages()->with('user')->orderBy('created_at')->get();
@endphp
<div class="space-y-3 max-h-80 overflow-y-auto p-3 bg-gray-50 rounded-lg border">
    @forelse($messages as $msg)
        <div class="flex {{ $msg->is_admin ? 'justify-start' : 'justify-end' }}">
            <div class="max-w-[80%] rounded-xl px-4 py-2.5 {{ $msg->is_admin ? 'bg-white border border-gray-200' : 'bg-amber-500 text-white' }}">
                @if($msg->is_admin)
                    <p class="text-xs font-medium text-amber-600 mb-0.5">{{ $msg->user->name }} (Admin)</p>
                @else
                    <p class="text-xs font-medium text-amber-100 mb-0.5">{{ $msg->user->name }}</p>
                @endif
                <p class="text-sm whitespace-pre-wrap">{{ $msg->message }}</p>
                <p class="text-xs mt-1 {{ $msg->is_admin ? 'text-gray-400' : 'text-amber-100' }}">{{ $msg->created_at->format('d M H:i') }}</p>
            </div>
        </div>
    @empty
        <p class="text-center text-gray-400 text-sm py-8">Belum ada pesan</p>
    @endforelse
</div>
