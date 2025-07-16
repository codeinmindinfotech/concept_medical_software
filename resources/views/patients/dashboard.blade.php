@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Patients', 'url' => route('patients.index')],
    ['label' => 'Patient Dashboard'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Patient Dashboard',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('patients.index'),
    'isListPage' => false
    ])
    <style>
        .tab-content {
            min-height: 400px;
        }

        .nav-pills .nav-link {
            border-radius: 0;
            text-align: left;
        }

        .nav-pills .nav-link.active {
            background-color: #0d6efd;
        }

        .action-buttons .btn {
            margin-right: 10px;
        }

    </style>
    <div class="container">
        <!-- Patient Header -->
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex align-items-center">
                <img src="https://via.placeholder.com/70" alt="Avatar" class="rounded-circle me-3">
                <div>
                    <h4 class="mb-1"> (Working)
                        {{ optional($patient->title)->value ? $patient->title->value . ' ' : '' }}
                        {{ $patient->first_name }} {{ $patient->surname }}
                    </h4>
                    <p class="mb-0 text-muted">DOB: {{ format_date($patient->dob) }} | Gender: {{ $patient->gender }}</p>
                </div>
                @can('update', $patient)
                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-outline-primary btn-sm ms-auto">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endcan

            </div>
        </div>

        <div class="row">
            <!-- Tabs Navigation -->
            <div class="col-md-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="tab-waiting" data-bs-toggle="pill" data-bs-target="#waiting" type="button" role="tab">
                        <i class="fas fa-notes-medical me-2"></i>Waiting Lists
                    </button>
                    <button class="nav-link" id="tab-fee" data-bs-toggle="pill" data-bs-target="#fee" type="button" role="tab">
                        <i class="fas fa-money-check-alt me-2"></i>Fee Notes
                    </button>
                    <button class="nav-link" id="tab-recalls" data-bs-toggle="pill" data-bs-target="#recalls" type="button" role="tab">
                        <i class="fas fa-bell me-2"></i>Recalls
                    </button>
                    <button class="nav-link" id="tab-docs" data-bs-toggle="pill" data-bs-target="#docs" type="button" role="tab">
                        <i class="fas fa-file-alt me-2"></i>Documents
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="col-md-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="waiting" role="tabpanel">
                        @include('patients.waiting_lists.list')
                    </div>
                    <div class="tab-pane fade" id="fee" role="tabpanel">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Fee Notes</h5>
                                <button id="addFeeNoteBtn" class="btn btn-primary btn-sm">
                                    <i class="fa bi-plus"></i> Add Fee Note
                                </button>
                            </div>

                            <div id="feeNotesList">
                                @include('patients.dashboard.fee_notes.list', [
                                'feeNotes' => $feeNotes,
                                'patient' => $patient])
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="recalls" role="tabpanel">
                        <div class="card mb-4">
                            Recalls Content
                        </div>
                    </div>
                    <div class="tab-pane fade" id="docs" role="tabpanel">
                        <div class="card mb-4">
                            Documents Content
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('patients.waiting_lists.form', [
'patient' => $patient,
'clinics' => $clinics,
'categories' => $categories
])

@include('patients.dashboard.fee_notes.form', [
'clinics' => $clinics,
'patient' => $patient,
'consultants' => $consultants,
'chargecodes' => $chargecodes])

@endsection

@push('scripts')

<script src="{{ asset('theme/patient-dashboard.js') }}"></script>
<script src="{{ asset('theme/form-validation.js') }}"></script>

<script>
    $('#FeeNoteTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
        , columnDefs: [{
            targets: 5, // column index for "Start Date" (0-based)
            orderable: false // Disable sorting
        }]
    });

</script>

@endpush

