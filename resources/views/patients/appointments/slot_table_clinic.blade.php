<div class="container-fluid py-3">

    @foreach($slots as $time)
        @php
            $appointmentsForSlot = $appointments->filter(fn($apt) => \Illuminate\Support\Str::substr($apt->start_time, 0, 5) === $time);
        @endphp

        <div class="d-flex align-items-start mb-1">
            <!-- Time Column -->
            <div class="text-center me-1" style="width: 80px;">
                <span class="fw-bold text-primary">{{ $time }}</span>
            </div>

            <!-- Appointments Column -->
            <div class="flex-grow-1">
                @if($appointmentsForSlot->count())
                    @foreach($appointmentsForSlot as $appointment)
                        @php
                            $user = auth()->user();
                            $isSuperAdmin = (($user->hasRole('superadmin') || $user->hasRole('manager')) && $flag == 1);
                            $isPatientUserEditingOwnAppointment = ((getCurrentGuard() == 'patient') && $appointment->patient_id === $user->id);
                            $isCurrentPatient = (($user->hasRole('superadmin') || $user->hasRole('manager')) && isset($patient) && $appointment->patient->id === $patient->id);
                            $isclinic = (getCurrentGuard() == "clinic");
                            $rowClass =  $appointment->appointmentType ? 'appointment-' . strtolower(str_replace(' ', '_', $appointment->appointmentType->value)) : '' ;
                        @endphp

                        <div class="d-flex align-items-center mb-1 p-0 border rounded shadow-sm draggable"
                             data-appointment-id="{{ $appointment->id }}"
                             data-time-slot="{{ $time }}"
                             @if($isSuperAdmin || $isPatientUserEditingOwnAppointment || $isCurrentPatient || $isclinic)
                                 draggable="true"
                                 ondragstart="onDragStart(event)"
                                 ondrop="onDrop(event)"
                                 ondragover="onDragOver(event)"
                             @endif
                        >
                            <!-- Appointment Type -->
                            <span class="badge {{ $rowClass }} text-dark me-3 px-3" style="min-width: 120px;">
                                {{ $appointment->appointmentType->value ?? '-' }}
                            </span>

                            <!-- Patient Info -->
                            <div class="d-flex align-items-center me-3 flex-grow-1">
                                @if ($appointment->patient->patient_picture)
                                    <img src="{{ asset('storage/' . $appointment->patient->patient_picture) }}" class="rounded-circle me-2" width="40" height="40" alt="Patient">
                                @else
                                    <div class="rounded-circle bg-secondary text-white text-center me-2" style="width: 40px; height: 40px; line-height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <a target="_blank" class="text-decoration-none fw-semibold" href="{{guard_route('patients.show', ['patient' => $appointment->patient->id]) }}">
                                    {{ $appointment->patient->full_name }}
                                </a>
                            </div>

                            <!-- DOB -->
                            <span class="me-3 small text-muted">{{ format_date($appointment->patient->dob ?? '') }}</span>

                            <!-- Status -->
                            <span class="badge bg-light border text-dark me-3">
                                {{ $appointment->appointmentStatus->value ?? '-' }}
                            </span>

                            <!-- Note -->
                            @if($appointment->appointment_note)
                                <span class="text-muted small me-3">
                                    {{ $appointment->appointment_note }}
                                </span>
                            @endif

                            <!-- WhatsApp Button -->
                            <button class="btn btn-sm btn-outline-success me-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#whatsAppModal"
                                    data-appointment-id="{{ $appointment->id }}"
                                    data-patient-name="{{ $appointment->patient->full_name }}"
                                    data-patient-phone="{{ $appointment->patient->phone }}"
                                    data-appointment-time="{{ $time }}">
                                <i class="fab fa-whatsapp"></i>
                            </button>

                            <!-- Dropdown actions -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    @if($isSuperAdmin || $isPatientUserEditingOwnAppointment || $isCurrentPatient || $isclinic)
                                        <li>
                                            <a href="javascript:void(0)" class="dropdown-item edit-appointment"
                                               data-id="{{ $appointment->id }}">
                                                <i class="fa fa-pen me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="javascript:void(0)"
                                               onclick="deleteAppointment({{ $appointment->id }}, {{ $appointment->patient->id }}, 0)">
                                                <i class="fa fa-trash me-2"></i> Delete
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-primary" href="javascript:void(0)"
                                               onclick="openStatusModal({{ $appointment->id }}, {{ $appointment->patient->id }}, '{{ $appointment->appointment_status }}')">
                                                <i class="fa fa-sync-alt me-2"></i> Change Status
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" target="_blank" href="{{guard_route('patients.edit', $appointment->patient->id) }}">
                                            <i class="fa-solid fa-user-pen me-2"></i> Edit Patient
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-credit-card me-2"></i> Take Payment
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" target="_blank" href="{{guard_route('recalls.create', ['patient' => $appointment->patient->id]) }}">
                                            <i class="fas fa-bell me-2"></i> Add Recall
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" target="_blank" href="{{guard_route('sms.index', ['patient' => $appointment->patient->id]) }}">
                                            <i class="fas fa-sms me-2"></i> Send SMS
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="d-flex align-items-center justify-content-between p-1 border rounded shadow-sm" ondrop="onDrop(event)" ondragover="onDragOver(event)" data-time-slot="{{ $time }}">
                        <span class="text-muted fst-italic">This time slot is available.</span>
                        <button class="btn btn-sm btn-outline-primary p-1" onclick="bookSlot('{{ $time }}')">
                            <i class="fas fa-plus me-1"></i> Book
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endforeach

</div>
