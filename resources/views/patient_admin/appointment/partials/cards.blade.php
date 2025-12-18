@foreach ($appointments as $appointment)
@php
$clinic_type = strtolower($appointment->clinic->clinic_type);
@endphp
<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
    <div class="appointment-wrap appointment-grid-wrap">
        <ul>
            <li>
                <div class="appointment-grid-head">
                    <div class="patinet-information">

                        @if ($appointment->patient->patient_picture)
                        <img src="{{ asset('storage/' . $appointment->patient->patient_picture) }}" class="rounded-circle me-2" width="40" height="40">
                        @else
                        <div class="rounded-circle bg-secondary text-white text-center me-2" style="width:40px;height:40px;line-height:40px;">
                            <i class="fas fa-user"></i>
                        </div>
                        @endif

                        <div class="patient-info">
                            <p>#Apt{{ $appointment->id }}</p>
                            <h6>{{ $appointment->patient->doctor->name ?? 'No Doctor Assigned' }}</h6>
                            <p class="visit">{{ $appointment->appointmentType->value ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($clinic_type === 'clinic')
                    <div class="grid-user-msg ">
                        <span class="hospital-icon">
                            <a href="#">
                                <i class="isax isax-building-3 "></i>
                            </a>
                        </span>
                    </div>
                    @elseif($clinic_type === 'hospital')
                    <div class="grid-user-msg btn-warning">
                        <span class="hospital-icon">
                            <a href="#">
                                <i class="isax isax-hospital text-white"></i>
                            </a>
                        </span>
                    </div>
                    @endif

                </div>
            </li>

            <li class="appointment-info">
                <p><i class="isax isax-calendar5"></i> {{ format_date($appointment->appointment_date) }} - {{ format_time($appointment->start_time) }}</p>
                <p><i class="isax isax-messages-25"></i> {{ ($appointment->appointment_note) }}</p>
            </li>

            <li class="appointment-action">
                <ul>
                    {{-- <li><a href="#"><i class="isax isax-eye4"></i></a></li> --}}
                    <li>
                        @if($clinic_type === 'clinic')
                        <a href="javascript:void(0)" class="btn btn-warning  edit-appointment" data-id="{{ $appointment->id }}" data-clinic_id="{{ $appointment->clinic_id }}" data-consultant="{{ $appointment->patient->consultant->name }}" data-dob="{{ format_date($appointment->patient->dob) }}" data-patient_id="{{ $appointment->patient->id }}" data-patient_name="{{ $appointment->patient->full_name }}" data-type="{{ $appointment->appointment_type }}" data-date="{{ $appointment->appointment_date }}" data-start="{{ format_time($appointment->start_time) }}" data-end="{{ $appointment->end_time }}" data-need="{{ $appointment->patient_need }}" data-note="{{ $appointment->appointment_note }}">
                            <i class="isax isax-edit-2"></i>
                        </a>
                        @else
                        <a class="btn btn-primary edit-hospital-appointment" href="javascript:void(0)" data-id="{{ $appointment->id }}" data-action="{{guard_route('hospital_appointments.store', ['patient' => $appointment->patient->id]) }}" data-consultant="{{ $appointment->patient->consultant->name }}" data-clinic_id="{{ $appointment->clinic_id }}" data-type="{{ $appointment->appointment_type }}" data-date="{{ $appointment->appointment_date }}" data-admission_date="{{ $appointment->admission_date }}" data-start="{{ format_time($appointment->start_time) }}" data-operation_duration="{{ $appointment->operation_duration }}" data-ward="{{ $appointment->ward }}" data-admission_time="{{ format_time($appointment->admission_time) }}" data-procedure_id="{{ $appointment->procedure_id }}" data-patient_id="{{ optional($appointment->patient)->id }}" data-patient_name="{{ optional($appointment->patient)->full_name }}" data-patient_dob="{{ format_date(optional($appointment->patient)->dob) }}" data-allergy="{{ $appointment->allergy }}" data-note="{{ $appointment->appointment_note }}">
                            <i class="isax isax-edit-2"></i>
                        </a>
                        @endif
                    </li>


                    <li>
                        <a href="javascript:void(0)" title="Change Status" class="btn btn-success" onclick="openStatusModal({{ $appointment->id }}, {{ $appointment->patient->id }}, '{{ $appointment->appointment_status }}')">
                            <i class="isax isax-task-square"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" title="Delete" class="btn btn-danger" onclick="deleteAppointment({{ $appointment->id }}, {{ $appointment->patient->id }}, 0)">
                            <i class="isax isax-trash"></i>
                        </a>
                    </li>


                </ul>


                {{-- <div class="appointment-detail-btn">
                        <a href="javascript:void(0)"
						    onclick="openStatusModal({{ $appointment->id }}, {{ $appointment->patient->id }}, '{{ $appointment->appointment_status }}')"
                class="start-link">
                <i class="isax isax-calendar-tick5 me-1"></i>Attend</a>
    </div> --}}
    </li>
    </ul>
</div>
</div>
@endforeach

@if ($appointments->hasMorePages())
<div class="col-md-12 text-center">
    <button class="btn btn-outline-primary rounded-pill load-more" data-next-page="{{ $appointments->nextPageUrl() }}">
        Load More
    </button>
</div>
@endif

