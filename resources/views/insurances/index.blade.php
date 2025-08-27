@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Insurances List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Insurances List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('insurances.create'),
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
            Insurances Management
        </div>
        <div class="card-body">
            <div id="insurances-list" data-pagination-container>
                @include('insurances.list', ['insurances' => $insurances])
            </div>
        </div> 
    </div>
</div>
@endsection
@push('scripts')
<script>
    $('#InsuranceTable').DataTable({
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