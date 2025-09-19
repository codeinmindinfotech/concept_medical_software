@if($notifications->count())
        <ul class="list-group mb-3">
            @foreach($notifications as $notification)
                <li class="list-group-item {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                    <div class="d-flex justify-content-between">
                        <div>
                            {{ $notification->data['message'] ?? 'New message' }}
                            <br>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        @if(is_null($notification->read_at))
                            <span class="badge bg-warning text-dark">Unread</span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>

        {{ $notifications->links() }} <!-- Pagination links -->
@else
    <p class="text-muted">You have no notifications.</p>
@endif

