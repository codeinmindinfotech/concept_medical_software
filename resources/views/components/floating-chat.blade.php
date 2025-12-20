@props(['users', 'patients'])
<style>
    #floating-chat {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 320px;
        max-height: 500px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #ddd;
        z-index: 1051;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    /* Chat header */
    #floating-chat-header {
        font-weight: 600;
        cursor: pointer;
    }

    #floating-chat .user-list-container {
        flex: 0 0 auto;
        /* fixed height user list */
    }

    #floating-chat-messages {
        flex: 1 1 auto;
        /* take remaining space */
        overflow-y: auto;
    }

    #chat-user-list .user-item {
        cursor: pointer;
        transition: background 0.2s, border-left 0.2s;
        border-left: 4px solid transparent;
    }

    #chat-user-list .user-item.active {
        background-color: #66d7e9e3;
        border-left: 4px solid #15525b;
    }

    #chat-user-list .user-item:hover {
        background-color: #f1f5f9;
        color: black;
    }

    .user-avatar i {
        font-size: 1rem;
    }


    /* Mobile responsiveness */
    @media (max-width: 576px) {
        #floating-chat {
            right: 10px !important;
            bottom: 10px !important;
            width: calc(100% - 20px) !important;
            left: 10px;
        }
    }

</style>

    
<!-- Floating Chat Trigger Button -->
<div id="chat-trigger" style="position: fixed; bottom: 20px; right: 20px; z-index: 1050; cursor: pointer;">
    <div class="btn btn-primary rounded-circle p-3 shadow">
        <i class="fa-solid fa-comments fa-lg text-white"></i>
        <span id="chat-unread-count" class="badge bg-danger rounded-circle" style="position:absolute; top:0; right:0; font-size:0.7rem; display:none;">0</span>
    </div>
</div>

<!-- Floating Chat Panel -->
<div id="floating-chat" class="d-none shadow rounded">
    <div id="floating-chat-header" class="bg-primary text-white px-3 py-2 rounded-top">
        Chat
        <i id="close-chat" class="fa-solid fa-xmark float-end" style="cursor:pointer;"></i>
    </div>

    <!-- User / Patient list -->
    <div class="p-2 border-bottom" style="max-height: 200px; overflow-y:auto;">
        <ul class="list-group list-group-flush" id="chat-user-list">
            @foreach($users as $u)
            <li class="list-group-item user-item d-flex align-items-center px-2 py-1" data-id="{{ $u->id }}" data-type="user">

                <div class="user-avatar rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div class="user-info flex-grow-1">
                    <h6 class="mb-0" style="font-size:0.85rem;">{{ $u->name }}</h6>
                    <small class="text-muted">{{ $u->getRoleNames()->first() }}</small>
                </div>
            </li>
            @endforeach

            @foreach($patients as $p)
            <li class="list-group-item user-item d-flex align-items-center px-2 py-1" data-id="{{ $p->id }}" data-type="patient">

                @if($p->patient_picture)
                <img src="{{ asset('storage/patient_pictures/'.$p->id.'/small.jpg') }}" class="rounded-circle me-2" width="35" height="35">
                @else
                <div class="user-avatar rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                    <i class="fa-solid fa-user"></i>
                </div>
                @endif

                <div class="user-info flex-grow-1">
                    <h6 class="mb-0" style="font-size:0.85rem;">{{ $p->full_name }}</h6>
                    <small class="text-muted">Patient</small>
                </div>
            </li>
            @endforeach
        </ul>
    </div>


    <!-- Messages -->
    <div id="floating-chat-messages" style="flex:1; overflow-y:auto; padding:10px;"></div>

    <!-- Input -->
    <div class="p-2 border-top d-flex">
        <input id="floating-message" type="text" class="form-control" placeholder="Type a message...">
        <button id="floating-send" class="btn btn-primary ms-2">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
// Toggle chat panel
document.getElementById('chat-trigger').addEventListener('click', function () {
    document.getElementById('floating-chat').classList.toggle('d-none');
    $('#chat-unread-count').hide();
});
document.getElementById('close-chat').addEventListener('click', function () {
    document.getElementById('floating-chat').classList.add('d-none');
});

let currentConversation = null;
let chatChannel = null;
let unreadCount = 0;

// Select user/patient
$('#chat-user-list').on('click', '.user-item', function () {
    $('.user-item').removeClass('active');
    $(this).addClass('active');

    let participantId = $(this).data('id');
    let participantType = $(this).data('type');

    $('#floating-chat-messages').html('<p class="text-muted">Loading...</p>');

    axios.post('{{guard_route("chat.getconversation")}}', {
        participant_id: participantId,
        participant_type: participantType
    }).then(res => {
        currentConversation = res.data.conversation_id;
        $('#floating-chat-messages').html('');
        res.data.messages.forEach(m => appendMessage(m));
        listen(currentConversation);
    });
});

// Send message
$('#floating-send').on('click', function () {
    let message = $('#floating-message').val().trim();
    if(!message || !currentConversation) return;

    axios.post('{{guard_route("chat.send")}}', {
        conversation_id: currentConversation,
        message: message
    }).then(res => {
        appendMessage(res.data);
        $('#floating-message').val('');
    });
});
</script>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.min.js"></script>

<script>
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "{{ config('broadcasting.connections.pusher.key') }}",
    cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
    forceTLS: true
});

// Append message
function appendMessage(m){
    let isMine = m.sender_id == {{ auth()->id() }};
    let senderName = isMine ? 'You' : (m.sender?.name ?? 'User');

    let timestamp = m.created_at ? new Date(m.created_at) : new Date();
    let now = new Date();
    let timeLabel = '';
    if(timestamp.toDateString() === now.toDateString()){
        timeLabel = `Today ${timestamp.getHours().toString().padStart(2,'0')}:${timestamp.getMinutes().toString().padStart(2,'0')}`;
    } else {
        timeLabel = `${timestamp.getFullYear()}-${(timestamp.getMonth()+1).toString().padStart(2,'0')}-${timestamp.getDate().toString().padStart(2,'0')} ${timestamp.getHours().toString().padStart(2,'0')}:${timestamp.getMinutes().toString().padStart(2,'0')}`;
    }

    let messageHTML = `
        <div class="d-flex ${isMine ? 'justify-content-end' : 'justify-content-start'} mb-2">
            <div class="${isMine ? 'bg-success-light text-white' : 'bg-light text-dark'} p-2 rounded shadow-sm" style="max-width: 70%;">
                <div class="small text-muted mb-1">${senderName} • ${timeLabel}</div>
                <div>${m.message}</div>
            </div>
        </div>
    `;
    $('#floating-chat-messages').append(messageHTML);
    $('#floating-chat-messages').scrollTop($('#floating-chat-messages')[0].scrollHeight);
}

// Listen for new messages
function listen(id){
    if(chatChannel) window.Echo.leave(chatChannel);
    chatChannel = `conversation.${id}`;
    window.Echo.private(chatChannel).listen('.message.sent', e => {
        appendMessage(e.message);
        if($('#floating-chat').hasClass('d-none')){
            unreadCount++;
            $('#chat-unread-count').text(unreadCount).show();
        }
    });
}
</script>