@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Patients', 'url' => route('patients.index')],
            ['label' => 'Patients List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Patients List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('patients.notes.create', $patient->id),
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
            Patients Notes Management
        </div>
        <div class="card-body">
            <div id="patient-notes-list" data-pagination-container>
                @include('patients.notes.list', [
                    'patient' => $patient,
                    'notes'=> $notes
                    ])
            </div>
        </div> 
    </div>
</div>
@endsection