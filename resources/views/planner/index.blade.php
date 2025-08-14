@extends('backend.theme.default')
@push('styles')
<link href="{{ asset('theme/main/css/custom_diary.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Patients', 'url' => route('patients.index')],
    ['label' => 'Scheduled Appointment List'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Scheduled Appointment List',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('patients.create'),
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
            <form method="GET" action="{{ route('planner.index') }}" class="mb-4">
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
                        <a href="{{ route('planner.index', ['date' => now()->toDateString()]) }}" class="btn btn-outline-primary w-100">
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
                            <th style="width: 100px;">Time</th>
                            @foreach($clinics as $clinic)
                                <th>{{ $clinic->name }}</th>
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
                                    <td class="p-2 align-top">
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
                                            <div class="card shadow-sm mb-2 border-start border-3 {{ $typeClass }}">
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
                                                        
                                                            <span class="fw-semibold text-dark text-truncate" title="View Details">
                                                                {{ $appointment->patient->full_name ?? 'No Name' }}
                                                            </span>
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
    :flag="0"
    :action="$patient ? route('patients.appointments.store', ['patient' => $patient->id]) : route('appointments.storeGlobal')" />

<!-- Status Change Modal -->
<x-status-modal :diary_status="$diary_status" :flag="0" />

<!-- Appointment Booking Modal -->
<x-appointment-modal
    :clinics="$clinics"
    :patients="$patients"
    :patient="$patient ? $patient : ''"
    :appointmentTypes="$appointmentTypes"
    :flag="0"
    :action="$patient ? route('patients.appointments.store', ['patient' => $patient->id]) : route('appointments.storeGlobal')" />

@endsection
@push('scripts')
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

        let actionUrl = $(this).attr('action');
        let formData = $(this).serialize();

        $.post(actionUrl, formData, function(res) {
            $('#bookAppointmentModal').modal('hide');
            location.reload();
        }).fail(function() {
            alert('Failed to submit appointment form.');
        });
    });
    const routes = {
        destroyAppointment: (appointmentId, patientId) =>
            `{{ route('patients.appointments.destroy', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
                .replace('__PATIENT_ID__', patientId)
                .replace('__APPOINTMENT_ID__', appointmentId),

        statusAppointment: (appointmentId, patientId) =>
            `{{ route('patients.appointments.updateStatus', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
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
        }
    });

 </script>
@endpush
