@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Patients', 'url' => route('patients.index')],
    ['label' => 'Show Patient'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Show Patient',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('patients.index'),
    'isListPage' => false
    ])

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab">
                <i class="fas fa-user me-1"></i>Patient
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                <i class="fas fa-user-md me-1"></i>Doctor
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                <i class="fas fa-file-invoice-dollar me-1"></i>Insurance
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="note-tab" data-bs-toggle="tab" data-bs-target="#note" type="button" role="tab">
                <i class="fas fa-sticky-note me-1"></i>Notes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="physical-notes-tab" data-bs-toggle="tab" data-bs-target="#physical-notes" type="button" role="tab">
                <i class="fas fa-stethoscope me-1"></i>Physical Exams
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="history-notes-tab" data-bs-toggle="tab" data-bs-target="#history-notes" type="button" role="tab">
                <i class="fas fa-history me-1"></i>History Notes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="audio-tab" data-bs-toggle="tab" data-bs-target="#audio" type="button" role="tab">
                <i class="fas fa-microphone me-1"></i>Audio Recordings
            </button>
        </li>
    </ul>


    <div class="tab-content border border-top-0 p-3" id="myTabContent">

        {{-- Patient Information --}}
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row g-4">

                {{-- ▶ Personal Information --}}
                <div class="col-md-6">
                    <div class="card border-start border-primary shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user me-2 text-primary"></i>Personal Information
                            </h5>
                        </div>
                        <div class="card-body row g-3">
                            <x-show-field label="Title" :value="$patient->title->value ?? '-'" />
                            <x-show-field label="Surname" :value="$patient->surname" />
                            <x-show-field label="First Name" :value="$patient->first_name" />
                            <x-show-field label="Date of Birth" :value="optional($patient->dob)->format('Y-m-d')" />
                            <x-show-field label="Gender" :value="$patient->gender" />
                            <x-show-field label="Doctor" :value="$patient->doctor->name ?? '-'" />
                        </div>
                    </div>
                </div>

                {{-- ▶ Contact Information --}}
                <div class="col-md-6">
                    <div class="card border-start border-info shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-phone-alt me-2 text-info"></i>Contact Information
                            </h5>
                        </div>
                        <div class="card-body row g-3">
                            <x-show-field label="Phone" :value="$patient->phone" />
                            <x-show-field label="Email" :value="$patient->email" />
                            <x-show-field label="Preferred Contact" :value="$patient->preferredContactMethod->value ?? '-'" />
                            <div class="col-12">
                                <label class="form-label"><strong>Address</strong></label>
                                <p class="mb-0 text-muted">{{ $patient->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ▶ Emergency & Medical Info --}}
                <div class="col-12">
                    <div class="card border-start border-danger shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-briefcase-medical me-2 text-danger"></i>Emergency & Medical Info
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach ([
                            'Emergency Contact' => $patient->emergency_contact,
                            'Medical History / Notes' => $patient->medical_history,
                            'Referral Reason' => $patient->referral_reason,
                            'Symptoms' => $patient->symptoms,
                            'Patient Needs' => $patient->patient_needs,
                            'Allergies' => $patient->allergies,
                            'Diagnosis' => $patient->diagnosis
                            ] as $label => $value)
                            <div class="mb-3">
                                <label class="form-label"><strong>{{ $label }}</strong></label>
                                <p class="mb-0 text-muted">{{ $value ?? '-' }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ▶ Insurance Information --}}
                <div class="col-12">
                    <div class="card border-start border-warning shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-invoice-dollar me-2 text-warning"></i>Insurance Information
                            </h5>
                        </div>
                        <div class="card-body row g-3">
                            <x-show-field label="Insurance Provider" :value="$patient->insurance->code ?? '-'" />
                            <x-show-field label="Insurance Plan" :value="$patient->insurance_plan" />
                            <x-show-field label="Policy Number" :value="$patient->policy_no" />
                        </div>
                    </div>
                </div>

                {{-- ▶ Patient Status & Consent --}}
                <div class="col-md-6 mt-4">
                    <div class="card border-start border-success shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-check-circle me-2 text-success"></i>Patient Status & Consent
                            </h5>
                        </div>
                        <div class="card-body">
                            <x-show-field label="RIP" :value="$patient->rip ? 'Yes' : 'No'" />
                            <x-show-field label="Date of RIP" :value="optional($patient->rip_date)->format('Y-m-d')" />
                            <x-show-field label="SMS Consent" :value="$patient->sms_consent ? 'Yes' : 'No'" />
                            <x-show-field label="Email Consent" :value="$patient->email_consent ? 'Yes' : 'No'" />
                        </div>
                    </div>
                </div>

                {{-- ▶ COVID-19 Vaccination Info --}}
                <div class="col-md-6 mt-4">
                    <div class="card border-start border-dark shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-syringe me-2 text-dark"></i>COVID-19 Vaccination Info
                            </h5>
                        </div>
                        <div class="card-body">
                            <x-show-field label="Vaccination Date" :value="optional($patient->covid_19_vaccination_date)->format('Y-m-d')" />
                            <x-show-field label="Fully Vaccinated" :value="$patient->fully_covid_19_vaccinated ? 'Yes' : 'No'" />
                            <div class="mb-3">
                                <label class="form-label"><strong>Vaccination Note</strong></label>
                                <p class="mb-0 text-muted">{{ $patient->covid_19_vaccination_note ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        {{-- Doctor Information --}}
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="card border-start border-primary shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-md text-primary me-2"></i><strong>Doctor Information</strong>
                    </h5>
                </div>

                <div class="card-body">
                    @php
                    $doctorTypes = [
                    'Primary Doctor' => $patient->doctor,
                    'Referral Doctor' => $patient->referralDoctor ?? null,
                    'Other Doctor' => $patient->otherDoctor ?? null,
                    'Solicitor Doctor' => $patient->solicitorDoctor ?? null,
                    ];
                    @endphp

                    @foreach($doctorTypes as $label => $doc)
                    <div class="border rounded p-3 mb-4 position-relative bg-light-subtle">
                        <span class="badge bg-primary position-absolute top-0 start-0 rounded-0 rounded-end px-3 py-2">{{ $label }}</span>

                        @if($doc)
                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold mb-0"><i class="fas fa-user me-1 text-secondary"></i>Name</label>
                                <p class="text-dark">{{ 'Dr. ' . $doc->name }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold mb-0"><i class="fas fa-envelope me-1 text-secondary"></i>Email</label>
                                <p class="text-dark">{{ $doc->email ?? '—' }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold mb-0"><i class="fas fa-phone me-1 text-secondary"></i>Phone</label>
                                <p class="text-dark">{{ $doc->phone ?? '—' }}</p>
                            </div>
                        </div>
                        @else
                        <div class="text-muted fst-italic mt-3">No {{ strtolower($label) }} assigned.</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


        {{-- Invoice or Contact --}}
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="card border-start border-info shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-invoice-dollar text-info me-2"></i>Insurance / Invoice Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($patient->insurance)
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Code:</label>
                            <p class="text-muted mb-0">{{ $patient->insurance->code }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Email:</label>
                            <p class="text-muted mb-0">{{ $patient->insurance->email }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Phone:</label>
                            <p class="text-muted mb-0">{{ $patient->insurance->contact }}</p>
                        </div>
                    </div>
                    @else
                    <p class="text-muted">No insurance information available.</p>
                    @endif
                </div>
            </div>
        </div>


        {{-- Notes --}}
        <div class="tab-pane fade show p-3" id="note" role="tabpanel" aria-labelledby="note-tab">
            <h5 class="mb-3"><i class="fas fa-notes-medical text-primary me-2"></i>Patient Notes</h5>

            @if($patient->notes->isNotEmpty())
            <div class="timeline">
                @foreach($patient->notes as $index => $note)
                <div class="timeline-item mb-4 position-relative ps-4 border-start border-3 border-primary">
                    <div class="mb-1 small text-muted">
                        <i class="far fa-clock me-1"></i>{{ optional($note->created_at)->format('d M Y, h:i A') }}
                    </div>
                    <h6 class="mb-1">
                        <span class="badge bg-secondary me-2">{{ ucfirst($note->method) }}</span>
                        {{ $note->completed ? '✅ Completed' : '⏳ Pending' }}
                    </h6>
                    <p class="mb-0">{{ $note->notes }}</p>
                </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i> No notes available for this patient.
            </div>
            @endif
        </div>


        {{-- Physical Exams Notes --}}
        <div class="tab-pane fade show p-3" id="physical-notes" role="tabpanel" aria-labelledby="physical-notes-tab">
            <h5 class="mb-3"><i class="fas fa-stethoscope me-2 text-primary"></i>Physical Exam Records</h5>

            @if($patient->physicalNotes->isNotEmpty())
            <div class="row row-cols-1 row-cols-md-2 g-3">
                @foreach($patient->physicalNotes as $index => $note)
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="card-title text-secondary">
                                <i class="fas fa-file-medical-alt me-1"></i> Entry #{{ $index + 1 }}
                            </h6>
                            <p class="card-text">{{ $note->physical_notes }}</p>
                        </div>
                        <div class="card-footer bg-light text-muted small">
                            <i class="far fa-clock me-1"></i>
                            {{ optional($note->created_at)->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-1"></i> No physical exams available for this patient.
            </div>
            @endif
        </div>

        {{-- History Notes --}}
        <div class="tab-pane fade" id="history-notes" role="tabpanel" aria-labelledby="history-notes-tab">
            <div class="mt-3">
                @forelse($patient->histories as $note)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="card-title mb-1">
                            <i class="fas fa-history text-primary me-1"></i>
                            History Entry #{{ $loop->iteration }}
                        </h6>
                        <p class="card-text">{{ $note->history_notes }}</p>
                        <small class="text-muted">Created on: {{ $note->created_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>
                @empty
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-1"></i> No history notes available.
                </div>
                @endforelse
            </div>
        </div>

        <div class="tab-pane fade" id="audio" role="tabpanel" aria-labelledby="audio-tab">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-info mb-0">
                    <i class="fas fa-microphone-alt me-2"></i>Audio Recordings
                </h5>
                <a href="{{ route('patients.audio.create',$patient->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Add Recording
                </a>
            </div>

            @if($patient->audio->isEmpty())
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-1"></i>No audio recordings available.
            </div>
            @else
            <div class="row g-3">
                @foreach($patient->audio as $audio)
                <div class="col-md-6">
                    <div class="card shadow-sm border border-light rounded-3 h-100 position-relative">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar-alt text-muted me-2"></i>
                                <small class="text-muted">{{ $audio->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                            {{-- <div class="mb-2 text-muted small">
                                <i class="fas fa-user-md me-1 text-primary"></i>
                                <span><strong>Doctor:</strong> Dr. {{ $audio->doctor->name ?? 'N/A' }}</span>
                            </div> --}}
                            <audio controls class="w-100 rounded">
                                <source src="{{ asset_url($audio->file_path) }}" type="audio/webm">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                        {{-- <div class="position-absolute top-0 end-0 p-2">
                            <a href="{{ route('audios.edit', $audio->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Recording">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div> --}}
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
 </div>
</div>
@endsection

