@extends('layout.mainlayout')
@section('content')

<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-lg-9 col-xl-10">
			    <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Patient Appointment
                        </h5>
                        <a href="{{ guard_route('patients.appointments.schedule', ['patient' => $patient]) }}" class="btn bg-primary text-white btn-light btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> New Appointment
                        </a>
                    </div>
                    
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="table-responsive">
                            @include('patients.apt.list', [
                            'patient' => $patient,
                            'apts'=> $apts
                            ])
                        </div>
                    </div>
                </div>
            </div>
            <!-- Profile Sidebar -->
            @component('components.admin.tab-navigation', ['patient' => $patient])
            @endcomponent
        </div>
    </div>
</div>
<!-- /Page Content -->
@endsection
@push('modals')
    <!-- Status Change Modal -->
<x-status-modal :diary_status="$diary_status" :flag="0" />

<!-- Hospital Booking Modal -->
<x-hospital-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''" :procedures="$procedures" :flag="0" :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />

<!-- Include your bookAppointmentModal component -->
<x-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''" :appointmentTypes="$appointmentTypes" :flag="0" :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />
@endpush
@php
$loadAppointmentsUrl = (!empty($patient) && !empty($patient->id))
    ? guard_route('patients.appointments.byDate', ['patient' => $patient])
    : guard_route('appointments.byDateGlobal');
@endphp
@push('scripts')
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

        $('#PatientApt').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            lengthChange: true,
            pageLength: 10,
            columnDefs: [
                {
                    targets: 7, // Disable sorting for Action column
                    orderable: false
                }
            ]
        });

    $(document).ready(function() {
        PopupForm.init('#bookAppointmentModal', '#bookAppointmentForm', (response) => {
            // Reload appointments after booking
            location.reload();
            Swal.fire({
                icon: 'success'
                , title: 'Success'
                , text: response.message || 'Appointment booked successfully!'
                , timer: 2000
                , showConfirmButton: false
            });
        });

        PopupForm.init('#statusChangeModal', '#statusChangeForm', (response) => {
            // Optionally reload appointments table
            location.reload();
            Swal.fire({
                icon: 'success'
                , title: 'Success'
                , text: response.message || 'Status updated successfully!'
                , timer: 2000
                , showConfirmButton: false
            });
            $('#statusChangeModal').modal('hide');
        });

        // For Manual Booking Modal
        PopupForm.init('#manualBookingModal', '#manualBookingForm', (response) => {
            // Do something after manual booking
            location.reload();
            Swal.fire({
                icon: 'success'
                , title: 'Success'
                , text: response.message || 'Appointment booked For Hospital successfully!'
                , timer: 2000
                , showConfirmButton: false
            });
        });
    });
    </script>
    <script src="{{ URL::asset('/assets/js/modalpopup.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/popupForm.js') }}"></script>
@endpush