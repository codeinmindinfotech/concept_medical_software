@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Patients', 'url' => route('patients.index')],
            ['label' => 'Patient Physical Exams List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Patients List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('patients.physical.create', $patient->id),
        'isListPage' => true
    ])

    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    
    <div class="card mb-4">
        <div class="card-header">
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
</div>
@endsection
@push('scripts')
@endpush

