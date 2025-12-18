<?php $page = 'doctors-list'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Doctor List'],
        ];
    @endphp

    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Doctor List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('doctors.create'),
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
                        @include('doctors.list', ['doctors' => $doctors])
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
    $('#DoctorTable').DataTable({
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