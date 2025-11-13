<?php $page = 'user-list'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-4">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
                ['label' => 'Users', 'url' =>guard_route('users.index')],
                ['label' => 'Users List'],
            ];
        @endphp

        @include('layout.partials.breadcrumb', [
            'pageTitle' => 'Users List',
            'breadcrumbs' => $breadcrumbs,
            'backUrl' =>guard_route('users.create'),
            'isListPage' => true
        ])
        
        
        @session('success')
            <div class="alert alert-success" role="alert"> 
                {{ $value }}
            </div>
        @endsession

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            @include('users.list', ['data' => $data])
                        </div>
                    </div>
                </div>
            </div>
        </div>                    
    </div>
</div>
<!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->
@endsection
@push('scripts')
<script>
    $('#UserTable').DataTable({
     paging: true,
     searching: true,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
     columnDefs: [
       {
         targets: 4, // column index for "Start Date" (0-based)
         orderable: false   // Disable sorting
       }
     ]
   });
</script>
@endpush