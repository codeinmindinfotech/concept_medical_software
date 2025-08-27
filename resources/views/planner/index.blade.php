@extends('backend.theme.default')
@push('styles')
<link href="{{ asset('theme/main/css/custom_diary.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'Scheduled Appointment List'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Scheduled Appointment List',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('patients.create'),
    'isListPage' => true
    ])

    @session('success')
    <div class="alert alert-success" role="alert">
        {{ $value }}
    </div>
    @endsession

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-calendar me-1"></i> Scheduled Appointment Management
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{guard_route('planner.index') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <!-- Date Filter -->
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label">Select Date</label>
                        <input type="date" name="date" value="{{ $date }}" class="form-control" onchange="this.form.submit()">
                    </div>
            
                    <!-- Clinic Filter -->
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label">Filter by Clinic</label>
                        <select name="clinic_id" class="form-select select2" onchange="this.form.submit()">
                            <option value="">All Clinics</option>
                            @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}" {{ request('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                {{ $clinic->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
            
                    <!-- Patient Filter -->
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label">Filter by Patient</label>
                        <select name="patient_id" class="form-select select2" onchange="this.form.submit()">
                            <option value="">All Patients</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->full_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
            
                    <!-- Reset Button -->
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label d-block invisible">Reset</label>
                        <a href="{{guard_route('planner.index', ['date' => now()->toDateString()]) }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-undo"></i> Reset Filters
                        </a>
                    </div>
                </div>
            </form>
            
            <!-- Table Layout -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 100px;"><i class="far fa-clock me-1  text-primary"></i>Time</th>
                            @foreach($clinics as $clinic)
                                <th>
                                    @if(strtolower($clinic->clinic_type) === 'hospital')
                                        <i class="fas fa-hospital me-1 text-danger" title="Hospital"></i>
                                    @else
                                        <i class="fas fa-clinic-medical me-1 text-primary" title="Clinic"></i>
                                    @endif
                                    {{ $clinic->name }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @for ($hour = 7; $hour <= 18; $hour++)
                            <tr>
                                <td class="fw-bold text-nowrap bg-light align-middle">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                                </td>
                    
                                @foreach($clinics as $clinic)
                                    <td class="p-2 align-top dropzone"
                                        data-hour="{{ $hour }}"
                                        data-clinic-id="{{ $clinic->id }}">
                                        @php
                                            $hourlyAppointments = $appointments->filter(function ($appointment) use ($clinic, $hour) {
                                                return $appointment->clinic_id == $clinic->id &&
                                                       \Carbon\Carbon::parse($appointment->start_time)->hour == $hour;
                                            });
                                        @endphp
                    
                                        @forelse($hourlyAppointments as $appointment)
                                        @php
                                            $typeClass = $appointment->appointmentType
                                                ? 'appointment-' . str_replace(' ', '_', strtolower($appointment->appointmentType->value))
                                                : 'appointment-default';
                                        @endphp
                                            <div 
                                                class="card shadow-sm mb-2 border-start border-3 {{ $typeClass }} draggable-appointment"
                                                draggable="true"
                                                data-id="{{ $appointment->id }}"
                                                data-patient-id="{{ $appointment->patient_id }}"
                                                data-appointment_type="{{ $appointment->appointment_type }}"
                                                data-procedure-id="{{ $appointment->procedure_id }}"
                                                data-end-time="{{ $appointment->end_time }}"
                                                data-hour="{{ $hour }}"
                                                data-clinic-id="{{ $clinic->id }}"
                                            >
                                                <div class="card-body p-2 small">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <div class="d-flex align-items-center gap-2">
                                                            @if ($appointment->patient->patient_picture)
                                                                <img src="{{ asset('storage/' . $appointment->patient->patient_picture) }}"
                                                                        alt="Patient Picture"
                                                                        class="rounded-circle"
                                                                        width="36" height="36">
                                                            @else
                                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                                                        style="width: 36px; height: 36px;">
                                                                    <i class="fa-solid fa-user"></i>
                                                                </div>
                                                            @endif
                                                        
                                                            <a href="{{guard_route('tasks.tasks.index', $appointment->patient_id) }}"
                                                                target="_blank"
                                                                class="fw-semibold text-dark text-truncate text-decoration-none"
                                                                title="View Patient Tasks">
                                                                 {{ $appointment->patient->full_name ?? '-' }}
                                                             </a>
                                                        </div>
                                                            
                                                        <div>
                                                            @if (strtolower($clinic->clinic_type) === 'hospital')
                                                                @include('planner.partials.hospitalactions', ['appointment' => $appointment])
                                                            @else
                                                                @include('planner.partials.actions', ['appointment' => $appointment])
                                                            @endif
                                                        </div>
                                                    </div>
                    
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="text-muted">
                                                            <i class="far fa-clock me-1"></i>
                                                            {{ format_time($appointment->start_time) }} - {{ format_time($appointment->end_time) }}
                                                        </div>
                                                        @if(!empty($appointment->appointmentStatus->value))
                                                            <span class="badge rounded-pill bg-{{ 
                                                                $appointment->appointmentStatus->value === 'Scheduled' ? 'primary' :
                                                                ($appointment->appointmentStatus->value === 'Arrived' ? 'success' :
                                                                ($appointment->appointmentStatus->value === 'DNA' ? 'danger' : 'secondary'))
                                                            }}">
                                                                {{ $appointment->appointmentStatus->value }}
                                                            </span>
                                                        @endif
                                                    </div>
                    
                                                    @if($appointment->appointment_note || $appointment->patient_need)
                                                        <div class="mt-1 text-muted small">
                                                            @if($appointment->patient_need)
                                                                <i class="fas fa-sticky-note me-1 text-warning"></i>
                                                                <span title="Patient Need">{{ Str::limit($appointment->patient_need, 40) }}</span>
                                                            @endif
                                                            @if($appointment->appointment_note)
                                                                <br>
                                                                <i class="fas fa-notes-medical me-1 text-info"></i>
                                                                <span title="Note">{{ Str::limit($appointment->appointment_note, 40) }}</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-muted small fst-italic">No Appointments</div>
                                        @endforelse
                                    </td>
                                @endforeach
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Hospital Booking Modal -->
<x-hospital-appointment-modal
    :clinics="$clinics"
    :patients="$patients"
    :patient="$patient ? $patient : ''"
    :procedures="$procedures"
    :flag="1"
    :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />

