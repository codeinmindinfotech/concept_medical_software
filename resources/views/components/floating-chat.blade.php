@props(['users', 'patients'])

<style>
  /* =========================
   Floating chat container
   ========================= */
#floating-chat {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 90%;
    max-width: 480px;
    max-height: 600px;
    background: #ffffff;
    border-radius: 10px;
    border: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    overflow: hidden; /* prevents entire panel from scrolling */
    z-index: 9999;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Header (always visible) */
#floating-chat-header {
    background: #007bff;
    color: #fff;
    padding: 10px 15px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0; /* prevents header from shrinking */
}
#floating-chat-header i {
    cursor: pointer;
}

/* =========================
   Chat body container
   ========================= */
#chat-body-container {
    display: flex;
    flex-direction: column;
    flex: 1 1 auto;
    overflow: hidden; /* restricts overflow to children */
}

/* -------------------------
   Left sidebar (users list)
   ------------------------- */
#chat-sidebar {
    overflow-y: auto; /* only user list scrolls */
    background: #f5f5f5;
}
#chat-user-list {
    list-style: none;
    margin: 0;
    padding: 0;
}
#chat-user-list .user-item {
    cursor: pointer;
    display: flex;
    align-items: center;
    padding: 10px 12px;
    border-bottom: 1px solid #e0e0e0;
    transition: background 0.2s;
}
#chat-user-list .user-item:hover,
#chat-user-list .user-item.active {
    background: #e9f7fe;
    color: black;
}

/* Avatar */
.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #777;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
    font-size: 0.9rem;
}

/* Unread badge */
.user-item .badge-unread {
    background: #dc3545;
    color: white;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 50%;
    margin-left: auto;
    display: none;
}

/* =========================
   Chat main panel (right)
   ========================= */
#chat-main-panel {
    display: flex;
    flex-direction: column;
    flex: 1;
    overflow: hidden;
}

/* Messages (scrolls only here) */
#floating-chat-messages {
    flex: 1 1 auto;
    overflow-y: auto; /* only messages scroll */
    padding: 10px;
    height: 350px;
    background: #fafafa;
}

/* Optional scrollbar style */
#floating-chat-messages::-webkit-scrollbar {
    width: 6px;
}
#floating-chat-messages::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,0.15);
    border-radius: 3px;
}

/* Message bubbles */
.message {
    margin-bottom: 10px;
    padding: 8px 12px;
    border-radius: 8px;
}
.message.mine {
    background: #007bff;
    color: white;
    margin-left: auto;
    max-width: 70%;
}
.message.other {
    background: #e9ecef;
    color: #333;
    margin-right: auto;
    max-width: 70%;
}

/* =========================
   Input area (bottom)
   ========================= */
#floating-chat-input {
    display: flex;
    border-top: 1px solid #ddd;
    padding: 2px;
    background: #fff;
    flex-shrink: 0; /* keeps input visible */
}
#floating-chat-input input {
    flex: 1;
    border: 1px solid #ccc;
    border-radius: 20px;
    padding: 0px 7px;
}
#floating-chat-input button {
    margin-left: 8px;
}

/* -------------------------
   Floating trigger icon
   ------------------------- */
#chat-trigger {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 10000;
    cursor: pointer;
}
#chat-trigger .badge {
    background: #dc3545;
    color: #fff;
    font-size: 0.75rem;
    padding: 3px 7px;
    border-radius: 50%;
    display: none;
}

/* =========================
   Responsive behavior
   ========================= */

/* On tablets and desktops */
@media (min-width: 768px) {
    #chat-body-container {
        flex-direction: row; /* left/right columns */
    }
    #chat-sidebar {
        flex: 0 0 220px; /* fixed width */
        border-right: 1px solid #ddd;
    }
    #chat-main-panel {
        flex: 1;
    }
}

/* On small mobile screens */
@media (max-width: 767px) {
    #chat-sidebar {
        border-bottom: 1px solid #ddd;
    }
    #floating-chat {
        width: calc(100% - 20px);
        right: 10px;
        bottom: 10px;
    }
}

</style>

<!-- Floating Chat Trigger -->
<div id="chat-trigger">
    <div class="btn btn-primary rounded-circle p-3 shadow">
        <i class="fa-solid fa-comments fa-lg text-white"></i>
        <span id="chat-unread-count" class="badge">0</span>
    </div>
</div>

