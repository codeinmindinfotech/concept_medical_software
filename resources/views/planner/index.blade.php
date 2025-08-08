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
<div class="modal fade" id="manualBookingModal" tabindex="-1" aria-labelledby="manualBookingLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"><!-- wider dialog -->
        <form id="manualBookingForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manualBookingLabel">Add Hospital Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    <input type="hidden" class="form-control" id="hospital-clinic-id" name="clinic_id">
                    <input type="hidden" class="form-control" id="hospital-appointment-id" name="hospital_id">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="hospital-patient-name" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <div class="input-group">
                                <input readonly id="hospital-dob" type="text" class="form-control " placeholder="YYYY-MM-DD">
                                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="hospital_appointment_date" class="form-label">Procedure Date</label>
                            <input type="date" class="form-control" id="hospital_appointment_date" name="appointment_date" required>
                        </div>

                        <div class="col-md-6">
                            <label for="hospital_start_time" class="form-label">Procedure Time</label>
                            <input type="time" class="form-control" id="hospital_start_time" name="start_time" required>
                        </div>

                        <div class="col-md-6">
                            <label for="admission_date" class="form-label">Admission Date</label>
                            <input type="date" class="form-control" id="admission_date" name="admission_date" required>
                        </div>

                        <div class="col-md-6">
                            <label for="admission_time" class="form-label">Admission Time</label>
                            <input type="time" class="form-control" id="admission_time" name="admission_time" required>
                        </div>

                        <div class="col-md-6">
                            <label for="procedure_id" class="form-label">Procedure</label>
                            <select class="form-select" id="procedure_id" name="procedure_id" required>
                                <option value="">Select Procedure</option>
                                @foreach($procedures as $procedure)
                                    <option value="{{ $procedure->id }}">{{ $procedure->code }} - {{ $procedure->description }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="operation_duration" class="form-label">Operation Duration (minutes)</label>
                            <input type="number" min="1" class="form-control" id="operation_duration" name="operation_duration" required>
                        </div>

                        <div class="col-md-6">
                            <label for="ward" class="form-label">Ward</label>
                            <input type="text" class="form-control" id="ward" name="ward" placeholder="Ward name or number">
                        </div>

                        <div class="col-md-6">
                            <label for="allergy" class="form-label">Allergy Information</label>
                            <input type="text" class="form-control" id="allergy" name="allergy" placeholder="Patient allergy details">
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Additional notes"></textarea>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="booking-submit-btn">Add Appointment</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Status Change Modal -->
<div class="modal fade" id="statusChangeModal" tabindex="-1" aria-hidden="true" aria-labelledby="statusChangeModalLabel">
    <div class="modal-dialog">
        <form id="statusChangeForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="statusChangeModalLabel">Change Appointment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="appointment_id">
                    <input type="hidden" name="patient_id" id="patient_id">
                    <div class="mb-3">
                        <label for="appointment_status" class="form-label">Select Status:</label>
                        <select id="appointment_status" name="appointment_status" class="form-select">
                            @foreach($diary_status as $id => $value)
                            <option value="{{ $id }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Appointment Booking Modal -->
<div class="modal fade" id="bookAppointmentModal" tabindex="-1" aria-labelledby="bookAppointmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="bookAppointmentForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bookAppointmentLabel">Book Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="appointment-id">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="appointment-patient" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <div class="input-group">
                                <input readonly id="appointment-dob" type="text" class="form-control " placeholder="YYYY-MM-DD">
                                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="appointment_type" class="form-label">Appointment Type</label>
                            <select class="form-select" id="appointment_type" name="appointment_type">
                                @foreach($appointment_types as $id => $value)
                                <option value="{{ $id }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="dob" class="form-label"><strong>Appointment Date</strong></label>
                            <div class="input-group">
                                <input id="modal-appointment-date" name="appointment_date" type="text" class="form-control flatpickr @error('dob') is-invalid @enderror" placeholder="YYYY-MM-DD">
                                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Start Time</label>
                            <input type="text" class="form-control" id="start_time" name="start_time" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">End Time</label>
                            <input type="text" class="form-control" id="end_time" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Slots</label>
                            <div id="slot-options">
                                @for ($i = 1; $i <= 10; $i++) <div class="form-check form-check-inline">
                                    <input class="form-check-input apt-slot-radio" type="radio" name="apt_slots" id="slot{{ $i }}" {{ $i==1 ? 'checked' : '' }} value="{{ $i }}">
                                    <label class="form-check-label" for="slot{{ $i }}">{{ $i }}</label>
                            </div>
                            @endfor
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Patient Need</label>
                        <input type="text" class="form-control" id="patient_need" name="patient_need">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Appointment Note</label>
                        <input type="text" class="form-control" id="appointment_note" name="appointment_note">
                    </div>

                    <div class="col-md-6">
                        <label for="appointment-clinic-id" class="form-label fw-semibold">Select Clinic:</label>
                        <select id="appointment-clinic-id" name="clinic_id" class="form-select shadow-sm">
                            <option value="">-- Choose Clinic --</option>
                            @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}">
                                {{ $clinic->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="modal-submit-btn">Confirm Booking</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).on('click', '.edit-appointment', function() {
        let btn = $(this);

        // Set form values
        $('#appointment-id').val(btn.data('id'));
        $('#appointment-patient').val(btn.data('patient'));
        $('#appointment-dob').val(btn.data('dob'));
        $('#appointment_type').val(btn.data('type'));
        $('#modal-appointment-date').val(btn.data('date'));
        $('#start_time').val(btn.data('start'));
        $('#end_time').val(btn.data('end'));
        $('#patient_need').val(btn.data('need'));
        $('#appointment_note').val(btn.data('note'));
        $('#appointment-clinic-id').val(btn.data('clinic-id'));

        // Set patient info (optional)
        $('#modal-patient-name').val(btn.data('name') || '');
        $('#modal-dob').val(btn.data('dob') || '');

        // Set form action dynamically
        let appointmentId = btn.data('id');
        let route = btn.data('action');

        $('#bookAppointmentForm').attr('action', route);

        // Show modal
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
    const statusAppointmentRoute = `{{ route('patients.appointments.updateStatus', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`;
    const destroyAppointment = `{{ route('patients.appointments.destroy', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`;

    // OPEN STATUS CHANGE MODAL
    function openStatusModal(appointmentId, patientId, currentStatus) {
        $('#appointment_id').val(appointmentId);
        $('#patient_id').val(patientId);
        $('#appointment_status').val(currentStatus);

        // Replace placeholders with actual values
        let finalUrl = statusAppointmentRoute
            .replace('__PATIENT_ID__', patientId)
            .replace('__APPOINTMENT_ID__', appointmentId);
        $('#statusChangeForm').data('action', finalUrl);

        $('#statusChangeModal').modal('show');
    }

    // SUBMIT STATUS FORM (AJAX optional)
    $('#statusChangeForm').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize();
        const url = $(this).data('action');

        $.post(url, data, function(response) {
            $('#statusChangeModal').modal('hide');
            location.reload();
        }).fail(function() {
            alert('Status update failed.');
        });
    });

    function deleteAppointment(appointmentId, patientId) {
        
        // Replace placeholders with actual values
        let finalUrl = destroyAppointment
            .replace('__PATIENT_ID__', patientId)
            .replace('__APPOINTMENT_ID__', appointmentId);
        const url = finalUrl;
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the appointment.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        location.reload();

                    } else {
                        Swal.fire('Error', data.message || 'Failed to delete.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Something went wrong.', 'error');
                });
            }
        });
    }
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

            document.getElementById('manualBookingLabel').textContent = 'Edit Appointment';
            document.getElementById('booking-submit-btn').textContent = 'Update Appointment';

            document.getElementById('hospital-appointment-id').value = id;
            // document.getElementById('appointment_type').value = type;
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

            document.getElementById('hospital-patient-name').value = "{{ $patient->full_name }}";
            document.getElementById('hospital-dob').value = "{{ format_date($patient->dob) }}";

            $('#manualBookingForm').attr('action', action);
            const modal = new bootstrap.Modal(document.getElementById('manualBookingModal'));
            modal.show();
        }
    });
    // Handle submission
    $('#manualBookingForm').on('submit', function(e) {
        e.preventDefault();

        let actionUrl = $(this).attr('action');
        let formData = $(this).serialize();

        $.post(actionUrl, formData, function(res) {
            $('#manualBookingModal').modal('hide');
            location.reload();
        }).fail(function() {
            alert('Failed to submit appointment form.');
        });
    });
 </script>
@endpush
