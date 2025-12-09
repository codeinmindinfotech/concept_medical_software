@extends('layout.mainlayout_admin')
@push('styles')
<style>
    #calendar {
        width: 100% !important;
    }

    .fc-event {
        border-radius: 6px;
        font-size: 0.75rem;
        padding: 1px;
        cursor: pointer;
    }
</style>
@endpush
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid">
        {{-- @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
                ['label' => 'Patients', 'url' =>guard_route('patients.index')],
                ['label' => 'Patients List'],
            ];
        @endphp

        @include('layout.partials.breadcrumb', [
            'pageTitle' => 'Patients List',
            'breadcrumbs' => $breadcrumbs,
            'backUrl' => guard_route('patients.create'),
            'isListPage' => true
        ]) --}}


        <div class="container mt-4">

            <div class="card shadow-sm">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
@endsection

@push('modals')
<x-status-modal :diary_status="$diary_status" :flag="0" />
<x-hospital-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ?? ''" :procedures="$procedures" :flag="0" :action="$patient ? guard_route('patients.appointments.store',['patient'=>$patient->id]) : guard_route('appointments.storeGlobal')" />
<!-- Move Appointment Modal -->
<x-move-appointment-modal :clinics="$clinics" id="moveAppointmentModal" title="Reschedule Appointment" />

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
                <textarea id="customMessage" class="form-control" rows="4" placeholder="Enter your message here...">Hello, I wanted to confirm my appointment for</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="sendWhatsAppMessage()">Send Message</button>
            </div>
        </div>
    </div>
</div>

<x-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ?? ''" :appointmentTypes="$appointmentTypes" :flag="0" :action="$patient ? guard_route('patients.appointments.store',['patient'=>$patient->id]) : guard_route('appointments.storeGlobal')" />
@endpush

@push('scripts')
<script src="{{ URL::asset('/assets/plugins/fullcalendar/3.10.2/fullcalendar.min.js') }}"></script>

<script>
    /* REQUIRED BY appointment.js */
    window.appConfig = {
        fetchAppointmentRoute: "{{ guard_route('appointments.edit', ['id' => '__ID__']) }}"
        , statusAppointment: (appointmentId, patientId) =>
            `{{ guard_route('patients.appointments.updateStatus',['patient'=>'__PID__','appointment'=>'__AID__']) }}`
            .replace('__PID__', patientId)
            .replace('__AID__', appointmentId)
        , destroyAppointment: (appointmentId, patientId) =>
            `{{ guard_route('patients.appointments.destroy',['patient'=>'__PID__','appointment'=>'__AID__']) }}`
            .replace('__PID__', patientId)
            .replace('__AID__', appointmentId)
        , storeHospitalAppointment: "{{ $patient ? guard_route('hospital_appointments.store',['patient'=>$patient->id]) : guard_route('hospital_appointments.storeGlobal') }}"
        , csrfToken: "{{ csrf_token() }}",

        //move appoitnments
        appointmentsForDate: "{{ guard_route('appointments.forDate') }}"
        , appointmentsAvailableSlots: "{{ guard_route('appointments.availableSlots') }}"
        , appointmentsMove: "{{ guard_route('appointments.move') }}",

        whatsappSend: "{{ guard_route('whatsapp.send.runtime') }}",

        calendarDays: "{{ guard_route('calendar.days') }}"
    , };

    /* EXTRA FOR CALENDAR PAGE */
    window.calendarConfig = {
        fetchAllAppointments: "{{ guard_route('patients.appointments.index') }}"
        , patientUrl: "{{ guard_route('patients.show', ['patient' => '__PID__']) }}"
    , };

