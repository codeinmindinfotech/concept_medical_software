@extends('backend.theme.tabbed')

@section('tab-navigation')
@include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
@php
$breadcrumbs = [
['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
['label' => 'Patients', 'url' =>guard_route('patients.index')],
['label' => 'Patient History List'],
];
@endphp

@include('backend.theme.breadcrumb', [
'pageTitle' => 'Patient History List',
'breadcrumbs' => $breadcrumbs,
'backUrl' =>guard_route('patients.history.create', $patient->id),
'isListPage' => true
])

@session('success')
<div class="alert alert-success" role="alert">
    {{ $value }}
</div>
@endsession

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-history"></i>
        Patient History Management
    </div>
    <div class="card-body">
        <div id="patient-history-list" data-pagination-container>
            @include('patients.history.list', [
            'patient' => $patient,
            'historys'=> $historys
            ])
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    $('#PatientHistoryTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: true,
        pageLength: 5
    });
</script>
@endpush