@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid"> 
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
                ['label' => 'Internal Chat System'],
            ];
        @endphp

        @include('layout.partials.breadcrumb', [
            'pageTitle' => 'Internal Chat System',
            'breadcrumbs' => $breadcrumbs,
            'isListPage' => false
        ])

        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following errors:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        
        <div class="row">
            <!-- LEFT PANEL: Users/Patients -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h5>All Chats</h5></div>
                    <div class="card-body p-0" style="max-height: 500px; overflow-y:auto;">
                        <ul class="list-group list-group-flush" id="chat-user-list">

                            @foreach($users as $u)
                                <li class="list-group-item user-item" data-id="{{ $u->id }}" data-type="user">
                                    <div class="align-items-center gap-2 d-flex">
                                        <div class="rounded-circle bg-secondary d-inline-block text-white text-center" style="width: 40px; height: 40px; line-height: 40px;">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div class="patient-info">
                                            <h6> {{$u->name}}</h6>
                                            <p>({{ $u->getRoleNames()->first() }})</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                            @foreach($patients as $p)
                                <li class="list-group-item user-item" data-id="{{ $p->id }}" data-type="patient">
                                    <div class="align-items-center gap-2 d-flex">
                                        @if ($p->patient_picture)
                                        <img src="{{ asset('storage/' . $p->patient_picture) }}" alt="Patient Picture" class="rounded-circle" width="30" height="30">
                                        @else
                                        <div class="rounded-circle bg-secondary d-inline-block text-white text-center" style="width: 40px; height: 40px; line-height: 40px;">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        @endif

                                        <div class="patient-info">
                                            <h6>{{ $p->full_name }}</h6>
                                            <p>(Patient)</p>
                                        </div>
                                    </div>
                                                 
                                    {{-- <small class="text-muted d-block">Patient</small> --}}
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL: Conversation -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h5 id="chat-title">Select a user to start chat</h5></div>
                    <div class="card-body">
                        <div id="chat-messages" style="height: 400px; overflow-y:auto; border:1px solid #ddd; padding:10px;"></div>
                        <div class="chat-footer mt-2 d-flex">
                            <input type="text" id="message" class="form-control" placeholder="Type your message...">
                            <button id="sendMessageBtn" class="btn btn-primary ms-2">
                                <i class="fa-solid fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.12.0/echo.iife.js"></script>

<script>
    window.Pusher = Pusher;
    Echo.config({
        broadcaster: 'pusher',
        key: "{{ config('broadcasting.connections.pusher.key') }}",
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        forceTLS: true
    });
</script>
   
<script>
let currentConversation = null;

    // Click user/patient → get conversation
    $('#chat-user-list').on('click', '.user-item', function () {
    
        let participantId = $(this).data('id');
        let participantType = $(this).data('type');
        let name = $(this).text().trim();
    
        $('#chat-title').text(name);
        $('#chat-messages').html('<p class="text-muted">Loading...</p>');
    
        axios.post('{{guard_route("chat.getconversation")}}', {
            participant_id: participantId,
            participant_type: participantType
        }).then(res => {
    
            currentConversation = res.data.conversation_id;
            $('#chat-messages').html('');
    
            res.data.messages.forEach(m => appendMessage(m));
            listen(currentConversation);
        });
    });
    
    // Send message
    $('#sendMessageBtn').on('click', function () {
        let message = $('#message').val().trim();
        if (!message || !currentConversation) return;
    
        axios.post('{{guard_route("chat.send")}}', {
            conversation_id: currentConversation,
            message: message
        }).then(res => {
            appendMessage(res.data);
            $('#message').val('');
        });
    });
    
    // Append message
function appendMessage(m) {
    let isMine = m.sender_id == {{ auth()->id() }};
    let senderName = isMine ? 'You' : (m.sender?.name ?? 'User');

    // Determine message timestamp
    let timestamp = m.created_at ? new Date(m.created_at) : new Date();
    let now = new Date();
    let timeLabel = '';

    if (
        timestamp.getFullYear() === now.getFullYear() &&
        timestamp.getMonth() === now.getMonth() &&
        timestamp.getDate() === now.getDate()
    ) {
        timeLabel = `Today ${timestamp.getHours().toString().padStart(2,'0')}:${timestamp.getMinutes().toString().padStart(2,'0')}`;
    } else {
        timeLabel = `${timestamp.getFullYear()}-${(timestamp.getMonth()+1).toString().padStart(2,'0')}-${timestamp.getDate().toString().padStart(2,'0')} ${timestamp.getHours().toString().padStart(2,'0')}:${timestamp.getMinutes().toString().padStart(2,'0')}`;
    }

    // Message bubble HTML
    let messageHTML = `
        <div class="d-flex ${isMine ? 'justify-content-end' : 'justify-content-start'} mb-2">
            <div class="${isMine ? 'bg-success-light text-white' : 'bg-primary-light text-dark'} p-2 rounded shadow-sm" style="max-width: 70%;">
                <div class="small text-muted mb-1">${senderName} • ${timeLabel}</div>
                <div>${m.message}</div>
            </div>
        </div>
    `;

    $('#chat-messages').append(messageHTML);
    $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
}
    
    // Listen for new messages
    let chatChannel = null;
    function listen(id) {
        if (chatChannel) Echo.leave(chatChannel);
    
        chatChannel = `conversation.${id}`;
        Echo.private(chatChannel)
            .listen('.message.sent', e => appendMessage(e.message));
            
    }
</script>
@endpush
