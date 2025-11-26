@extends('layout.mainlayout')
@push('styles')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">
@endpush
@section('content')
	@component('components.admin.breadcrumb')
		@slot('title')
		Patient
		@endslot
		@slot('li_1')
		Patient Diary
		@endslot
		@slot('li_2')
		Patient Diary
		@endslot
	@endcomponent
	<!-- Page Content -->
	<div class="content">
		<div class="container mt-5">
			<div class="row mb-3">
				<div class="col-md-4">
					<select id="clinicFilter" class="form-control">
						@foreach($clinics as $clinic)
							<option value="{{ $clinic->id }}" data-type="{{ $clinic->clinic_type }}">{{ $clinic->name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div id="calendar"></div>
		</div>
	</div>
@endsection

@push('modals')
	<!-- Hospital Booking Modal -->
	<x-hospital-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''" :procedures="$procedures" :flag="0" :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />
	
	<!-- Include your bookAppointmentModal component -->
	<x-appointment-modal :clinics="$clinics" :patients="$patients" :patient="$patient ? $patient : ''" :appointmentTypes="$appointmentTypes" :flag="0" :action="$patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal')" />

	<!-- Status Change Modal -->
	<x-status-modal :diary_status="$diary_status" :flag="0" />
@endpush


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="{{ URL::asset('/assets/js/popupForm.js') }}"></script>
<script>
	const routes = {
        fetchAppointments: "{{ $patient ?guard_route('patients.appointments.byDate', ['patient' => $patient->id]) :guard_route('appointments.byDateGlobal') }}",
        storeAppointment: "{{ $patient ?guard_route('patients.appointments.store', ['patient' => $patient->id]) :guard_route('appointments.storeGlobal') }}",
        storeHospitalAppointment: "{{ $patient ?guard_route('hospital_appointments.store', ['patient' => $patient->id]) :guard_route('hospital_appointments.storeGlobal') }}",
        destroyAppointment: (appointmentId, patientId) =>
            `{{guard_route('patients.appointments.destroy', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
                .replace('__PATIENT_ID__', patientId)
                .replace('__APPOINTMENT_ID__', appointmentId),

        statusAppointment: (appointmentId, patientId) =>
            `{{guard_route('patients.appointments.updateStatus', ['patient' => '__PATIENT_ID__', 'appointment' => '__APPOINTMENT_ID__']) }}`
                .replace('__PATIENT_ID__', patientId)
                .replace('__APPOINTMENT_ID__', appointmentId),
        reportUrl: "{{ guard_route('reports.entire-day') }}"

    };
	let selectedClinic = null;
	let selectedClinicType = null;
	let clinicSchedule = null;

	$("#clinicFilter").on("change", function() {

		let clinicID = $(this).val();
		if (!clinicID) return;

		$.get(`/patient/${clinicID}/schedule`, function(schedule) {
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
					dow: [data.dow],  // 0=Sun ... 6=Sat
					start: range.start,
					end:   range.end
				});
			});
		});

		$('#calendar').fullCalendar('option', {
			businessHours: businessHours,
			slotDuration: '00:' + (intervalMinutes < 10 ? '0' + intervalMinutes : intervalMinutes) + ':00'
		});
	}

	$(document).ready(function() {
		let firstClinicId = $('#clinicFilter').val();

		var calendar = $('#calendar').fullCalendar({
			editable: true,
			selectable: true,
			eventLimit: true,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay' // Month, Week, Day views
			},
			events: {
				url: '/patient/appointmentindex',
				type: 'GET',
				data: function() {
					return {
						clinic_id: $('#clinicFilter').val()
					};
				}
			},
			select: function(start, end) {
				if (!selectedClinic) {
					alert("Please select a clinic first.");
					return false;
				}

				let day = start.format("ddd").toLowerCase(); // mon, tue, wed...
				let dayData = clinicSchedule[day];

				if (!dayData || !dayData.active) {
					alert("Clinic closed this day.");
					return false;
				}

				let validSlots = generateTimeSlots(dayData);
				if (!validSlots.includes(start.format("HH:mm"))) {
					alert("Invalid time Slot for this clinic Appointmnet.");
					$('#calendar').fullCalendar('unselect');
					return false;
				}

				// Pre-fill modal
				$("#modal-appointment-date").val(moment(start).format('YYYY-MM-DD'));
				$("#start_time").val(start.format('HH:mm'));
				$("#end_time").val(end.format('HH:mm'));
				$("#appointment-clinic-id").val(selectedClinic);

				if (selectedClinicType === "hospital") {
					$("#manualBookingModal").modal("show");
				} else {
					$("#bookAppointmentModal").modal("show");
				}
			},
			// Click on existing event
			eventClick: function(event) {
				selectedClinicType = event.clinic_type;
				selectedClinic = event.clinic_id;

				$("#appointment-id").val(event.id);
				$("#appointment-patient-id").val(event.patient_id);
				$("#patient-id").val(event.patient_id);
				// Fill modal fields
				$("#modal-patient-name").val(event.patient_name);
				$("#modal-dob").val(event.dob);
				$("#clinic_consultant").val(event.consultant);
				$("#appointment_type").val(event.appointment_type);
				$("#modal-appointment-date").val(moment(event.start).format('YYYY-MM-DD'));
				$("#start_time").val(moment(event.start).format('HH:mm'));
				$("#end_time").val(moment(event.end).format('HH:mm'));
				$("#patient_need").val(event.patient_need);
				$("#appointment_note").val(event.appointment_note);

				$("#hospital-clinic-id").val(selectedClinic);
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
				$("#appointment-clinic-id").val(selectedClinic);

				let finalUrl = routes.storeHospitalAppointment;
				$('#manualBookingForm').attr('data-action', finalUrl);
				
				
				// Set slots radio button
				$(".apt-slot-radio").prop('checked', false);
				if(event.apt_slots) {
					$("#slot" + event.apt_slots).prop('checked', true);
				} else {
					$("#slot1").prop('checked', true);
				}

				// Open correct modal
				if (selectedClinicType === "hospital") {
					$("#manualBookingModal").modal("show");
				} else {
					$("#bookAppointmentModal").modal("show");
				}
			}
		});


		// Save or update event
		$('#eventForm').submit(function(e){
			e.preventDefault();

			var id = $('#eventId').val();
			var method = id ? 'PUT' : 'POST';
			var url = id ? '/patient/appointmentindex/' + id : '/patient/appointmentindex';

			$.ajax({
				url: url,
				type: method,
				data: {
					title: $('#eventTitle').val(),
					start: $('#eventStart').val(),
					end: $('#eventEnd').val(),
					category: $('#eventCategory').val(),
					_token: '{{ csrf_token() }}'
				},
				success: function() {
					$('#eventModal').modal('hide');
					$('#calendar').fullCalendar('refetchEvents');
				}
			});
		});

		// Delete event
		$('#deleteEvent').click(function() {
			var id = $('#eventId').val();
			$.ajax({
				url: '/patient/appointmentindex/' + id,
				type: 'DELETE',
				data: { _token: '{{ csrf_token() }}' },
				success: function() {
					$('#eventModal').modal('hide');
					$('#calendar').fullCalendar('refetchEvents');
				}
			});
		});

		// Refetch events when clinic filter changes
		// $('#clinicFilter').on('change', function() {
		// 	$('#calendar').fullCalendar('refetchEvents');
		// 	selectedClinic = $(this).val();
		// 	selectedClinicType = $("#clinicFilter option:selected").data("type");
		// });
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

	
    if(firstClinicId) {
        fetchClinicSchedule(firstClinicId);
    }

    $('#clinicFilter').on('change', function() {
        let clinicID = $(this).val();
        fetchClinicSchedule(clinicID);
        $('#calendar').fullCalendar('refetchEvents');
    });

    function fetchClinicSchedule(clinicID) {
        if (!clinicID) return;

        $.get(`/patient/${clinicID}/schedule`, function(schedule) {
            clinicSchedule = schedule;
            selectedClinic = clinicID;
            selectedClinicType = $("#clinicFilter option:selected").data("type");
            applyBusinessHours();
        });
    }
	 // Initialize Book Appointment Form
	 PopupForm.init('#bookAppointmentModal', '#bookAppointmentForm', function(response) {
            $('#calendar').fullCalendar('refetchEvents');
            alert('Appointment booked successfully!');
        });

        // Initialize Hospital Booking Form
        PopupForm.init('#manualBookingModal', '#manualBookingForm', function(response) {
            $('#calendar').fullCalendar('refetchEvents');
            alert('Hospital appointment booked successfully!');
        });

        // Optional: reset form on modal close
        $('#bookAppointmentModal, #manualBookingModal').on('hidden.bs.modal', function () {
            PopupForm.reset(this);
        });

});

$('#patient-id').on('change', function () {
	const selectedOption = $(this).find(':selected');
	const dob = selectedOption.data('dob') || '';
	const consultant = selectedOption.data('consultant') || '';

	$('#modal-dob').val(dob);
	$('#clinic_consultant').val(consultant);
});
// $('#bookAppointmentModal .select2').select2({
// 	dropdownParent: $('#bookAppointmentModal') // Replace with modal ID if needed
// });

$('#hospital-patient-id').on('change', function () {
	const selectedOption = $(this).find(':selected');
	const dob = selectedOption.data('dob') || '';
	const consultant = selectedOption.data('consultant') || '';

	$('#hospital-dob').val(dob);
	$('#consultant').val(consultant);
});
// $('#manualBookingModal .select2').select2({
// 	dropdownParent: $('#manualBookingModal')
// });

</script>
@endpush