<!-- Status Change Modal -->
<x-status-modal :diary_status="$diary_status" :flag="1" />

<!-- Appointment Booking Modal -->
<x-appointment-modal
    :clinics="$clinics"
    :patients="$patients"
    :patient="$patient ? $patient : ''"
    :appointmentTypes="$appointmentTypes"
    :flag="1"
    :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />

@endsection
@push('scripts')
<script src="{{ asset('theme/custom.js') }}"></script>
<script src="{{ asset('theme/patient-diary.js') }}"></script>
<script>
    $(document).on('click', '.edit-appointment', function() {
        let btn = $(this);

        // Set form values
        $('#appointment-id').val(btn.data('id'));
        $('#appointment-patient').val(btn.data('patient_name'));
        $('#appointment-dob').val(btn.data('dob'));
        $('#appointment_type').val(btn.data('type'));
        $('#modal-appointment-date').val(btn.data('date'));
        $('#start_time').val(btn.data('start'));
        $('#end_time').val(btn.data('end'));
        $('#patient_need').val(btn.data('need'));
        $('#appointment_note').val(btn.data('note'));
        $('#appointment-clinic-id').val(btn.data('clinic-id'));

        $('#modal-patient-name').val(btn.data('patient_name') || '');
        $('#modal-dob').val(btn.data('dob') || '');

        let appointmentId = btn.data('id');
        let route = btn.data('action');

        $('#bookAppointmentForm').attr('action', route);

        $('#bookAppointmentModal').modal('show');
    });

    // Handle submission
    $('#bookAppointmentForm').on('submit', function(e) {
        e.preventDefault();

        const form = this;
        let actionUrl = $(form).attr('action');
        let formData = $(form).serialize();
        let id = $('#appointment-id').val();

        $.post(actionUrl, formData)
            .done(function(res) {
                $('#bookAppointmentModal').modal('hide');
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: id ? 'Appointment updated successfully!' : 'Appointment booked successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    location.reload();
                } else {
                    Swal.fire('Error', res.message || 'Operation failed.', 'error');
                }
            })
            .fail(function(xhr) {
            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                handleValidationErrors(xhr.responseJSON.errors, form);
                } else {
                    Swal.fire('Error', 'Failed to submit appointment form.', 'error');
                    console.error(xhr.responseText);
                }
            });
    });

    const routes = {
        destroyAppointment: (appointmentId, patientId) =>
            `{{guard_route('patients.appointments.destroy', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
                .replace('__PATIENT_ID__', patientId)
                .replace('__APPOINTMENT_ID__', appointmentId),

        statusAppointment: (appointmentId, patientId) =>
            `{{guard_route('patients.appointments.updateStatus', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
                .replace('__PATIENT_ID__', patientId)
                .replace('__APPOINTMENT_ID__', appointmentId),
    };

    document.addEventListener('click', function (e) {
        if (e.target.closest('.edit-hospital-appointment')) {
            const button = e.target.closest('.edit-hospital-appointment');

            const id = button.dataset.id;
            const date = button.dataset.date;
            const admission_date = button.dataset.admission_date;
            const start = button.dataset.start;
            const admission_time = button.dataset.admission_time;
            const need = button.dataset.need;
            const note = button.dataset.note;
            const procedure_id = button.dataset.procedure_id;
            const operation_duration = button.dataset.operation_duration;
            const ward = button.dataset.ward;
            const allergy = button.dataset.allergy;
            const clinic_id = button.dataset.clinic_id;
            const action =  button.dataset.action; 
            const patient_id =  button.dataset.patient_id;  
            const patient_name =  button.dataset.patient_name;
            const patient_dob =  button.dataset.patient_dob;
            document.getElementById('manualBookingLabel').textContent = 'Edit Appointment';
            document.getElementById('booking-submit-btn').textContent = 'Update Appointment';

            document.getElementById('hospital-appointment-id').value = id;
            document.getElementById('hospital-patient-id').value = patient_id;

            document.getElementById('flag').value = 1;
            document.getElementById('hospital_appointment_date').value = date;
            document.getElementById('hospital_start_time').value = start;
            document.getElementById('admission_time').value = admission_time;
            document.getElementById('admission_date').value = admission_date;
            document.getElementById('patient_need').value = need;
            document.getElementById('appointment_note').value = note;
            document.getElementById('procedure_id').value = procedure_id;
            document.getElementById('operation_duration').value = operation_duration;
            document.getElementById('ward').value = ward;
            document.getElementById('allergy').value = allergy;
            document.getElementById('hospital-clinic-id').value = clinic_id;
            document.getElementById('notes').value = note;
            document.getElementById('hospital-patient-name').value = patient_name;
            document.getElementById('hospital-dob').value = patient_dob;

            $('#manualBookingForm').attr('data-action', action);

            const modal = new bootstrap.Modal(document.getElementById('manualBookingModal'));
            modal.show();
            $('#procedure_id').val(procedure_id).trigger('change');
                $('#procedure_id').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#manualBookingModal')  // important for modals!
                });
        }
    });
    let draggedAppointment = null;

    document.querySelectorAll('.draggable-appointment').forEach(el => {
        el.addEventListener('dragstart', function (e) {
            draggedAppointment = this;
            setTimeout(() => this.classList.add('dragging'), 0);
        });

        el.addEventListener('dragend', function () {
            this.classList.remove('dragging');
        });
    });

    document.querySelectorAll('.dropzone').forEach(zone => {
        zone.addEventListener('dragover', e => {
            e.preventDefault(); // Allow drop
            zone.classList.add('drag-over');
        });

        zone.addEventListener('dragleave', () => {
            zone.classList.remove('drag-over');
        });

        zone.addEventListener('drop', function (e) {
            e.preventDefault();
            zone.classList.remove('drag-over');

            if (!draggedAppointment) return;
            const appointmentId = draggedAppointment.dataset.id;
            const oldClinicId = draggedAppointment.dataset.clinicId;
            const oldHour = draggedAppointment.dataset.hour;
            const end_time = draggedAppointment.dataset.endTime;
            const procedureId = draggedAppointment.dataset.procedureId;
            const appointment_type = draggedAppointment.dataset.appointment_type;

            const newClinicId = this.dataset.clinicId;
            const newHour = this.dataset.hour;

            if (oldClinicId === newClinicId && oldHour === newHour) return;

            // Make AJAX request to update appointment
            updateAppointmentTimeAndClinic(appointmentId, newClinicId, newHour, end_time, procedureId, appointment_type);
        });
    });

    function updateAppointmentTimeAndClinic(appointmentId, clinicId, hour, end_time, procedureId, appointment_type) {
        const date = "{{ $date }}"; // Blade variable

        const url = `/appointments/${appointmentId}/reschedule`; // Adjust to your route
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                clinic_id: clinicId,
                hour: hour,
                date: date,
                end_time: end_time,
                procedureId: procedureId,
                appointment_type: appointment_type
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                location.reload(); // Or dynamically move the card
            } else {
                alert('Could not reschedule appointment.');
            }
        })
        .catch(() => {
            alert('Error updating appointment.');
        });
    }
</script>

@endpush