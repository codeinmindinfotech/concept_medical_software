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
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($apts as $index => $apt)
            @php
                $isClinic = $apt->clinic->clinic_type === 'clinic';
                $borderColor = $apt->clinic->color ?? '';
            @endphp
            <tr>
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
                    @if($isClinic)
                        <a href="javascript:void(0)" class="btn bg-primary-light" onclick="fetchAppointmentData({{ $apt->id }})" >
                            <i class="fa fa-pencil-square"></i> Edit
                        </a>
                    @else
                        <a class="btn bg-primary-light text-success " onclick="fetchHospitalAppointmentData({{ $apt->id }})"
                            href="javascript:void(0)">
                            <i class="fa fa-pencil-square"></i> Edit
                        </a>
                    @endif
                    <a class="btn bg-warning" onclick="openStatusModal({{ $apt->id }}, {{ $apt->patient->id }}, {{ $apt->appointment_status }});"
                        href="javascript:void(0)">
                        <i class="fa fa-pencil-square"></i> Status
                    </a>
                    

                    <a href="javascript:void(0)" title="Delete" class="btn bg-danger-light" onclick="deleteAppointment({{ $apt->id }}, {{ $apt->patient->id }}, 1)">
                        <i class="fe fe-trash"></i> Delete
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>