<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Models\Chatmessages;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternalChatController extends Controller
{
    public function getOrCreateConversation(Request $request)
    {
        $request->validate([
            'participant_id'   => 'required|integer',
            'participant_type' => 'required|in:user,patient',
        ]);

        $sender = $this->getAuthEntity(); // ['id'=>..., 'type'=>..., 'company_id'=>...]

        // ❌ Prevent self-chat
        if ($sender['id'] == $request->participant_id && $sender['type'] === $request->participant_type) {
            return response()->json(['message' => 'Cannot chat with yourself'], 422);
        }

        $senderType   = $sender['type'] === 'user' ? \App\Models\User::class : \App\Models\Patient::class;
        $receiverType = $request->participant_type === 'user' ? \App\Models\User::class : \App\Models\Patient::class;

        // 🔍 Find existing conversation with exactly these 2 participants
        $conversation = Conversation::whereHas('participants', function ($q) use ($sender, $senderType) {
                $q->where('participant_id', $sender['id'])
                ->where('participant_type', $senderType);
            })
            ->whereHas('participants', function ($q) use ($request, $receiverType) {
                $q->where('participant_id', $request->participant_id)
                ->where('participant_type', $receiverType);
            })
            ->withCount('participants')
            ->having('participants_count', 2) // exactly 2 participants
            ->first();

        // If not found, create new conversation
        if (!$conversation) {
            $conversation = Conversation::create([
                'created_by_id'   => $sender['id'],
                'created_by_type' => $senderType,
            ]);

            ConversationParticipant::insert([
                [
                    'conversation_id' => $conversation->id,
                    'participant_id'  => $sender['id'],
                    'participant_type'=> $senderType
                ],
                [
                    'conversation_id' => $conversation->id,
                    'participant_id'  => $request->participant_id,
                    'participant_type'=> $receiverType
                ]
            ]);
        }

        $conversation->load(['participants', 'messages.sender']);

        return response()->json([
            'conversation_id' => $conversation->id,
            'participants'    => $conversation->participants,
            'messages'        => $conversation->messages()->with('sender')->orderBy('created_at')->get(),
        ]);
    }

    public function index()
    {
        $authUser = auth()->user();

        $users = \App\Models\User::companyOnly()->where('id', '!=', $authUser->id)->get();
        if(getCurrentGuard() === 'patient') {
            $patients = \App\Models\Patient::companyOnly()->where('id', '!=', $authUser->id)->get();
        }
        else {
            $patients = \App\Models\Patient::companyOnly()->get();
        }

        return view(guard_view('chat.chat', 'patient_admin.chat.chat'), compact('users', 'patients'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'message' => 'required|string',
        ]);

        $user = auth()->user();
        $sender = $this->getAuthEntity(); // returns ['id' => ..., 'type' => ..., 'company_id' => ...]
        $senderType   = $sender['type'] === 'user' ? \App\Models\User::class : \App\Models\Patient::class;

        $message = \App\Models\Chatmessages::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'message' => $request->message,
        ]);

        // Broadcast event (optional)
        broadcast(new \App\Events\ChatMessageSent($message))->toOthers();

        return response()->json($message->load('sender'));
    }

    private function getAuthEntity()
    {
        $guard = getCurrentGuard();

        if($guard === 'patient') {
            return [
                'id' => auth('patient')->id(),
                'type' => 'patient',
                'company_id' => auth()->user()->company_id
            ];
        } else {
            return [
                'id' => auth()->id(),
                'type' => 'user',
                'company_id' => auth()->user()->company_id
            ];
        }
    }
     // Send a message
    public function send(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'message' => 'required|string',
        ]);

        $sender = $this->getAuthEntity();
        $senderType = $sender['type'] === 'user'
            ? \App\Models\User::class
            : \App\Models\Patient::class;

        $conversation = Conversation::with('participants')
            ->findOrFail($request->conversation_id);

        $receiver = $conversation->participants
            ->first(fn($p) => $p->participant_id != $sender['id']);

        $message = Chatmessages::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => $sender['id'],
            'sender_type' => $senderType,
            'receiver_id' => $receiver->participant_id,
            'message' => $request->message,
        ]);

        broadcast(new ChatMessageSent($message))->toOthers();

        return response()->json($message->load('sender'));
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer',
        ]);

        $userId = auth()->id();

        \App\Models\Chatmessages::where('conversation_id', $request->conversation_id)
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['status' => 'ok']);
    }


}