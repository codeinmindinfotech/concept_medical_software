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
                <form id="uploadPatientPictureForm" action="{{ route('patients.upload-picture', $patient->id) }}" enctype="multipart/form-data" class="position-relative me-3">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    <input type="file" name="patient_picture" id="patient_picture_input" class="d-none" accept="image/*">
        
                    <label for="patient_picture_input" style="cursor: pointer;">
                        <img id="patient_picture_preview"
                             src="{{ $patient->patient_picture ? asset('storage/' . $patient->patient_picture) : '' }}"
                             alt="Avatar"
                             class="rounded-circle"
                             style="width: 70px; height: 70px; object-fit: cover;"
                        >
                        <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow" title="Change picture">
                            <i class="fas fa-camera"></i>
                        </span>
                    </label>
                </form>
        
                <div>
                    <h4 class="mb-1">
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
                                <button id="addFeeNoteBtn" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Add Fee Note
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Recall</h5>
                                <button id="addRecallBtn" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Add Recall
                                </button>
                            </div>

                            <div id="recallList">
                                @include('patients.dashboard.recalls.list', [
                                'recalls' => $recalls,
                                'patient' => $patient])
                            </div>
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
@include('patients.dashboard.recalls.form', [
'patient' => $patient,
'statuses' => $statuses
])

@include('patients.dashboard.fee_notes.form', [
'clinics' => $clinics,
'patient' => $patient,
'consultants' => $consultants,
'chargecodes' => $chargecodes])

@include('patients.waiting_lists.form', [
'patient' => $patient,
'clinics' => $clinics,
'categories' => $categories
])





@endsection

@push('scripts')

<script src="{{ asset('theme/patient-dashboard.js') }}"></script>
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush

