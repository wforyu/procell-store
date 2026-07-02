<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = ChatConversation::forUser(auth()->id())
            ->with('latestMessage')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('store.chat.index', compact('conversations'));
    }

    public function show(ChatConversation $conversation)
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $messages = $conversation->messages()->with('user')->orderBy('created_at')->get();

        return view('store.chat.show', compact('conversation', 'messages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $conversation = ChatConversation::create([
            'user_id' => auth()->id(),
            'subject' => $data['subject'] ?? 'Pertanyaan',
            'status' => 'open',
        ]);

        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'message' => $data['message'],
            'is_admin' => false,
        ]);

        $this->notifyAdmins($conversation);

        return redirect()->route('chat.show', $conversation)
            ->with('success', 'Pesan terkirim. Admin akan merespons segera.');
    }

    public function send(Request $request, ChatConversation $conversation)
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        if ($conversation->status === 'closed') {
            return back()->with('error', 'Percakapan ini sudah ditutup.');
        }

        $data = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'message' => $data['message'],
            'is_admin' => false,
        ]);

        $conversation->touch();
        $this->notifyAdmins($conversation);

        return back()->with('success', 'Pesan terkirim.');
    }

    public function poll(ChatConversation $conversation, Request $request)
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $since = $request->get('since');
        $query = $conversation->messages()->with('user')->orderBy('created_at');

        if ($since) {
            $query->where('created_at', '>', $since);
        }

        $messages = $query->get();

        return response()->json([
            'messages' => $messages->map(fn ($m) => [
                'id' => $m->id,
                'message' => $m->message,
                'is_admin' => $m->is_admin,
                'sender_name' => $m->user->name,
                'created_at' => $m->created_at->toISOString(),
                'time' => $m->created_at->format('H:i'),
            ]),
            'status' => $conversation->status,
        ]);
    }

    protected function notifyAdmins(ChatConversation $conversation)
    {
        try {
            $admins = User::where('is_admin', true)->get();
            $customerName = auth()->user()->name;

            foreach ($admins as $admin) {
                Notification::make()
                    ->title('Pesan Baru dari '.$customerName)
                    ->body('#'.$conversation->id.' — '.($conversation->subject ?? 'Pertanyaan'))
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->actions([
                        Action::make('view')
                            ->url('/admin/chat-conversations/'.$conversation->id.'/edit')
                            ->label('Lihat'),
                    ])
                    ->sendToDatabase($admin);
            }
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim notifikasi admin: '.$e->getMessage());
        }
    }
}
