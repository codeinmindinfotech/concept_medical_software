
@foreach($slots as $time)
    @php
        $appointmentsForSlot = $appointments->filter(fn($apt) => \Illuminate\Support\Str::substr($apt->start_time, 0, 5) === $time);
    @endphp

    @if($appointmentsForSlot->count())
        @foreach($appointmentsForSlot as $appointment)
            @php
                $user = auth()->user();
                $isSuperAdmin =(($user->hasRole('superadmin') || $user->hasRole('manager')) && $flag == 1);
                $isPatientUserEditingOwnAppointment = ((getCurrentGuard() == 'patient') && $appointment->patient_id === $user->id);
                $isCurrentPatient = (($user->hasRole('superadmin') || $user->hasRole('manager')) && isset($patient) && $appointment->patient->id === $patient->id);
        
                // $isSuperAdmin = ($user->hasRole('superadmin') || $user->hasRole('manager')) && $flag == 1;
                // $isPatientUserEditingOwnAppointment = $user->hasRole('patient') && $appointment->patient_id === $user->userable_id;
                // $isCurrentPatient = $user->hasRole('superadmin') && isset($patient) && $appointment->patient->id === $patient->id;

                $type = strtolower($appointment->appointmentType->value ?? '');
                $rowClass =  $appointment->appointmentType ? 'appointment-' . strtolower(str_replace(' ', '_', $appointment->appointmentType->value)) : '' ;

            @endphp

            <tr class="align-middle"
                data-appointment-id="{{ $appointment->id }}"
                data-time-slot="{{ $time }}"
                @if($isSuperAdmin || $isPatientUserEditingOwnAppointment || $isCurrentPatient)
                    draggable="true"
                    ondragstart="onDragStart(event)"
                    ondrop="onDrop(event)"
                    ondragover="onDragOver(event)"
                @endif
            >
                <td class="fw-bold text-primary">{{ $time }}{{$user->hasRole('manager')}}</td>
                <td>
                    <span class="badge {{ $rowClass }} text-dark px-3">
                        {{ $appointment->appointmentType->value ?? '-' }}
                    </span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        @if ($appointment->patient->patient_picture)
                            <img src="{{ asset('storage/' . $appointment->patient->patient_picture) }}"
                                    class="rounded-circle me-2" width="40" height="40" alt="Patient">
                        @else
                            <div class="rounded-circle bg-secondary text-white text-center me-2"
                                    style="width: 40px; height: 40px; line-height: 40px;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <a target="_blank"
                            class="text-decoration-none text-dark fw-semibold"
                            href="{{guard_route('tasks.tasks.index', ['patient' => $appointment->patient->id]) }}">
                            {{ $appointment->patient->full_name }}
                        </a>
                    </div>
                </td>
                <td>{{ format_date($appointment->patient->dob ?? '') }}</td>
                <td>
                    <span class="badge bg-light border text-dark">
                        {{ $appointment->appointmentStatus->value ?? '-' }}
                    </span>
                </td>
                <td >
                    <span class="text-muted small appointment-note">
                        {{ $appointment->appointment_note ?? '' }}
                    </span>
                </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            @if($isSuperAdmin || $isPatientUserEditingOwnAppointment || $isCurrentPatient)
                                <li>
                                    <a href="javascript:void(0)" class="dropdown-item edit-appointment"
                                        data-id="{{ $appointment->id }}"
                                        data-dob="{{ format_date($appointment->patient->dob) }}"
                                        data-patient_id="{{ $appointment->patient->id }}"
                                        data-patient_name="{{ $appointment->patient->full_name }}"
                                        data-type="{{ $appointment->appointment_type }}"
                                        data-date="{{ $appointment->appointment_date }}"
                                        data-start="{{ format_time($appointment->start_time) }}"
                                        data-end="{{ $appointment->end_time }}"
                                        data-need="{{ $appointment->patient_need }}"
                                        data-note="{{ $appointment->appointment_note }}">
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
                                <a class="dropdown-item" target="_blank" href="{{guard_route('recalls.recalls.create', ['patient' => $appointment->patient->id]) }}">
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
                </td>
            </tr>
        @endforeach
    @else
        <tr ondrop="onDrop(event)" ondragover="onDragOver(event)" data-time-slot="{{ $time }}">
            <td class="fw-bold text-primary">{{ $time }}</td>
            <td colspan="5">
                <div class="d-flex align-items-center text-muted">
                    <i class="fas fa-calendar-check me-2 text-success"></i>
                    <span class="fst-italic">This time slot is available. Book now!</span>
                </div>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="bookSlot('{{ $time }}')">
                    <i class="fas fa-plus"></i> Book
                </button>
            </td>
        </tr>
    @endif
@endforeach
