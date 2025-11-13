<table class="table table-hover align-middle text-nowrap" id="PatientApt">
    <thead>
        <tr>
            <th>#</th>
            <th>Type</th>
            <th>Start Time</th>
            <th>Appointment Date</th>
            <th>Note</th>
            <th>Location</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($apts as $index => $apt)
            @php
                $isClinic = $apt->clinic->clinic_type === 'clinic';
                $borderColor = $apt->clinic->color ?? '';
                $trClass = $isClinic ? 'edit-appointment' : 'edit-hospital-appointment';

                // Build the attributes string
                $attributes = [
                    'data-id' => $apt->id,
                    'data-type' => $apt->appointment_type,
                    'data-date' => $apt->appointment_date,
                    'data-start' => format_time($apt->start_time),
                    'data-consultant' => $apt->patient->consultant->name,
                    'data-note' => $apt->appointment_note,
                    'data-clinic_id' => $apt->clinic_id,
                ];

                if ($isClinic) {
                    $attributes += [
                        'data-dob' => format_date($apt->patient->dob),
                        'data-patient_id' => $apt->patient->id,
                        'data-patient_name' => $apt->patient->full_name,
                        'data-end' => $apt->end_time,
                        'data-need' => $apt->patient_need,
                    ];
                } else {
                    $attributes += [
                        'data-action' => guard_route('hospital_appointments.store', ['patient' => $apt->patient->id]),
                        'data-admission_date' => $apt->admission_date,
                        'data-operation_duration' => $apt->operation_duration,
                        'data-ward' => $apt->ward,
                        'data-admission_time' => format_time($apt->admission_time),
                        'data-procedure_id' => $apt->procedure_id,
                        'data-patient_id' => optional($apt->patient)->id,
                        'data-patient_name' => optional($apt->patient)->full_name,
                        'data-allergy' => $apt->allergy,
                    ];
                }

                // Convert to HTML attribute string
                $attributeString = collect($attributes)
                    ->map(fn($val, $key) => $key . '="' . e($val) . '"')
                    ->implode(' ');
            @endphp

            <tr
                class="text-success {{ $trClass }}"
                style="border: solid; border-color: {{ $borderColor }};"
                {!! $attributeString !!}
            >
                <td>{{ $index + 1 }}</td>
                <td>{{ ucfirst($apt->appointmentType->value ?? '-') }}</td>
                <td>{{ format_time($apt->start_time) }}</td>
                <td>{{ format_date($apt->appointment_date) }}</td>
                <td>{{$apt->appointment_note}}</td>
                <td class="d-flex align-items-center" ><div class="me-2 rounded-circle"
                    style="width:12px; height:12px; background:{{ $apt->clinic->color }};"></div>
                        <span>{{ $apt->clinic->name }}</span>
                    </div>
                </td>
                <td>{{$apt->appointmentStatus->value??''}}</td>
            </tr>
        @endforeach
    </tbody>
</table>