<!-- Floating Chat Panel -->
<div id="floating-chat" class="d-none shadow rounded">

    <!-- Chat Header -->
    <div id="floating-chat-header" class="bg-primary text-white px-3 py-2">
        Chat
        <i id="close-chat" class="fa-solid fa-xmark float-end" style="cursor:pointer;"></i>
    </div>

    <!-- Chat Body -->
    <div id="chat-body-container" >

        <!-- Left: User / Patient List -->
        <div id="chat-sidebar" class="sidebar-expanded">
            <!-- Collapse/Expand Button -->
            {{-- <div id="sidebar-toggle" class="sidebar-toggle">
                <i class="fa-solid fa-chevron-left"></i>
            </div> --}}
            <ul id="chat-user-list" class="list-group list-group-flush">
                @foreach($users as $u)
                <li class="list-group-item user-item" data-id="{{ $u->id }}" data-type="user">
                    <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                    <div>{{ $u->name }}
                    </div>
                    <br/>
                    <p class="text-muted usertype"> ({{ $u->getRoleNames()->first() ?? 'Admin' }})</p>
                    <span class="badge-unread">0</span>
                </li>
                @endforeach

                @foreach($patients as $p)
                <li class="list-group-item user-item" data-id="{{ $p->id }}" data-type="patient">
                    @if($p->patient_picture)
                        <img src="{{ asset('storage/patient_pictures/'.$p->id.'/small.jpg') }}" class="rounded-circle" width="35" height="35">
                    @else
                        <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                    @endif
                    <div>{{ $p->full_name }}</div>
                    <small class="text-muted usertype"> (Patient)</small>

                    <span class="badge-unread">0</span>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Right: Messages Area -->
        <div id="chat-main-panel">
            <!-- Messages -->
            <div id="floating-chat-messages"></div>

            <!-- Input -->
            <div id="floating-chat-input">
                <input id="floating-message" type="text" placeholder="Type a message...">
                <button id="floating-send" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>

    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script type="module">
import Echo from "https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.min.js";

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "{{ config('broadcasting.connections.pusher.key') }}",
    cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
    forceTLS: true,
    auth: { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } }
});

let currentConversation = null;
let chatChannel = null;
let unreadCounts = {};  // e.g. { "user_2": 3 }
let totalUnread = 0;

// Update total chat badge
function updateTotalUnread(){
    totalUnread = Object.values(unreadCounts).reduce((sum, val) => sum + val, 0);
    if(totalUnread > 0){
        $('#chat-unread-count').text(totalUnread).show();
    } else {
        $('#chat-unread-count').hide();
    }
}


// Toggle chat panel
document.getElementById('chat-trigger').addEventListener('click', () => {
    document.getElementById('floating-chat').classList.toggle('d-none');
    $('#chat-unread-count').hide();
});
document.getElementById('close-chat').addEventListener('click', () => {
    document.getElementById('floating-chat').classList.add('d-none');
});

// Select user/patient
$('#chat-user-list').on('click', '.user-item', function () {
    $('.user-item').removeClass('active');
    $(this).addClass('active');

    let participantId = $(this).data('id');
    let participantType = $(this).data('type');
    let key = participantType+'_'+participantId;

    // Reset unread for this user
    unreadCounts[key] = 0;
    $(this).find('.badge-unread').hide();
    updateTotalUnread();

    $('#floating-chat-messages').html('<p class="text-muted">Loading...</p>');
    axios.post('{{guard_route("chat.getconversation")}}', { participant_id: participantId, participant_type: participantType })
        .then(res => {
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

    axios.post('{{guard_route("chat.send")}}', { conversation_id: currentConversation, message })
        .then(res => {
            appendMessage(res.data);
            $('#floating-message').val('');
        });
});

// Append message
function appendMessage(m){
    let isMine = m.sender_id == {{ auth()->id() }};
    let senderName = isMine ? 'You' : (m.sender?.name ?? 'User');
    let timestamp = new Date(m.created_at);
    let timeLabel = timestamp.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

    let html = `<div class="message ${isMine ? 'mine bg-success-light text-white' : 'other'}">
        <div class="small text-muted mb-1">${senderName} • ${timeLabel}</div>
        <div>${m.message}</div>
    </div>`;
    $('#floating-chat-messages').append(html);
    $('#floating-chat-messages').scrollTop($('#floating-chat-messages')[0].scrollHeight);
}

// Listen for messages
function listen(conversationId){
    if(chatChannel) window.Echo.leave(chatChannel);
    chatChannel = `conversation.${conversationId}`;

    window.Echo.private(chatChannel)
        .listen('.message.sent', e => {
            appendMessage(e.message);

            // Update unread if not current user
            let active = $('.user-item.active');
            if(!active.length || active.data('id') != e.message.sender_id || active.data('type') != e.message.sender_type){
                let key = e.message.sender_type+'_'+e.message.sender_id;
                unreadCounts[key] = (unreadCounts[key] || 0) + 1;
                $(`.user-item[data-id=${e.message.sender_id}][data-type=${e.message.sender_type}] .badge-unread`)
                    .text(unreadCounts[key]).show();
                updateTotalUnread();
            }
        });
}
</script>