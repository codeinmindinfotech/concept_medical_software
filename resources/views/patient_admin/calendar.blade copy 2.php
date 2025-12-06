@extends('layout.mainlayout')
@section('content')

@push('styles')
<style>
.fc-event {
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    padding: 5px;
    cursor: pointer;
}
</style>
@endpush

<div class="content">
    <div class="container mt-5">

        <div class="row mb-3 align-items-center">
            <!-- Clinic filter -->
            <div class="col-md-4">
                <select id="clinicFilter" class="form-select">
                    <option value="">-- Select Clinic --</option>
                    @foreach($clinics as $clinic)
                        <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>

    </div>
</div>

@endsection
@push('modals')

<!-- Status Change Modal -->
<x-status-modal :diary_status="$diary_status" :flag="0" />

<!-- Hospital Booking Modal -->
<x-hospital-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''" :procedures="$procedures" :flag="0" :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />

<!-- Include your bookAppointmentModal component -->
<x-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''" :appointmentTypes="$appointmentTypes" :flag="0" :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />



@endpush
@push('scripts')
<script src="{{ URL::asset('/assets/plugins/fullcalendar/3.10.2/fullcalendar.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/popupForm.js') }}"></script>
<script>
    window.Laravel = {
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ URL::asset('/assets/js/calendar.js') }}"></script>
<script>
    const routes = {
        storeHospitalAppointment: "{{ $patient ?guard_route('hospital_appointments.store', ['patient' => $patient->id]) :guard_route('hospital_appointments.storeGlobal') }}",
        fetchAppointmentsByDate: "{{ $patient ? guard_route('patients.appointments.new_schedule', ['patient' => $patient->id]) : guard_route('patients.appointments.new_schedule') }}",
        fetchAllAppointments: "{{ guard_route('patients.appointments.index') }}",
        statusAppointment: (appointmentId, patientId) =>
            `{{guard_route('patients.appointments.updateStatus', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
            .replace('__PATIENT_ID__', patientId)
            .replace('__APPOINTMENT_ID__', appointmentId)
    };

    $(document).ready(function() {
    function loadCalendar(clinicId = '') {
        $('#calendar').fullCalendar('destroy');

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: '' // only month viewmonth
            },
            height: 450, // compact height
            contentHeight: 450, // ensures height fits inside card
            editable: false,
            selectable: true,
            eventLimit: true,
            events: {
                url: routes.fetchAllAppointments,
                type: 'GET',
                data: { clinic_id: clinicId }
            },
            eventClick: function(event) {
				// Show options to user
				Swal.fire({
					title: 'Select Action',
					showDenyButton: true,
					showCancelButton: true,
					confirmButtonText: 'Edit Appointment',
					denyButtonText: 'Change Status',
				}).then((result) => {
					if (result.isConfirmed) {
						// Edit appointment
						fillAppointmentModal(event, event.clinic_type);
					} else if (result.isDenied) {
						// Change status
						openStatusModal(event.id, event.patient_id, event.status);
					}
					// Cancel does nothing
				});
			},

            // eventClick: function(event) {
            //     const date = moment(event.start).format('YYYY-MM-DD');
            //     const clinicId = $('#clinicFilter').val() || '';
            //     window.location.href = `${routes.fetchAppointmentsByDate}?date=${date}&clinic_id=${clinicId}`;
            // },
            select: function(start) {
                const date = start.format('YYYY-MM-DD');
                const clinicId = $('#clinicFilter').val();
                if (!clinicId) {
                    Swal.fire({ icon: 'warning', title: 'Select Clinic', text: 'Please select a clinic first.' });
                    return false;
                }
                window.location.href = `${routes.fetchAppointmentsByDate}?date=${date}&clinic_id=${clinicId}`;
            },
            views: {
                month: {
                    // Remove extra time and slots from month view
                    titleFormat: 'MMMM YYYY', // e.g., December 2025
                    columnHeaderFormat: 'dddd' // shows just 1,2,3...
                }
            },
            columnHeaderFormat: 'dddd' // shows 1,2,3 in the top header row
        });
    }

    loadCalendar($('#clinicFilter').val());

    $('#clinicFilter').on('change', function() {
        loadCalendar($(this).val());
    });

    function fillAppointmentModal(event, selectedClinicType) {
            // Common fields
            $("#appointment-id").val(event.id);
            $("#appointment-patient-id").val(event.patient_id);
            $("#patient-id").val(event.patient_id);

            $("#modal-patient-name").val(event.patient_name);
            $("#modal-dob").val(event.dob);
            $("#clinic_consultant").val(event.consultant);
            $("#appointment_type").val(event.appointment_type);
            $("#modal-appointment-date").val(moment(event.start).format('YYYY-MM-DD'));
            $("#start_time").val(moment(event.start).format('HH:mm'));
            $("#end_time").val(moment(event.end).format('HH:mm'));
            $("#patient_need").val(event.patient_need);
            $("#appointment_note").val(event.appointment_note);

            // Hospital-specific fields
            $("#hospital-clinic-id").val(event.clinic_id);
            $("#hospital-appointment-id").val(event.id);
            $("#hospital-patient-id").val(event.patient_id);
            $("#hospital-dob").val(event.dob);
            $("#hospital_appointment_date").val(moment(event.start).format('YYYY-MM-DD'));
            $("#hospital_start_time").val(moment(event.start).format('HH:mm'));
            $("#admission_date").val(event.admission_date);
            $("#admission_time").val(event.admission_time);
            $("#operation_duration").val(event.operation_duration);
            $("#ward").val(event.ward);
            $("#allergy").val(event.allergy);
            $("#procedure_id").val(event.procedure_id);
            $("#consultant").val(event.consultant);
            $("#notes").val(event.appointment_note);
            $("#appointment-clinic-id").val(event.clinic_id);

            // Slots
            $(".apt-slot-radio").prop('checked', false);
            if (event.apt_slots) {
                $("#slot" + event.apt_slots).prop('checked', true);
            } else {
                $("#slot1").prop('checked', true);
            }

            // Set form action for hospital modal
            if (selectedClinicType === "hospital") {
                $('#manualBookingForm').attr('data-action', routes.storeHospitalAppointment);
                $("#manualBookingModal").modal("show");
            } else {
                $("#bookAppointmentModal").modal("show");
            }
        }
});

</script>
@endpush
