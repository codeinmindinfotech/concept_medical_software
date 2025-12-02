<table class="table table-hover table-center mb-0" id="PatientApt">
    <thead>
        <tr>
            <th>#</th>
            <th>Type</th>
            <th>Start Time</th>
            <th>Appointment Date</th>
            <th>Note</th>
            <th>Location</th>
            <th>Status</th>
            <th>Action</th>
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
                class="text-success "
                style="border: solid; border-color: {{ $borderColor }};"
                
            >
                <td>{{ $index + 1 }}</td>
                <td>{{ ucfirst($apt->appointmentType->value ?? '-') }}</td>
                <td>{{ format_time($apt->start_time) }}</td>
                <td>{{ format_date($apt->appointment_date) }}</td>
                <td>{{$apt->appointment_note}}</td>
                <td><div class="align-items-center gap-2 d-flex">
                    <div class="me-2 rounded-circle"
                    style="width:12px; height:12px; background:{{ $apt->clinic->color }};"></div>
                        {{ $apt->clinic->name }}
                </div>
                </td>
                <td>{{$apt->appointmentStatus->value??''}}</td>
                <td>
                    <a href="javascript:void(0)" class="btn btn-sm bg-primary-light {{ $trClass }}" title="Edit" {!! $attributeString !!}>
                        <i class="fe fe-pencil"></i> Edit
                    </a>

                    <a href="javascript:void(0)" title="Delete" class="btn bg-danger-light" onclick="deleteAppointment({{ $apt->id }}, {{ $apt->patient->id }}, 1)">
                        <i class="fe fe-trash"></i> Delete
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>