@extends('backend.theme.tabbed')

@section('tab-navigation')
    @include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patient Documents', 'url' =>guard_route('patient-documents.index', $patient)],
            ['label' => 'Documents List'],
        ];
    @endphp

    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Documents List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => guard_route('patient-documents.create', $patient),
        'isListPage' => true
    ])

    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-alt me-1"></i>
            Patient Documents Management
        </div>
        <div class="card-body">
            <div id="patient-documents-list" data-pagination-container>
                @include('patients.documents.list', [
                    'patient' => $patient,
                    'documents'=> $documents
                    ])
            </div>
        </div> 
    </div>        

@endsection
@push('scripts')
<script>
    $('#PatientDocument').DataTable({
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

