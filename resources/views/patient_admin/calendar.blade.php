@extends('layout.mainlayout')
@section('content')

@push('styles')
<style>
#calendar {
    width: 100% !important;
}
.fc-event {
    border-radius: 6px;
    font-size: 0.85rem;
    padding: 5px;
    cursor: pointer;
}
</style>
@endpush

<div class="content">
    <div class="container mt-4">

        <div class="card shadow-sm">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('modals')
    <x-status-modal :diary_status="$diary_status" :flag="0" />
    <x-hospital-appointment-modal 
        :clinics="$clinics" 
        :patients="$patients"
        :patient="$patient ?? ''" 
        :procedures="$procedures" 
        :flag="0"
        :action="$patient ? guard_route('patients.appointments.store',['patient'=>$patient->id]) : guard_route('appointments.storeGlobal')"
    />

    <x-appointment-modal 
        :clinics="$clinics" 
        :patients="$patients"
        :patient="$patient ?? ''"
        :appointmentTypes="$appointmentTypes"
        :flag="0" 
        :action="$patient ? guard_route('patients.appointments.store',['patient'=>$patient->id]) : guard_route('appointments.storeGlobal')"
    />
@endpush

@push('scripts')
<script src="{{ URL::asset('/assets/plugins/fullcalendar/3.10.2/fullcalendar.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/popupForm.js') }}"></script>

<script>
/* REQUIRED BY appointment.js */
window.appConfig = {
    fetchAppointmentRoute: "{{ guard_route('appointments.edit', ['id' => '__ID__']) }}",
    statusAppointment: (appointmentId, patientId) =>
        `{{ guard_route('patients.appointments.updateStatus',['patient'=>'__PID__','appointment'=>'__AID__']) }}`
            .replace('__PID__', patientId)
            .replace('__AID__', appointmentId),
    destroyAppointment: (appointmentId, patientId) =>
        `{{ guard_route('patients.appointments.destroy',['patient'=>'__PID__','appointment'=>'__AID__']) }}`
            .replace('__PID__', patientId)
            .replace('__AID__', appointmentId),
    storeHospitalAppointment: "{{ $patient ? guard_route('hospital_appointments.store',['patient'=>$patient->id]) : guard_route('hospital_appointments.storeGlobal') }}",
    csrfToken: "{{ csrf_token() }}"
};

/* EXTRA FOR CALENDAR PAGE */
window.calendarConfig = {
    fetchAllAppointments : "{{ guard_route('patients.appointments.index') }}",
    patientUrl: "{{ guard_route('patients.show', ['patient' => '__PID__']) }}",
};
</script>
<script>
$(document).ready(function() {

    $('#calendar').fullCalendar({
        height: 580,
        contentHeight: 580,
        aspectRatio: 2.0,
        defaultView: 'month',
        editable: false,
        selectable: true,

        /** THIS ENABLES +more **/
        eventLimit: true,

        header: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },

        events: {
            url: calendarConfig.fetchAllAppointments,
            type: 'GET'
        },

        eventClick: function(event) {
            Swal.fire({
                title: "Choose Action",
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: "Edit",
                denyButtonText: "Status",
                cancelButtonText: "Cancel",
                footer: `<a href="${calendarConfig.patientUrl.replace('__PID__', event.patient_id)}" target="_blank">View Patient</a>`
            }).then((result) => {
                if (result.isConfirmed) {
                    fetchAppointmentData(event.id);
                }
                else if (result.isDenied) {
                    openStatusModal(event.id, event.patient_id, event.status);
                }
            });
        },

        select: function(date) {
            const selected = date.format('YYYY-MM-DD');
            window.location.href = `{{ guard_route('patients.appointments.new_schedule') }}?date=${selected}`;
        }
    });

});
</script>
<script src="{{ URL::asset('/assets/js/modalpopup.js') }}"></script>

@endpush
