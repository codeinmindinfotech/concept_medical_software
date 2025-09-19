@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => guard_route('dashboard.index')],
            ['label' => 'Notifictaion List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Notifictaion List',
        'breadcrumbs' => $breadcrumbs,
        'isListPage' => true
    ])
    
    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Notifictaion Management
        </div>
        <div class="card-body">
            <form action="{{ guard_route('notifications.markAllAsRead') }}" method="POST" class="text-center my-3 validate-form" >
                @csrf
                <button class="btn btn-sm btn-outline-primary">Mark All as Read</button>
            </form>
            
            <div id="notifications-list" data-pagination-container>
                @include('notifications.list', ['notifications' => $notifications])
            </div>
        </div> 
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
<script>
    $('#NotificationTable').DataTable({
     paging: true,
     searching: true,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
     columnDefs: [
       {
         targets: 2, // column index for "Start Date" (0-based)
         orderable: false   // Disable sorting
       }
     ]
   });
</script>
@endpush