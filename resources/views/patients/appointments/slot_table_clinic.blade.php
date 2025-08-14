@php
use Carbon\Carbon;
@endphp

@foreach($slots as $time)
@php
    $appointmentsForSlot = $appointments->filter(function ($apt) use ($time) {
        return \Illuminate\Support\Str::substr($apt->start_time, 0, 5) === $time;
    });
    $count = $appointmentsForSlot->count();
@endphp

@if($count > 0)
@foreach($appointmentsForSlot as $index => $appointment)
@php
    $user = auth()->user();
    $isSuperAdmin =( $user->hasRole('superadmin') && $flag == 1);
    $isPatientUserEditingOwnAppointment = ($user->hasRole('patient') && $appointment->patient_id === $user->userable_id && $flag == 1);
    $isCurrentPatient = ($user->hasRole('superadmin') && isset($patient) && $appointment->patient->id === $patient->id);
@endphp
<tr 
    data-appointment-id="{{ $appointment->id }}"
    data-time-slot="{{ $time }}"
    @if($isPatientUserEditingOwnAppointment || $isCurrentPatient || $isSuperAdmin)
        ondragstart="onDragStart(event)"
        ondrop="onDrop(event)"
        ondragover="onDragOver(event)"
        draggable="true"
    @endif

    class="{{ $appointment->appointmentType ? 'appointment-' . strtolower(str_replace(' ', '_', $appointment->appointmentType->value)) : '' }}">

    <td>{{ $time }}</td>    
    <td>{{ $appointment->appointmentType->value ?? '-' }}</td>
    <td>
        <a target="_blank" rel="noopener noreferrer" href="{{ route('tasks.tasks.index', ['patient' => $appointment->patient->id]) }}">
        <div class="align-items-center gap-2 d-flex">
        @if ($patient->patient_picture)
            <img src="{{ asset('storage/' . $appointment->patient->patient_picture) }}"
                 alt="Patient Picture"
                 class="rounded-circle"
                 width="40" height="40">
        @else
            <div class="rounded-circle bg-secondary d-inline-block text-white text-center" style="width: 40px; height: 40px; line-height: 40px;">
                <i class="fa-solid fa-user"></i>
            </div>
        @endif
        {{ $appointment->patient->full_name }}
        </div>
    </a>
    </td>
    <td>{{ format_date($appointment->patient->dob ?? '') }}</td>
    <td>{{ $appointment->appointmentStatus->value ?? '-' }}</td>
    <td>{{ $appointment->appointment_note ?? '' }}</td>
    <td>
        <div class="dropdown">
            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Actions
            </button>
            <ul class="dropdown-menu" style="z-index: 1055;">
                @if($isPatientUserEditingOwnAppointment || $isCurrentPatient || $isSuperAdmin)
                <li>
                    <a href="javascript:void(0)" 
                        class="dropdown-item text-success edit-appointment" 
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
                        <i class="fa fa-pencil-square"></i> Edit Appointment
                    </a>
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                        onclick="deleteAppointment({{ $appointment->id }},{{ $appointment->patient->id }},0)">
                        <i class="fa fa-trash"></i> Delete Appointment
                    </a>
                </li>
                <li>
                    <a class="dropdown-item text-primary" href="javascript:void(0)"
                    onclick="openStatusModal({{ $appointment->id }},{{ $appointment->patient->id }}, '{{ $appointment->appointment_status }}')">
                        <i class="fa fa-sync-alt"></i> Change Status
                    </a>
                </li>
                @endif 
                <li>
                    <a class="dropdown-item" target="_blank" rel="noopener noreferrer" href="{{ route('patients.edit', $appointment->patient->id) }}">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Patient
                    </a>
                </li>
                <li>
                    <a class="dropdown-item"
                        href="">
                        <i class="fas fa-credit-card"></i> Take Payment
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" target="_blank" rel="noopener noreferrer"
                        href="{{ route('recalls.recalls.create', ['patient' => $appointment->patient->id]) }}">
                        <i class="fas fa-bell"></i> Add Recall
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" target="_blank" rel="noopener noreferrer" href="{{ route('sms.index', ['patient' => $appointment->patient->id]) }}">
                        <i class="fas fa-sms"></i> Send SMS
                    </a>
                </li>
            </ul>
        </div>
    </td>

</tr>
@endforeach
@else
<tr 
    data-time-slot="{{ $time }}"
    ondrop="onDrop(event)"
    ondragover="onDragOver(event)">
    <td>{{ $time }}</td>
    <td class="text-muted"></td>
    <td class="text-muted"></td>
    <td class="text-muted"></td>
    <td class="text-muted"></td>
    <td class="text-muted"></td>
    <td>
        <button class="btn btn-sm btn-primary" onclick="bookSlot('{{ $time }}')">Book</button>
    </td>
</tr>
@endif
@endforeach