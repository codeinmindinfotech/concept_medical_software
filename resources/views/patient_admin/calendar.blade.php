@extends('layout.mainlayout')
@section('content')
@component('components.admin.breadcrumb')
    @slot('title') Patient @endslot
    @slot('li_1') Patient Diary @endslot
    @slot('li_2') Patient Diary @endslot
@endcomponent
@push('styles')
<style>
.fc-event {
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    padding: 5px;
    cursor: pointer;
    transition: transform 0.15s;
}
</style>
@endpush

<div class="content">
    <div class="container mt-5">
        <!-- Top Controls -->
        <div class="row mb-3 align-items-center">
            <!-- Clinic Filter -->
            <div class="col-md-4">
                <select id="clinicFilter" class="form-select">
                    <option value="">-- Select Clinic --</option>
                    @foreach($clinics as $clinic)
                        <option value="{{ $clinic->id }}" data-type="{{ $clinic->clinic_type }}">{{ $clinic->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="col-md-8 text-end">
                <button class="btn btn-info me-2" onclick="openClinicOverviewCountModal()">
                    Clinic Overview
                </button>
                <button class="btn btn-warning me-2" onclick="openMoveAppointmentModal()">
                    Move Appointment
                </button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#setCalendarDaysModal">
                    Set Calendar Days
                </button>
            </div>
        </div>

        <!-- Calendar -->
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


<!-- Move Appointment Modal -->
<x-move-appointment-modal :clinics="$clinics" id="moveAppointmentModal" title="Reschedule Appointment" />

<!-- set caledar days Modal -->
<x-set-calendar-days-modal :clinics="$clinics" />

<!-- Clinic Overview Count Modal -->
<x-clinic-overview-count-modal/>

<!-- Hospital Booking Modal -->
<x-hospital-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''" :procedures="$procedures" :flag="0" :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />

<!-- Include your bookAppointmentModal component -->
<x-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''" :appointmentTypes="$appointmentTypes" :flag="0" :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />



@endpush


@push('scripts')
<script src="{{ URL::asset('/assets/plugins/fullcalendar/3.10.2/fullcalendar.min.js') }}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script> --}}
<script src="{{ URL::asset('/assets/js/popupForm.js') }}"></script>
<script>
    window.Laravel = {
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ URL::asset('/assets/js/calendar.js') }}"></script>

<script>
    const routes = {
        fetchAppointments: "{{ $patient ?guard_route('patients.appointments.byDate', ['patient' => $patient->id]) :guard_route('appointments.byDateGlobal') }}"
        , storeAppointment: "{{ $patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal') }}"
        , storeHospitalAppointment: "{{ $patient ?guard_route('hospital_appointments.store', ['patient' => $patient->id]) :guard_route('hospital_appointments.storeGlobal') }}"
        , destroyAppointment: (appointmentId, patientId) =>
            `{{guard_route('patients.appointments.destroy', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
            .replace('__PATIENT_ID__', patientId)
            .replace('__APPOINTMENT_ID__', appointmentId),

        statusAppointment: (appointmentId, patientId) =>
            `{{guard_route('patients.appointments.updateStatus', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
            .replace('__PATIENT_ID__', patientId)
            .replace('__APPOINTMENT_ID__', appointmentId)
        , reportUrl: "{{ guard_route('reports.entire-day') }}",
		
		//set caledar days
        calendarDays: "{{ guard_route('calendar.days') }}",
		savecalendarDays: "{{ guard_route('calendar.store') }}",
		
		//move appoitnments
		appointmentsForDate: "{{ guard_route('appointments.forDate') }}",
        appointmentsAvailableSlots: "{{ guard_route('appointments.availableSlots') }}",
        appointmentsMove: "{{ guard_route('appointments.move') }}",
		
		//Clinic overview appoitnments
        appointmentsClinicOverviewCounts: "{{ guard_route('appointments.clinicOverviewCounts') }}",

		// Check slot availability
		checkSlot: (clinicId, appointmentId = '') =>
        	`{{ guard_route('patients.appointments.checkSlot') }}?clinic_id=${clinicId}&appointment_id=${appointmentId}`,

		// Update appointment time (dynamic)
		updateTime: (appointmentId) =>
			`{{ guard_route('patients.appointments.updateTime', ['appointment' => '__APPOINTMENT_ID__']) }}`
				.replace('__APPOINTMENT_ID__', appointmentId),

		// Fetch clinic schedule (dynamic)
		clinicSchedule: (clinicId) =>
			`{{ guard_route('clinic.schedule', ['clinic' => '__CLINIC_ID__']) }}`
				.replace('__CLINIC_ID__', clinicId),

				 // Store or update appointment dynamically
		storeOrUpdateAppointment: (appointmentId = '') => {
			// Use named route for index
			const base = "{{ guard_route('patients.appointments.index') }}";
			return appointmentId ? `${base}/${appointmentId}` : base;
        },

    };
    let selectedClinic = null;
    let selectedClinicType = null;
    let clinicSchedule = null;

    $("#clinicFilter").on("change", function() {

        let clinicID = $(this).val();
        if (!clinicID) return;

		$.get(routes.clinicSchedule(clinicID), function(schedule) {
			clinicSchedule = schedule;
			applyBusinessHours();
		});

        $('#calendar').fullCalendar('refetchEvents');
    });

    function applyBusinessHours() {
        let businessHours = [];
        let intervalMinutes = 15; // default slot duration

        Object.keys(clinicSchedule).forEach(day => {
            let data = clinicSchedule[day];

            if (!data.active) return;

            // Use the first active day's interval as slotDuration
            if (!intervalMinutes && data.interval) {
                intervalMinutes = data.interval;
            }

            data.business.forEach(range => {
                businessHours.push({
                    dow: [data.dow], // 0=Sun ... 6=Sat
                    start: range.start
                    , end: range.end
                });
            });
        });

        $('#calendar').fullCalendar('option', {
            businessHours: businessHours
            , slotDuration: '00:' + (intervalMinutes < 10 ? '0' + intervalMinutes : intervalMinutes) + ':00'
        });
    }

    $(document).ready(function() {
        let firstClinicId = $('#clinicFilter').val();
		var indexurl = routes.storeOrUpdateAppointment(); // Use centralized route

        var calendar = $('#calendar').fullCalendar({
            editable: true
            , selectable: true
            , eventLimit: true
            , header: {
                left: 'prev,next today'
                , center: 'title'
                , right: 'month,agendaWeek,agendaDay' // Month, Week, Day views
            }
            , events: {
                url: indexurl
                , type: 'GET'
                , data: function() {
                    return {
                        clinic_id: $('#clinicFilter').val()
                    };
                }
            }
            , select: function(start, end) {
                if (!selectedClinic) {
					Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: 'Please select a clinic first.'
					});
                    return false;
                }

                let day = start.format("ddd").toLowerCase(); // mon, tue, wed...
                let dayData = clinicSchedule[day];

                if (!dayData || !dayData.active) {
					Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: 'Clinic closed this day!'
					});
                    return false;
                }

                let validSlots = generateTimeSlots(dayData);
                if (!validSlots.includes(start.format("HH:mm"))) {
					Swal.fire({
						icon: 'error',
						title: 'Error!',
						text: 'Invalid time Slot for this clinic Appointmnet.'
					});
                    $('#calendar').fullCalendar('unselect');
                    return false;
                }

                // Pre-fill modal
                $("#hospital_appointment_date").val(moment(start).format('YYYY-MM-DD'));
                $("#modal-appointment-date").val(moment(start).format('YYYY-MM-DD'));
                $("#start_time").val(start.format('HH:mm'));
                $("#end_time").val(end.format('HH:mm'));
                $("#appointment-clinic-id").val(selectedClinic);
                $("#hospital-clinic-id").val(selectedClinic);

                if (selectedClinicType === "hospital") {
                    $('#manualBookingForm').attr('data-action', routes.storeHospitalAppointment);
                    $("#manualBookingModal").modal("show");
                } else {
                    $("#bookAppointmentModal").modal("show");
                }
            },
            // Click on existing event
            // eventClick: function(event) {
            //     selectedClinicType = event.clinic_type;
            //     selectedClinic = event.clinic_id;

            //     fillAppointmentModal(event, selectedClinicType);

            // },
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

			eventSources: [
				{
					url: routes.calendarDays,
					method: 'GET',
				},
				// {
				// 	url: indexurl,
				// 	type: "GET",
				// 	data: function() {
				// 		return {
				// 			clinic_id: $('#clinicFilter').val()
				// 		};
				// 	}
				// }
			],
			eventAfterRender: function(event, element) {

				let selectedClinicId = $("#clinicFilter").val();

				// Do NOT show border if no clinic is selected
				if (!selectedClinicId) return;

				// Only apply border if event's clinic matches selected clinic
				if (event.clinic_id == selectedClinicId && event.allDay) {

					let date = moment(event.start).format("YYYY-MM-DD");

					let cell = $(".fc-day[data-date='" + date + "']");

					cell.css({
						"border": "2px solid " + event.clinicColor,
						"box-sizing": "border-box"
					});
				}
			},
            // ADD THESE
            eventDrop: function(event, delta, revertFunc) {
                handleEventMoveOrResize(event, revertFunc);
            }
            , eventResize: function(event, delta, revertFunc) {
                handleEventMoveOrResize(event, revertFunc);
            }
        });

		function openEditModal(event) {
			fillAppointmentModal(event, selectedClinicType);
			if (selectedClinicType === "hospital") {
                $('#manualBookingForm').attr('data-action', routes.storeHospitalAppointment);
				$("#manualBookingModal").modal("show");
			} else {
				$("#bookAppointmentModal").modal("show");
			}
		}

		// function openStatusModal(appointmentId, patientId, status)
		// {
		// 	$('#appointment_id').val(appointmentId);   
		// 	$('#patient_id').val(patientId);               
		// 	$('#appointment_status').val(status); 

		// 	let finalUrl = routes.statusAppointment(appointmentId, patientId); 
		// 	$('#statusChangeForm').attr('data-action', finalUrl);    

		// 	$('#statusChangeModal').modal('show'); 
		// }

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


        function handleEventMoveOrResize(event, revertFunc) {
            if (!clinicSchedule) {
				Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: 'Please select a clinic first.'
					});
                revertFunc();
                return;
            }

            let start = moment(event.start);
            let end = moment(event.end);

            let dayKey = start.format("ddd").toLowerCase(); // mon, tue ...

            let dayData = clinicSchedule[dayKey];

            if (!dayData || !dayData.active) {
				Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: 'Clinic is closed on this day.'
					});
                revertFunc();
                return;
            }

            // Generate allowed slots for this clinic
            let validSlots = generateTimeSlots(dayData);

            // Check if start time is valid slot
            if (!validSlots.includes(start.format("HH:mm"))) {
				Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: 'This time slot is not allowed for this clinic.'
					});
                revertFunc();
                return;
            }

            // -------------- CHECK IF SLOT IS ALREADY BOOKED ------------------
            $.ajax({
                url: routes.checkSlot(event.clinic_id, event.id),
				type: "GET",
                data: {
                    appointment_id: event.id,
                    patient_id: event.patient_id,
                    clinic_id: event.clinic_id,
                    date: start.format("YYYY-MM-DD"),
                    start_time: start.format("HH:mm"),
                    end_time: end.format("HH:mm"),
                },
                success: function(res) {
                    if (!res.available) {
                        Swal.fire({
							icon: 'error',
							title: 'Error!',
							text: 'This slot is already booked.'
						});
                        revertFunc();
                        return;
                    }

                    // -------------- UPDATE APPOINTMENT ------------------
                    $.ajax({
                        url: routes.updateTime(event.id),
						type: "PUT",
                        data: {
                            date: start.format("YYYY-MM-DD"),
                            start_time: start.format("HH:mm"),
                            end_time: end.format("HH:mm"),
                            _token: "{{ csrf_token() }}"
                        },
                        success: function() {
                            $('#calendar').fullCalendar('refetchEvents');

                            Swal.fire({
								icon: 'success',
								title: 'Success!',
								text: 'Appointment Updated successfully!'
							});
                        },
                        error: function() {
							Swal.fire({
								icon: 'error',
								title: 'Error!',
								text: 'Update failed.'
							});
                            revertFunc();
                        }
                    });
                }
            });
        }

        function generateTimeSlots(dayData) {
            let slots = [];
            if (!dayData.active) return slots;

            dayData.business.forEach(range => {
                let start = moment(range.start, "HH:mm");
                let end = moment(range.end, "HH:mm");

                while (start < end) {
                    slots.push(start.format("HH:mm"));
                    start.add(dayData.interval, 'minutes');
                }
            });

            return slots;
        }


        if (firstClinicId) {
            fetchClinicSchedule(firstClinicId);
        }

        $('#clinicFilter').on('change', function() {
            let clinicID = $(this).val();
            fetchClinicSchedule(clinicID);
            $('#calendar').fullCalendar('refetchEvents');
        });

        function fetchClinicSchedule(clinicID) {
            if (!clinicID) return;

			$.get(routes.clinicSchedule(clinicID), function(schedule) {
				clinicSchedule = schedule;
				selectedClinic = clinicID;
				selectedClinicType = $("#clinicFilter option:selected").data("type");
				applyBusinessHours();
			});
        }
        // Initialize Book Appointment Form
        PopupForm.init('#bookAppointmentModal', '#bookAppointmentForm', function(response) {
            $('#calendar').fullCalendar('refetchEvents');
			Swal.fire({
				icon: 'success',
				title: 'Success!',
				text: 'Appointment booked successfully!'
			});
        });

        // Initialize Hospital Booking Form
        PopupForm.init('#manualBookingModal', '#manualBookingForm', function(response) {
            $('#calendar').fullCalendar('refetchEvents');
			Swal.fire({
				icon: 'success',
				title: 'Success!',
				text: 'Hospital Appointment booked successfully!'
			});
        });

        // Optional: reset form on modal close
        $('#bookAppointmentModal, #manualBookingModal').on('hidden.bs.modal', function() {
            PopupForm.reset(this);
        });

    });

    $('#patient-id').on('change', function() {
        const selectedOption = $(this).find(':selected');
        const dob = selectedOption.data('dob') || '';
        const consultant = selectedOption.data('consultant') || '';

        $('#modal-dob').val(dob);
        $('#clinic_consultant').val(consultant);
    });
    // $('#bookAppointmentModal .select2').select2({
    // 	dropdownParent: $('#bookAppointmentModal') // Replace with modal ID if needed
    // });

    $('#hospital-patient-id').on('change', function() {
        const selectedOption = $(this).find(':selected');
        const dob = selectedOption.data('dob') || '';
        const consultant = selectedOption.data('consultant') || '';

        $('#hospital-dob').val(dob);
        $('#consultant').val(consultant);
    });
</script>
@endpush