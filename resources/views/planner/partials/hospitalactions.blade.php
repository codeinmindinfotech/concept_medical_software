<div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        Actions
    </button>
    <ul class="dropdown-menu" style="z-index: 1055;">
        <li>
            <a class="dropdown-item text-success edit-hospital-appointment"
                href="javascript:void(0)"
                data-id="{{ $appointment->id }}"
                data-action="{{ route('hospital_appointments.store', ['patient' => $appointment->patient->id]) }}" 
                data-type="{{ $appointment->appointment_type }}"
                data-date="{{ $appointment->appointment_date }}"
                data-admission_date="{{ $appointment->admission_date }}"
                data-start="{{ format_time($appointment->start_time) }}"
                data-operation_duration="{{ $appointment->operation_duration }}"
                data-ward="{{ $appointment->ward }}"
                data-admission_time="{{ format_time($appointment->admission_time) }}"
                data-procedure_id="{{ $appointment->procedure_id }}"
                data-allergy="{{ $appointment->allergy }}"
                data-clinic_id="{{ $appointment->clinic_id }}"
                data-note="{{ $appointment->appointment_note }}">
                <i class="fa fa-pencil-square"></i> Edit Appointment
            </a>
        </li>
        <li>
            <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteAppointment({{ $appointment->id }},{{ $appointment->patient->id }})">
                <i class="fa fa-trash"></i> Delete Appointment
            </a>
        </li>
        <li>
            <a class="dropdown-item text-primary" href="javascript:void(0)" onclick="openStatusModal({{ $appointment->id }},{{ $appointment->patient->id }}, '{{ $appointment->appointment_status }}')">
                <i class="fa fa-sync-alt"></i> Change Status
            </a>
        </li>
        <li>
            <a class="dropdown-item" target="_blank" href="{{ route('patients.edit', $appointment->patient->id) }}">
                <i class="fa-solid fa-pen-to-square"></i> Edit Patient
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#"><i class="fas fa-credit-card"></i> Take Payment</a>
        </li>
        <li>
            <a class="dropdown-item" target="_blank" href="{{ route('recalls.recalls.create', ['patient' => $appointment->patient->id]) }}">
                <i class="fas fa-bell"></i> Add Recall
            </a>
        </li>
        <li>
            <a class="dropdown-item" target="_blank" href="{{ route('sms.index', ['patient' => $appointment->patient->id]) }}">
                <i class="fas fa-sms"></i> Send SMS
            </a>
        </li>
    </ul>
</div>
