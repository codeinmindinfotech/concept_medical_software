<?php $page = 'patient-dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
{{-- @component('components.admin.breadcrumb')
@slot('title') Edit History @endslot
@slot('li_1') Patients @endslot
@slot('li_2') Edit @endslot
@endcomponent --}}
<!-- Page Content -->
<div class="content">
    <div class="container pt-3">

        <div class="row">
            <div class="col-lg-9 col-xl-10">
                <div class="card mb-4 shadow-sm p-3">
                    <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Audio Management
                        </h5>
                        <a href="{{guard_route('patients.audio.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> Audio New
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @include('patients.audio.list', [
                                'patient' => $patient,
                                'audios'=> $audios
                                ])
                        </div>
                    </div>
                </div>    
            </div>
            <!-- Profile Sidebar -->
            @component('components.admin.tab-navigation', ['patient' => $patient])
            @endcomponent
        </div>

    </div>

</div>
<!-- /Page Content -->

<x-transcription-modal :title="''" :transcription="''" />

@endsection

@push('scripts')
<script src="{{ asset('assets_admin/js/transcription.js') }}"></script>
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