</script>
<script>
    $(document).ready(function() {
        PopupForm.init('#bookAppointmentModal', '#bookAppointmentForm', (response) => {
            // Reload appointments after booking
            appointmentManager.loadAppointments();
            Swal.fire({
                icon: 'success'
                , title: 'Success'
                , text: response.message || 'Appointment booked successfully!'
                , timer: 2000
                , showConfirmButton: false
            });
        });

        // For Manual Booking Modal
        PopupForm.init('#manualBookingModal', '#manualBookingForm', (response) => {
            // Do something after manual booking
            appointmentManager.loadAppointments();
            Swal.fire({
                icon: 'success'
                , title: 'Success'
                , text: response.message || 'Appointment booked For Hospital successfully!'
                , timer: 2000
                , showConfirmButton: false
            });
        });

        PopupForm.init('#moveAppointmentModal', '#manualBookingForm', (response) => {
            // Do something after manual booking
            appointmentManager.loadAppointments();
            Swal.fire({
                icon: 'success'
                , title: 'Success'
                , text: response.message || 'Appointment booked For Hospital successfully!'
                , timer: 2000
                , showConfirmButton: false
            });
        });

        PopupForm.init('#statusChangeModal', '#statusChangeForm', (response) => {
            // Optionally reload appointments table
            appointmentManager.loadAppointments();
            Swal.fire({
                icon: 'success'
                , title: 'Success'
                , text: response.message || 'Status updated successfully!'
                , timer: 2000
                , showConfirmButton: false
            });
            $('#statusChangeModal').modal('hide');
        });

        $('#calendar').fullCalendar({
            height: 580
            , contentHeight: 580
            , aspectRatio: 2.0
            , defaultView: 'month'
            , editable: false
            , selectable: true,

            /** THIS ENABLES +more **/
            eventLimit: true,

            header: {
                left: 'prev,next today'
                , center: 'title'
                , right: ''
            },

            events: {
                url: calendarConfig.fetchAllAppointments
                , type: 'GET'
            }
            , eventClick: function(event) {
                Swal.fire({
                    title: "Choose Action"
                    , showCancelButton: true
                    , showDenyButton: true
                    , confirmButtonText: "Edit"
                    , denyButtonText: "Status"
                    , cancelButtonText: "Cancel",

                    footer: `
                    <a href="${calendarConfig.patientUrl.replace('__PID__', event.patient_id)}" class="btn btn-primary" target="_blank">View Patient</a>
                    <br>
                    <button class="btn btn-info mt-2" id="moveAppointmentBtn">Move Appointment</button>
                    <button class="btn btn-success mt-2" id="whatsappBtn">Send WhatsApp</button>
                `
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (event.clinic_type === 'hospital') {
                            fetchHospitalAppointmentData(event.id);
                        } else {
                            fetchAppointmentData(event.id);
                        }
                    } else if (result.isDenied) {
                        openStatusModal(event.id, event.patient_id, event.status);
                    }
                });

                // Handle MOVE button
                $(document)
                    .off('click', '#moveAppointmentBtn')
                    .on('click', '#moveAppointmentBtn', function() {
                        Swal.close();
                        openMoveAppointmentModal(event);
                    });
                // WhatsApp Button
                $(document)
                    .off('click', '#whatsappBtn')
                    .on('click', '#whatsappBtn', function() {
                        Swal.close();
                        openWhatsAppModal({
                            appointmentId: event.id
                            , patientName: event.patient_name || event.patient_name
                            , patientPhone: event.patient_phone
                            , appointmentTime: event.start.format('h:mm A')
                        });
                    });
            },

            // viewRender: function(view) {

            // $.get(window.appConfig.calendarDays, function(days) {

            //     // $("td.fc-day, td[data-date]").css("border", ""); // reset

            //     days.forEach(function(day) {

            //         let cell = $("td[data-date='" + day.date + "']");

            //         cell.css({
            //             // "box-sizing": "border-box",
            //             "border": "3px solid " + day.color,
            //             // "box-sizing": "border-box",
            //             "border-radius": "6px"
            //         });
            //     });

            // });

            // },
            viewRender: function(view) {
                renderCalendarDaysDots();
            },

            select: function(date) {
                const selected = date.format('YYYY-MM-DD');
                window.location.href = `{{ guard_route('appointments.schedule') }}?date=${selected}`;
            }
        });
    });

</script>
<script src="{{ URL::asset('/assets/js/modalpopup.js') }}"></script>
<script src="{{ URL::asset('/assets/js/popupForm.js') }}"></script>
@endpush