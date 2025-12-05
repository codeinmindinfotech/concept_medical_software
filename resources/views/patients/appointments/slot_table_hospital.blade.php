@if ($isOpen == 0)
<tr>
    <td colspan="8">
        <div class="alert alert-warning d-flex align-items-center justify-content-center mb-0 py-4 rounded-3 shadow-sm" role="alert" id="close_clinic">
            <i class="fas fa-exclamation-triangle me-2 fs-5 text-warning"></i>
            <strong class="me-1">Clinic Closed:</strong> The hospital is closed on this date.
        </div>
    </td>
</tr>

@else
    @foreach ($appointments as $appointment)
        @php
            $typeClass = $appointment->appointmentType
                ? 'appointment-' . str_replace(' ', '_', strtolower($appointment->appointmentType->value))
                : 'appointment-default';

            $user = current_user(); // or auth()->user()
            $isSuperAdmin = (($user->hasRole('superadmin') || $user->hasRole('manager')) && $flag == 1);
            $isPatientUserEditingOwnAppointment = ((getCurrentGuard() == 'patient') && $appointment->patient_id === $user->id);
            $isCurrentPatient = (($user->hasRole('superadmin') || $user->hasRole('manager')) && isset($patient) && optional($appointment->patient)->id === optional($patient)->id);
        @endphp
     <tr class="align-middle">
        <td class="fw-bold text-primary">{{ format_time($appointment->start_time ?? '') }}</td>
        
        <td>
            <a target="_blank" class="text-decoration-none text-dark fw-semibold" href="{{ guard_route('chargecodes.show', optional($appointment->procedure)->id) }}">
                {{ optional($appointment->procedure)->code ?? '-' }}
            </a>
        </td>
        
        <td>
            <a target="_blank" class="text-decoration-none text-dark fw-semibold" href="{{ guard_route('tasks.index', ['patient' => optional($appointment->patient)->id]) }}">
                <div class="align-items-center gap-2 d-flex">
                    @if (optional($appointment->patient)->patient_picture)
                        <img src="{{ asset('storage/' . $appointment->patient->patient_picture) }}"
                             alt="Patient Picture"
                             class="rounded-circle"
                             width="40" height="40">
                    @else
                        <div class="rounded-circle bg-secondary d-inline-block text-white text-center"
                             style="width: 40px; height: 40px; line-height: 40px;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    @endif
                    {{ optional($appointment->patient)->full_name ?? '-' }}
                </div>
            </a>
        </td>
        
        <td>{{ optional($appointment->patient)->dob ? format_date($appointment->patient->dob) : '-' }}</td>
        
        <td>
            <span class="badge bg-light border text-dark">
                {{ optional($appointment->appointmentStatus)->value ?? '-' }}
            </span>
        </td>
        
        <td class="text-muted small appointment-note">{{ $appointment->appointment_note ?? '-' }}</td>
        
        <td>
            <button class="btn btn-sm btn-outline-success" 
                    data-bs-toggle="modal" 
                    data-bs-target="#whatsAppModal"
                    data-appointment-id="{{ $appointment->id }}"
                    data-patient-name="{{ optional($appointment->patient)->full_name ?? '' }}"
                    data-patient-phone="{{ optional($appointment->patient)->phone_number ?? '' }}"
                    data-appointment-time="{{ format_time($appointment->start_time ?? '') }}">
                <i class="fab fa-whatsapp"></i> Send Message
            </button>
        </td>
        
        <td>
            <div class="dropdown">
                <button class="btn btn-sm btn-light border dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    @if($isPatientUserEditingOwnAppointment || $isCurrentPatient || $isSuperAdmin)
                        <li>
                            <a class="dropdown-item text-success edit-hospital-appointment"
                               href="javascript:void(0)"
                               data-id="{{ $appointment->id }}"
                               data-consultant="{{ $appointment->patient->consultant->name }}"
                               data-clinic_id="{{ $appointment->clinic_id }}"
                               data-type="{{ $appointment->appointment_type }}"
                               data-date="{{ $appointment->appointment_date }}"
                               data-admission_date="{{ $appointment->admission_date }}"
                               data-start="{{ format_time($appointment->start_time) }}"
                               data-operation_duration="{{ $appointment->operation_duration }}"
                               data-ward="{{ $appointment->ward }}"
                               data-admission_time="{{ format_time($appointment->admission_time) }}"
                               data-procedure_id="{{ $appointment->procedure_id }}"
                               data-patient_id="{{ optional($appointment->patient)->id }}"
                               data-patient_name="{{ optional($appointment->patient)->full_name }}"
                               data-allergy="{{ $appointment->allergy }}"
                               data-note="{{ $appointment->appointment_note }}">
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
                               onclick="openStatusModal({{ $appointment->id }},{{ optional($appointment->patient)->id ?? 0 }}, '{{ $appointment->appointment_status }}')">
                                <i class="fa fa-sync-alt"></i> Change Status
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                    @endif
                    <li>
                        <a class="dropdown-item" target="_blank" rel="noopener noreferrer"
                           href="{{ guard_route('patients.edit', optional($appointment->patient)->id ?? 0) }}">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Patient
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#"><i class="fas fa-credit-card"></i> Take Payment</a>
                    </li>
                    <li>
                        <a class="dropdown-item" target="_blank" rel="noopener noreferrer"
                           href="{{ guard_route('recalls.index', ['patient' => optional($appointment->patient)->id ?? 0]) }}">
                            <i class="fas fa-bell"></i> Add Recall
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" target="_blank" rel="noopener noreferrer"
                           href="{{ guard_route('sms.index', ['patient' => optional($appointment->patient)->id ?? 0]) }}">
                            <i class="fas fa-sms"></i> Send SMS
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
    @endforeach

    @if ($appointments->isEmpty())
    <tr class="text-muted fst-italic">
        <td colspan="8" class="text-center">
          <i class="fas fa-hospital me-2 text-secondary"></i>
          No hospital appointments scheduled for this date.
        </td>
    </tr>
    @endif


    <div class="mb-3 justify-content-end p-3 border-bottom" id="manualSlotButton" class="d-flex ">
        <button class="btn btn-sm btn-outline-primary" onclick="openManualBookingModal()">
            <i class="fas fa-plus me-1"></i> Add Manual Slot
        </button>
    </div>
@endif
