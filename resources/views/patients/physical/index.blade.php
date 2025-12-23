@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
@php
$breadcrumbs = [
['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
['label' => 'Patients', 'url' =>guard_route('patients.index')],
['label' => 'Patient Physical Exams List'],
];
@endphp

@include('layout.partials.breadcrumb', [
'pageTitle' => 'Patients List',
'breadcrumbs' => $breadcrumbs,
'backUrl' =>guard_route('patients.physical.create', $patient->id),
'isListPage' => true
])

@session('success')
<div class="alert alert-success" role="alert">
    {{ $value }}
</div>
@endsession

<div class="card mb-4">
    <div class="card-header mb-1 p-2">
        <i class="fas fa-book-open"></i>
        Patient Physical Exam Management
    </div>
    <div class="card-body">
        <div id="patient-physical-list" data-pagination-container>
            @include('patients.physical.list', [
            'patient' => $patient,
            'physicals'=> $physicals
            ])
        </div>
    </div>
</div>


@endsection
@push('scripts')
<script>
    $('#PatientPhysical').DataTable({
     paging: true,
     searching: true,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
     columnDefs: [
       {
         targets: 3, // column index for "Start Date" (0-based)
         orderable: false   // Disable sorting
       }
     ]
   });
</script>
@endpush