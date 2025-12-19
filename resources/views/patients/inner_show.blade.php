<ul class="nav nav-tabs nav-tabs-bottom">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" role="tab">
            <i class="fas fa-user me-1"></i>Patient
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab">
            <i class="fas fa-user-md me-1"></i>Doctor
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" role="tab">
            <i class="fas fa-file-invoice-dollar me-1"></i>Insurance
        </a>
    </li>
</ul>

<div class="tab-content" id="myTabContent">

    {{-- Patient Information --}}
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="row g-4">
            {{-- ▶ Consultant Information --}}
            <div class="col-md-6">
                <div class="card border-start border-warning shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-stethoscope me-2 text-warning"></i>Consultant Information
                        </h5>
                    </div>
                    <div class="card-body row g-3">
                        <x-show-field label="Consultant" :value="$patient->consultant->name ?? '-'" />
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
                        <div class="col-12">
                            <label class="form-label"><strong>Address</strong></label>
                            <p class="mb-0 text-muted">{{ $patient->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

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

            <div class="col-6">
                <div class="card border-start border-primary shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-friends me-2 text-primary"></i>Next Of Kin Info
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ([
                        'Next Of Kin' => $patient->next_of_kin,
                        'Contact No' => $patient->kin_contact_no,
                        'Email' => $patient->kin_address,
                        'Address' => $patient->kin_email,
                        'Relation' => $patient->relationship,
                        ] as $label => $value)
                        <div class="mb-3">
                            <label class="form-label"><strong>{{ $label }}</strong></label>
                            <p class="mb-0 text-muted">{{ $value ?? '-' }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ▶ Emergency & Medical Info --}}
            <div class="col-7">
                <div class="card border-start border-danger shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-briefcase-medical me-2 text-danger"></i>Emergency & Medical Info
                        </h5>
                    </div>
                    <div class="card-body row g-3">
                        @foreach ([
                        'Emergency Contact' => $patient->emergency_contact,
                        'Medical History / Notes' => $patient->medical_history,
                        'Referral Reason' => $patient->referral_reason,
                        'Symptoms' => $patient->symptoms,
                        'Patient Needs' => $patient->patient_needs,
                        'Allergies' => $patient->allergies,
                        'Diagnosis' => $patient->diagnosis
                        ] as $label => $value)
                        <x-show-field 
                        label="{{ $label }}" 
                        :value="$value ?? '-'"
                        />  
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- ▶ Patient Status & Consent --}}
            <div class="col-md-5 mt-4">
                <div class="card border-start border-success shadow-sm">
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
                    <x-show-field label="Insurance Provider:" :value="$patient?->insurance->code" col="4" />
                    <x-show-field label="Email:" :value="$patient?->insurance->email" col="4" />
                    <x-show-field label="Phone:" :value="$patient?->insurance->contact" col="4" />
                    <x-show-field label="Insurance Plan:" :value="$patient?->insurance_plan" col="4" />
                    <x-show-field label="Policy Number:" :value="$patient?->policy_no" col="4" />
                </div>

                @else
                <p class="text-muted">No insurance information available.</p>
                @endif
            </div>
        </div>
    </div>

</div>