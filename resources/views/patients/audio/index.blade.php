@extends('backend.theme.tabbed')

@section('tab-navigation')
    @include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patients.index')],
            ['label' => 'Patients Consultation List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Patients Consultation List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.audio.create',$patient->id),
        'isListPage' => true
    ])

    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-notes-medical me-1"></i>
            Patients Consultation Management
        </div>
        <div class="card-body">
            <div id="patient-audio-list">
                @include('patients.audio.list', [
                    'patient' => $patient,
                    'audios'=> $audios
                    ])
            </div>
        </div> 
    </div>    
</div>

<x-transcription-modal :title="''" :transcription="''" />

@endsection

@push('scripts')
<script src="{{ asset('theme/transcription.js') }}"></script>
<script>
    $(document).ready(function() {
    if ( $.fn.DataTable.isDataTable('#PatientAudioTable') ) {
        $('#PatientAudioTable').DataTable().destroy();
    }

    $('#PatientAudioTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: true,
        pageLength: 10,
        columnDefs: [
            {
                targets: 3, // Disable sorting for Action column
                orderable: false
            }
        ]
    });
});

</script>
@endpush