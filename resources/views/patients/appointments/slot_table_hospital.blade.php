@if ($isOpen == 0)
<div class="alert alert-warning d-flex align-items-center justify-content-center text-center mb-3 py-4 rounded shadow-sm" role="alert" id="close_clinic">
    <i class="fas fa-exclamation-triangle me-2 fs-5 text-warning"></i>
    <strong>Clinic Closed:</strong> The hospital is closed on this date.
</div>
@else

<div class="row g-3">
    @forelse ($appointments as $appointment)
        @php
            $typeClass = $appointment->appointmentType
                ? 'appointment-' . str_replace(' ', '_', strtolower($appointment->appointmentType->value))
                : 'appointment-default';

            $user = current_user();
            $isclinic = (getCurrentGuard() == "clinic");
            $isSuperAdmin = (($user->hasRole('superadmin') || $user->hasRole('manager')) && $flag == 1);
            $isPatientUserEditingOwnAppointment = ((getCurrentGuard() == 'patient') && $appointment->patient_id === $user->id);
            $isCurrentPatient = (($user->hasRole('superadmin') || $user->hasRole('manager')) && isset($patient) && optional($appointment->patient)->id === optional($patient)->id);
        @endphp

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 {{ $typeClass }}">
                <div class="card-body d-flex flex-column">

                    <!-- Patient Info -->
                    <div class="d-flex align-items-center mb-2">
                        @if(optional($appointment->patient)->patient_picture)
                            <img src="{{ asset('storage/' . $appointment->patient->patient_picture) }}" alt="Patient Picture" class="rounded-circle me-2" width="50" height="50">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width:50px;height:50px;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0">{{ optional($appointment->patient)->full_name ?? '-' }}</h6>
                            <small class="text-muted">{{ optional($appointment->patient)->dob ? format_date($appointment->patient->dob) : '-' }}</small>
                        </div>
                    </div>

                    <!-- Appointment Info -->
                    <p class="mb-1"><strong>Time:</strong> {{ format_time($appointment->start_time ?? '-') }}</p>
                    <p class="mb-1"><strong>Clinic:</strong> {{ optional($appointment->clinic)->name ?? '-' }}</p>
                    <p class="mb-1"><strong>Procedure:</strong> {{ optional($appointment->procedure)->code ?? '-' }}</p>
                    <p class="mb-1"><strong>Admission Date:</strong> {{ $appointment->admission_date ?? '-' }}</p>
                    <p class="mb-1"><strong>Admission Time:</strong> {{ format_time($appointment->start_time ?? '-') }}</p>
                    <p class="mb-1"><strong>Operation Duration:</strong> {{ $appointment->operation_duration ?? '-' }} mins</p>
                    <p class="mb-1"><strong>Allergy:</strong> {{ $appointment->allergy ?? '-' }}</p>
                    <p class="mb-1 text-muted small">{{ $appointment->appointment_note ?? '-' }}</p>

                    <!-- Actions -->
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <button class="btn btn-sm btn-outline-success" 
                                data-bs-toggle="modal" 
                                data-bs-target="#whatsAppModal"
                                data-appointment-id="{{ $appointment->id }}"
                                data-patient-name="{{ optional($appointment->patient)->full_name ?? '' }}"
                                data-patient-phone="{{ optional($appointment->patient)->phone_number ?? '' }}"
                                data-appointment-time="{{ format_time($appointment->start_time ?? '') }}">
                            <i class="fab fa-whatsapp"></i>
                        </button>

                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                @if($isPatientUserEditingOwnAppointment || $isCurrentPatient || $isSuperAdmin  || $isclinic)
                                    <li>
                                        <a class="dropdown-item text-success edit-hospital-appointment"
                                           href="javascript:void(0)"
                                           data-id="{{ $appointment->id }}">
                                            <i class="fa fa-pencil-square"></i> Edit Appointment
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="javascript:void(0)"
                                           onclick="deleteAppointment({{ $appointment->id }},{{ optional($appointment->patient)->id ?? 0 }},0)">
                                            <i class="fa fa-trash"></i> Delete Appointment
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-primary" href="javascript:void(0)"
                                           onclick="openStatusModal({{ $appointment->id }}, {{ $appointment->patient->id }}, '{{ $appointment->appointment_status }}')">
                                            <i class="fa fa-sync-alt me-2"></i> Change Status
                                        </a>
                                    </li>
                                @endif
                                <li><a class="dropdown-item" target="_blank" href="{{ guard_route('patients.edit', optional($appointment->patient)->id ?? 0) }}">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit Patient
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted fst-italic py-4">
            <i class="fas fa-hospital me-2 text-secondary"></i>
            No hospital appointments scheduled for this date.
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-end my-3">
    <button class="btn btn-sm btn-outline-primary" onclick="openManualBookingModal()">
        <i class="fas fa-plus me-1"></i> Add Manual Slot
    </button>
</div>

@endif
