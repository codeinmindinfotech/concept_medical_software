@extends('layout.mainlayout_admin')
@section('content')
<div class="page-wrapper">
    <div class="container-fluid px-4">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Patients', 'url' =>guard_route('patients.index')],
        ['label' => 'Patients Appointment '],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Patients Appointment ',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.index'),
        'isListPage' => false
        ])
        <!-- Header: Date Navigation + Clinic + Patient -->
        <div class="d-flex align-items-center mb-3 gap-2">
            <button id="prevDay" class="btn btn-outline-primary">&larr;</button>
            <input type="date" id="selectedDate" class="form-control" style="width: 150px;" value="{{ date('Y-m-d') }}">
            <button id="nextDay" class="btn btn-outline-primary">&rarr;</button>
            
            <div class="col-md-3">
                <select id="clinic-select" class="form-select select2 ms-3" style="width: 200px;">
                    @foreach($clinics as $clinic)
                        <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="patient-select" class="form-select select2 ms-3" style="width: 200px;">
                    <option value="">Select Patient</option>
                    @foreach($patients as $pat)
                        <option value="{{ $pat->id }}">{{ $pat->full_name }} ({{ format_date($pat->dob) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-secondary ms-auto" id="fullDayReportBtn">View Full Day Report</button>
            </div>
        </div>

        <!-- Appointment slots -->
        <div id="appointments-container" class="table-responsive"></div>
    </div>
</div>
</div>
<!-- /Main Wrapper -->

<!-- Loader -->
<div id="globalLoader" style="display:none; position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.7);z-index:9999;text-align:center;">
    <div class="spinner-border text-primary" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);"></div>
</div>

@endsection

@php
$loadAppointmentsUrl = (!empty($patient) && !empty($patient->id))
    ? guard_route('patients.appointments.byDate', ['patient' => $patient])
    : guard_route('appointments.byDateGlobal');
@endphp
@push('scripts')
<script src="{{ URL::asset('/assets/js/popupForm.js') }}"></script>
<script>
    window.appConfig = {
        loadAppointmentsUrl: "{{ $loadAppointmentsUrl }}",
        updateSlotUrl: "{{ guard_route('appointments.update-slot') }}",
        reportUrl: "{{ guard_route('reports.entire-day') }}",
        csrfToken: "{{ csrf_token() }}",
        fetchAppointmentRoute: "{{ guard_route('appointments.edit', ['id' => '__ID__']) }}",
        initialDate: "{{ date('Y-m-d') }}",
        patientId: "{{ !empty($patient) && !empty($patient->id) ? $patient->id : '' }}",
        storeHospitalAppointment: "{{ $patient ?guard_route('hospital_appointments.store', ['patient' => $patient->id]) :guard_route('hospital_appointments.storeGlobal') }}",
        statusAppointment: (appointmentId, patientId) =>
            `{{guard_route('patients.appointments.updateStatus', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
            .replace('__PATIENT_ID__', patientId)
            .replace('__APPOINTMENT_ID__', appointmentId),
        destroyAppointment: (appointmentId, patientId) =>
            `{{guard_route('patients.appointments.destroy', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
            .replace('__PATIENT_ID__', patientId)
            .replace('__APPOINTMENT_ID__', appointmentId),
    };
</script>
<script src="{{ URL::asset('/assets/js/appointment.js') }}"></script>

@endpush

@push('modals')
    <!-- WhatsApp Modal (Only One Modal for All Appointments) -->
    <div class="modal fade" id="whatsAppModal" tabindex="-1" aria-labelledby="whatsAppModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="whatsAppModalLabel">Send WhatsApp Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Custom Message Input -->
                    <textarea id="customMessage" class="form-control" rows="4" placeholder="Enter your message here...">
                        Hello, I wanted to confirm my appointment for
                    </textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="sendWhatsAppMessage()">Send Message</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hospital Booking Modal -->
    <x-hospital-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''" :procedures="$procedures" :flag="$flag" :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />

    <!-- Status Change Modal -->
    <x-status-modal :diary_status="$diary_status" :flag="$flag" />

    <!-- Appointment Booking Modal -->
    <x-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''" :appointmentTypes="$appointmentTypes" :flag="$flag" :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />
@endpush


