@if($notifications->count())
    <ul class="list-group mb-3">
        @foreach($notifications as $notification)
            @php
                $isUnread = is_null($notification->read_at);
                $message = $notification->data['message'] ?? 'New notification';
                $url = $notification->data['url'] ?? '#';
            @endphp

            <li class="list-group-item p-3 {{ $isUnread ? 'bg-light' : '' }}">
                <a href="{{ $url }}" class="text-decoration-none text-dark d-flex align-items-start gap-3">
                    <div class="fs-4">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ $message }}</strong>
                            @if($isUnread)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-circle me-1"></i> Unread
                            </span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="d-flex justify-content-center">
        {{ $notifications->links() }}
    </div>
@else
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <div>You have no notifications.</div>
    </div>
@endif


