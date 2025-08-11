<div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        Actions
    </button>
    <ul class="dropdown-menu" style="z-index: 1055;">
        <li>
            <a href="javascript:void(0)" 
                class="dropdown-item text-success edit-appointment" 
                data-id="{{ $appointment->id }}"
                data-action="{{ route('patients.appointments.store', ['patient' => $appointment->patient->id]) }}" 
                data-clinic-id="{{ $appointment->clinic_id }}" 
                data-dob="{{ format_date($appointment->patient->dob) }}" 
                data-type="{{ $appointment->appointment_type }}" 
                data-date="{{ $appointment->appointment_date }}" 
                data-start="{{ format_time($appointment->start_time) }}" 
                data-end="{{ $appointment->end_time }}" 
                data-need="{{ $appointment->patient_need }}" 
                data-patient_id="{{ $appointment->patient->id }}"
                data-patient_name="{{ $appointment->patient->full_name }}"
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
