@extends('layout.mainlayout')
@push('styles')
<style>
    .user-item {
        cursor: pointer;
    }

    .user-item.active {
        background-color: #e9f5ff;
        border-left: 4px solid #0d6efd;
    }

    .user-item.active h6 {
        font-weight: 600;
    }
</style>
@endpush
@section('content')

<div class="content">
    <div class="container pt-3">
        <div class="row">
            <div class="card mb-4 shadow-sm p-3">
                <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Internal Chat System
                    </h5>
                </div>
                    @session('success')
                        <div class="alert alert-success" role="alert">
                            {{ $value }}
                        </div>
                    @endsession
                <div class="row">
                    <!-- LEFT PANEL: Users/Patients -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header mb-1 p-2"><h5>All Chats</h5></div>
                            <div class="card-body p-0" style="max-height: 500px; overflow-y:auto;">
                                <ul class="list-group list-group-flush" id="chat-user-list">

                                    @foreach($users as $u)
                                        <li class="list-group-item user-item" data-id="{{ $u->id }}" data-type="user">
                                            <div class="patinet-information gap-2 d-flex">
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
                                            <div class="patinet-information gap-2 d-flex">
                                                @if ($p->patient_picture)
                                                    <img src="{{ asset('storage/patient_pictures/'.$p->id.'/small.jpg') }}" alt="Patient Picture" class="rounded-circle" width="30" height="30">
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
                            <div class="card-header mb-1 p-2"><h5 id="chat-title">Select a user to start chat</h5></div>
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
    </div>
</div>

@endsection
@push('scripts')
<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- Pusher -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<!-- Laravel Echo (UMD build) -->
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.min.js"></script>

<script>
    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: "{{ config('broadcasting.connections.pusher.key') }}",
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        forceTLS: true
    });

    console.log('Echo OK:', typeof window.Echo.private); // function
</script>
<script>

    let currentConversation = null;

    // Click user/patient → get conversation
    $('#chat-user-list').on('click', '.user-item', function () {
        // 🔹 Remove active from all
        $('.user-item').removeClass('active');

        // 🔹 Add active to clicked item
        $(this).addClass('active');

        let participantId = $(this).data('id');
        let participantType = $(this).data('type');
        let name = $(this).find('h6').text().trim();

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

    // $('#chat-user-list').on('click', '.user-item', function () {
    
    //     let participantId = $(this).data('id');
    //     let participantType = $(this).data('type');
    //     let name = $(this).text().trim();
    
    //     $('#chat-title').text(name);
    //     $('#chat-messages').html('<p class="text-muted">Loading...</p>');
    
    //     axios.post('{{guard_route("chat.getconversation")}}', {
    //         participant_id: participantId,
    //         participant_type: participantType
    //     }).then(res => {
    
    //         currentConversation = res.data.conversation_id;
    //         $('#chat-messages').html('');
    
    //         res.data.messages.forEach(m => appendMessage(m));
    //         listen(currentConversation);
    //     });
    // });
    
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
                <div class="${isMine ? 'bg-success-light text-white' : 'bg-light text-dark'} p-2 rounded shadow-sm" style="max-width: 70%;">
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
        // Leave previous channel
        if (chatChannel) window.Echo.leave(chatChannel);

        chatChannel = `conversation.${id}`;

        window.Echo
            .private(chatChannel)
            .listen('.message.sent', e => appendMessage(e.message));
    }

</script>
@endpush

