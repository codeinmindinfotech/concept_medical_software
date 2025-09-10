@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Clinics List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Clinics List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('clinics.create'),
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
            Clinics Management
        </div>
        <div class="card-body">
            <div id="clinics-list" data-pagination-container>
                @include('clinics.list', ['clinics' => $clinics])
            </div>
        </div> 
    </div>
</div>
@endsection
@push('scripts')
<script>
    $('#ClinicTable').DataTable({
     paging: true,
     searching: true,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
     columnDefs: [
       {
         targets: 6, // column index for "Start Date" (0-based)
         orderable: false   // Disable sorting
       }
     ]
   });
   </script>
   @endpush