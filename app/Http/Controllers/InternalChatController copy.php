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
    
        $sender = $this->getAuthEntity(); // returns ['id' => ..., 'type' => ..., 'company_id' => ...]

        // ❌ Prevent self chat
        if (
            $sender['id'] == $request->participant_id &&
            $sender['type'] === $request->participant_type
        ) {
            return response()->json(['message' => 'Cannot chat with yourself'], 422);
        }
    
        $user = auth()->user();
        // 🔍 Find existing conversation between these two participants
        $senderType   = $sender['type'] === 'user' ? \App\Models\User::class : \App\Models\Patient::class;
        $receiverType = $request->participant_type === 'user' ? \App\Models\User::class : \App\Models\Patient::class;

        $conversation = Conversation::with(['messages.sender', 'participants'])
            ->whereHas('participants', function ($q) use ($sender, $senderType) {
                $q->where('participant_id', $sender['id'])
                ->where('participant_type', $senderType);
            })
            ->whereHas('participants', function ($q) use ($request, $receiverType) {
                $q->where('participant_id', $request->participant_id)
                ->where('participant_type', $receiverType);
            })
            ->first();
        // If not found, create conversation
        if(!$conversation){
            $conversation = Conversation::create([
                'created_by_id' => $sender['id'],
                'created_by_type' => $senderType,
            ]);

            ConversationParticipant::insert([
                [
                    'conversation_id' => $conversation->id,
                    'participant_id' => $sender['id'],
                    'participant_type' => $senderType
                ],
                [
                    'conversation_id' => $conversation->id,
                    'participant_id' => $request->participant_id,
                    'participant_type' => $receiverType
                ]
            ]);
            $conversation->load(['messages.sender','participants']);
        }

        return response()->json([
            'conversation_id' => $conversation->id,
            'participants' => $conversation->participants,
            'messages' => $conversation->messages()->with('sender')->orderBy('created_at')->get(),
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

        $sender = $this->getAuthEntity();

        $senderType = $sender['type'] === 'user'
            ? User::class
            : Patient::class;

        $message = Chatmessages::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => $sender['id'], // ✅ FIXED
            'sender_type' => $senderType,
            'message' => $request->message,
        ]);

        broadcast(new ChatMessageSent($message))->toOthers();

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
             'conversation_id' => 'required',
             'message' => 'required'
         ]);
 
         $user = auth()->user();
         $sender = $this->getAuthEntity(); // returns ['id' => ..., 'type' => ..., 'company_id' => ...]
         $senderType   = $sender['type'] === 'user' ? \App\Models\User::class : \App\Models\Patient::class;
 
        //  $message = Chatmessages::create([
        //      'conversation_id' => $request->conversation_id,
        //      'sender_id' => $user->id,
        //      'sender_type' => $senderType,
        //      'message' => $request->message
        //  ]);
        $message = Chatmessages::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => $sender['id'],
            'sender_type' => $senderType,
            'message' => $request->message,
        ]);
 
         // Broadcast to all participants of the conversation
         $conversation = Conversation::with('participants')->find($request->conversation_id);
         broadcast(new ChatMessageSent($message))->toOthers();

        //  foreach ($conversation->participants as $participant) {
        //      broadcast(new ChatMessageSent($message))
        //          ->toOthers(); // you can scope by private channels per participant
        //  }
 
         return response()->json($message);
     }
    
    public function unreadCount()
    {
        $auth = $this->getAuthEntity();
        
        $count = Chatmessages::whereHas('conversation.participants', function($q) use ($auth) {
            $participantType = $auth['type'] === 'user' ? \App\Models\User::class : \App\Models\Patient::class;
            $q->where('participant_id', $auth['id'])
            ->where('participant_type', $participantType);
        })->where('sender_id', '!=', $auth['id']) // exclude your own messages
        ->whereNull('read_at') // add a read_at column in chatmessages table
        ->count();

        return response()->json(['count' => $count]);
    }

}
