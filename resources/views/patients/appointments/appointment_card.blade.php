<div class="card shadow-sm border-0 mb-3 appointment-card" style="border-left: 5px solid {{ $rowColor ?? '#0d6efd' }};">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-start">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="appointment_ids[]" value="{{ $appointment->id }}" id="appt-{{ $appointment->id }}">
            </div>
            <div class="w-100 ms-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-primary">{{ $time }}</span>
                    <span class="badge bg-light border text-dark">{{ $appointment->appointmentStatus->value ?? '-' }}</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    @if($appointment->patient->patient_picture)
                        <img src="{{ asset('storage/' . $appointment->patient->patient_picture) }}" class="rounded-circle me-2" width="40" height="40" alt="Patient">
                    @else
                        <div class="rounded-circle bg-secondary text-white text-center me-2" style="width: 40px; height: 40px; line-height: 40px;">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <div>
                        <a target="_blank" class="text-decoration-none text-dark fw-semibold" href="{{ guard_route('patients.show', ['patient' => $appointment->patient->id]) }}">
                            {{ $appointment->patient->full_name }}
                        </a>
                        <div class="text-muted small">DOB: {{ format_date($appointment->patient->dob ?? '') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="badge bg-info-subtle text-dark px-3">{{ $appointment->appointmentType->value ?? '-' }}</span>
                    <span class="text-muted small"><i class="bi bi-chat-dots me-1"></i>{{ $appointment->appointment_note ?? '' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
