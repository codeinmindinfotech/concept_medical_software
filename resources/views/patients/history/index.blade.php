@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Patients', 'url' => route('patients.index')],
            ['label' => 'Patient History List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Patient History List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('patients.history.create', $patient->id),
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
</div>
@endsection
<script>
    $('#PatientHistoryTable').DataTable({
  paging: true,
  searching: true,
  ordering: true,
  info: true,
  lengthChange: true,
  pageLength: 5
});

  // Or initialize all tables with class .data-table
    // initDataTable('#PatientHistoryTable', {
    //     perPage: 15,
    //     fixedHeight: true,
    // });

</script>

@push('scripts')
@endpush

