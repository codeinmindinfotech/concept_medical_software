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

        // Get all users and patients for chat list
        $users = \App\Models\User::companyOnly()->where('id', '!=', $authUser->id)->get();
        $patients = \App\Models\Patient::companyOnly()->get();

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
             'conversation_id' => 'required',
             'message' => 'required'
         ]);
 
         $user = auth()->user();
         $sender = $this->getAuthEntity(); // returns ['id' => ..., 'type' => ..., 'company_id' => ...]
         $senderType   = $sender['type'] === 'user' ? \App\Models\User::class : \App\Models\Patient::class;
 
         $message = Chatmessages::create([
             'conversation_id' => $request->conversation_id,
             'sender_id' => $user->id,
             'sender_type' => $senderType,
             'message' => $request->message
         ]);
 
         // Broadcast to all participants of the conversation
         $conversation = Conversation::with('participants')->find($request->conversation_id);
         foreach ($conversation->participants as $participant) {
             broadcast(new ChatMessageSent($message))
                 ->toOthers(); // you can scope by private channels per participant
         }
 
         return response()->json($message);
     }
    // // Get all messages for a conversation
    // public function messages($id)
    // {
    //     return Chatmessages::where('conversation_id', $id)
    //         ->orderBy('created_at')
    //         ->get();
    // }

    // // Create a new conversation
    // public function create(Request $request)
    // {
    //     $user = auth()->user();

    //     $sender = $this->getAuthEntity(); // returns ['id' => ..., 'type' => ..., 'company_id' => ...]
    //     $senderType   = $sender['type'] === 'user' ? \App\Models\User::class : \App\Models\Patient::class;

    //     $conversation = Conversation::create([
    //         'title' => $request->title ?? null,
    //         'created_by_id' => $user->id,
    //         'created_by_type' => $senderType,
    //         'company_id' => $user->role === 'superadmin' ? null : $user->company_id
    //     ]);

    //     // Attach the creator as a participant
    //     ConversationParticipant::create([
    //         'conversation_id' => $conversation->id,
    //         'participant_id' => $user->id,
    //         'participant_type' => $senderType,
    //         'company_id' => $conversation->company_id
    //     ]);

    //     // Optionally attach other participants if sent in the request
    //     if ($request->filled('participants')) {
    //         foreach ($request->participants as $p) {
    //             ConversationParticipant::create([
    //                 'conversation_id' => $conversation->id,
    //                 'participant_id' => $p['id'],
    //                 'participant_type' => $p['type'], // 'user' or 'patient'
    //                 'company_id' => $user->role === 'superadmin' ? null : $user->company_id
    //             ]);
    //         }
    //     }

    //     return response()->json(['id' => $conversation->id]);
    // }

   

    public function usersByRole(Request $request)
    {
        $role = $request->query('role');
        $companyId = auth()->user()->company_id;

        $query = User::role($role);
        if (!auth()->user()->hasRole('superadmin')) {
            $query->where('company_id', $companyId);
        }
        return $query->get(['id','name']);
    }

    public function patients()
    {
        $companyId = auth()->user()->company_id;

        $query = Patient::query();
        if (!auth()->user()->hasRole('superadmin')) {
            $query->where('company_id', $companyId);
        }

        return $query->get(['id','first_name']);
    }


}
