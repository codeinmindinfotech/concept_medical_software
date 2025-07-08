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
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                role="tab">Patient</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                role="tab">Doctor</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button"
                role="tab">Insurance</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="note-tab" data-bs-toggle="tab" data-bs-target="#note" type="button"
                role="tab">Notes</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="physical-notes-tab" data-bs-toggle="tab" data-bs-target="#physical-notes" type="button"
                role="tab">Physical Exams</button>
        </li>
        
    </ul>

    <div class="tab-content border border-top-0 p-3" id="myTabContent">

        {{-- Patient Information --}}
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row g-4">

                {{-- ▶ Personal Information --}}
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><strong>Personal Information</strong></h5>
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
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><strong>Contact Information</strong></h5>
                        </div>
                        <div class="card-body row g-3">
                            <x-show-field label="Phone" :value="$patient->phone" />
                            <x-show-field label="Email" :value="$patient->email" />
                            <x-show-field label="Preferred Contact"
                                :value="$patient->preferredContactMethod->value ?? '-'" />
                            <div class="col-12">
                                <label class="form-label"><strong>Address</strong></label>
                                <p>{{ $patient->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ▶ Emergency & Medical Info --}}
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><strong>Emergency & Medical Info</strong></h5>
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
                                <p>{{ $value ?? '-' }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ▶ Insurance Information --}}
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><strong>Insurance Information</strong></h5>
                        </div>
                        <div class="card-body row g-3">
                            <x-show-field label="Insurance Provider" :value="$patient->insurance->code ?? '-'" />
                            <x-show-field label="Insurance Plan" :value="$patient->insurance_plan" />
                            <x-show-field label="Policy Number" :value="$patient->policy_no" />
                        </div>
                    </div>
                </div>

                {{-- ▶ Patient Status & Consent --}}
                <div class="col-6 mt-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><strong>Patient Status & Consent</strong></h5>
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
                <div class="col-6 mt-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><strong>COVID-19 Vaccination Info</strong></h5>
                        </div>
                        <div class="card-body">
                            <x-show-field label="Vaccination Date" :value="optional($patient->covid_19_vaccination_date)->format('Y-m-d')" />
                            <x-show-field label="Fully Vaccinated"
                                :value="$patient->fully_covid_19_vaccinated ? 'Yes' : 'No'" />
                            <div class="mb-3">
                                <label class="form-label"><strong>Vaccination Note</strong></label>
                                <p>{{ $patient->covid_19_vaccination_note ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Doctor Information --}}
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            @if($patient->doctor)
            <h4>Doctor Details</h4>
            <p><strong>Name:</strong> Dr. {{ $patient->doctor->name }}</p>
            <p><strong>Email:</strong> {{ $patient->doctor->email }}</p>
            <p><strong>Phone:</strong> {{ $patient->doctor->phone }}</p>
            @else
            <p>No doctor assigned.</p>
            @endif
        </div>

        {{-- Invoice or Contact --}}
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <h4>Invoice / Additional Information</h4>
            @if($patient->insurance)
            <h4>Insurance Details</h4>
            <p><strong>Code:</strong> {{ $patient->insurance->code }} </p>
            <p><strong>Email:</strong> {{ $patient->insurance->email }}</p>
            <p><strong>Phone:</strong> {{ $patient->insurance->contact }}</p>
            @else
            <p>No doctor assigned.</p>
            @endif
        </div>

        {{-- Notes --}}
        <div class="tab-pane fade" id="note" role="tabpanel" aria-labelledby="note-tab">
            <h4>Patient Notes</h4>
            @if($patient->notes->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Method</th>
                            <th>Note</th>
                            <th>Completed</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->notes as $index => $note)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ ucfirst($note->method) }}</td>
                            <td>{{ $note->notes }}</td>
                            <td>{{ $note->completed ? 'Yes' : 'No' }}</td>
                            <td>{{ optional($note->created_at)->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p>No notes available for this patient.</p>
            @endif
        </div>

        {{-- Notes --}}
        <div class="tab-pane fade" id="physical-notes" role="tabpanel" aria-labelledby="physical-notes-tab">
            <h4>Physical Exams</h4>
            @if($patient->physicalNotes->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Note</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->physicalNotes as $index => $note)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $note->physical_notes }}</td>
                            <td>{{ optional($note->created_at)->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p>No physical Exams available for this patient.</p>
            @endif
        </div>




    </div>

</div>
@endsection