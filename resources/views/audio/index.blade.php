@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Patients', 'url' => route('audios.index')],
            ['label' => 'Audio Recording List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Audio Recording List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('audios.create'),
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
            Patients Audio Recording Management
        </div>
        <div class="card-body">
            <div id="patient-audio-list">
                @include('audio.list', [
                    'audios'=> $audios
                    ])
            </div>
        </div> 
    </div>
</div>
@endsection
@push('scripts')
<script>
$('#AudioTable').DataTable({
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

