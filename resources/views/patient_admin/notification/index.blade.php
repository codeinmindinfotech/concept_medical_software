@extends('layout.mainlayout')
@section('content')

<div class="content">
    <div class="container">
        @php
        $currentGuard = getCurrentGuard();
        $backUrl = "";
        if ($currentGuard === 'doctor') {
        $backUrl = guard_route('notification.form'); // adjust if different
        } elseif ($currentGuard === 'clinic') {
        $backUrl = guard_route('clinic.notification.form');
        } elseif (has_role('superadmin')) {
        $backUrl = guard_route('notifications.form');
        } elseif (has_role('manager')) {
        $backUrl = guard_route('notifications.managerform');
        } elseif ($currentGuard === 'patient') {
        $backUrl = guard_route('patient.notification.form');
        }
        @endphp

        @session('success')
        <div class="alert alert-success" role="alert">
            {{ $value }}
        </div>
        @endsession
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user-clock me-2"></i> Notifications All
                </h5>
                <a href="{{$backUrl }}" class="btn bg-primary text-white btn-light btn-sm">
                    <i class="fas fa-plus-circle me-1"></i> New Notification
                </a>
            </div>
            <div class="card-body">
                <form action="{{ guard_route('notifications.markAllAsRead') }}" method="POST" class="text-center my-3 validate-form">
                    @csrf
                    <button class="btn btn-sm btn-outline-primary">Mark All as Read</button>
                </form>

                <div id="notifications-list" data-pagination-container>
                    @include('notifications.list', ['notifications' => $notifications])
                </div>
            </div>
        </div>



    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
<script>
    $('#NotificationTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
        , columnDefs: [{
            targets: 2, // column index for "Start Date" (0-based)
            orderable: false // Disable sorting
        }]
    });

</script>
@endpush
