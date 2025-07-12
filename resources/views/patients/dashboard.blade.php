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
        

        {{-- Vertical Tabs Layout --}}
        <div class="row">
            <div class="col-md-3">
              <div class="nav flex-column nav-pills me-3" id="v-patient-tabs" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#waiting-lists" type="button"> 
                      <i class="fas fa-notes-medical me-2"></i>Waiting Lists
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#recalls" type="button">
                      <i class="fas fa-bell me-2"></i>Recalls
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#communication" type="button">
                      <i class="fas fa-comments me-2"></i>Communication
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#documents" type="button">
                      <i class="fas fa-file-alt me-2"></i>Documents
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#fee-notes" type="button">
                      <i class="fas fa-money-check-alt me-2"></i>Fee Notes
                    </button>

                  </div>
            </div>

            <!-- Content -->
            <div class="col-md-9">
                <div class="tab-content" id="v-tabs-content">

                    <!-- Waiting Lists Tab -->
                    <div class="tab-pane fade show active" id="waiting-lists" role="tabpanel">
                      <div id="WaitingListsList">
                          @include('patients.waiting_lists.list')
                      </div>  
                      @include('patients.waiting_lists.form')      
                    </div>

                    <!-- Recalls Tab -->
                    <div class="tab-pane fade" id="recalls" role="tabpanel">
                        <h4>Recalls</h4>
                        <p>Recall content here...</p>
                    </div>

                    <!-- Documents Tab -->
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <h4>Documents</h4>
                        <p>Documents content here...</p>
                    </div>

                    <!-- Fee Notes Tab -->
                    <div class="tab-pane fade" id="fee-notes" role="tabpanel">
                        <h4>Fee Notes</h4>
                        <p>Fee notes content here...</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script src="{{ asset('theme/patient-dashboard.js') }}"></script>
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush

