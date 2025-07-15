@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Dropdown List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Dropdown List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('dropdowns.create'),
        'isListPage' => true
    ])
    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-md"></i>
            Dropdown Management
        </div>
        
        <div class="card-body">
            <div id="dropdown-list" data-pagination-container>
                @include('dropdowns.list', ['dropdowns' => $dropdowns])
            </div>
        </div>     
    </div>
</div>
@endsection
@push('scripts')
<script>
    $('#DropdownTable').DataTable({
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