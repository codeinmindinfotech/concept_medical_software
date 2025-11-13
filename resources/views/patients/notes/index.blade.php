@extends('backend.theme.tabbed')

@section('tab-navigation')
    @include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patients.index')],
            ['label' => 'Patients List'],
        ];
    @endphp

    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Patients List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.notes.create', $patient->id),
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
            Patient Notes Management
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

@endsection
@push('scripts')
<script>
    $('#PatientNote').DataTable({
     paging: true,
     searching: true,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
     columnDefs: [
       {
         targets: 5, // column index for "Start Date" (0-based)
         orderable: false   // Disable sorting
       }
     ]
   });
    $(document).ready(function() {
        $('.data-table').on('click', '.toggle-completed', function() {
            var badge = $(this);
            var toggleUrl = badge.data('url');

            $.ajax({
                url: toggleUrl,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        badge
                            .removeClass('bg-success bg-warning')
                            .addClass('bg-' + response.badge_class)
                            .text(response.text);
                    }
                },
                error: function() {
                    Swal.fire("Error", 'Failed to toggle completed status.', "warning");
                }
            });
        });
    });
</script>
@endpush

