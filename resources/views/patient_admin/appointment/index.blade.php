<?php $page = 'patient-dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
@component('components.admin.breadcrumb')
@slot('title')
Patient
@endslot
@slot('li_1')
Appointments 
@endslot
@slot('li_2')
Appointments
@endslot
@endcomponent
<!-- Page Content -->
<div class="content">
    <div class="container">

        <div class="row">

            <!-- Profile Sidebar -->
            @component('components.admin.sidebar_patient', ['patient' => $patient])
			@endcomponent

            <!-- / Profile Sidebar -->

            <div class="col-lg-8 col-xl-9">
				<div class="dashboard-header">
					<h3>Appointments</h3>
					<ul class="header-list-btns">
						<li>
							<div class="input-block dash-search-input">
								<input type="text" class="form-control" placeholder="Search">
								<span class="search-icon"><i class="isax isax-search-normal"></i></span>
							</div>
						</li>
					</ul>
				</div>
				<div class="appointment-tab-head">
					<div class="appointment-tabs">
						<ul class="nav nav-pills inner-tab " id="pills-tab" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link active" id="pills-upcoming-tab" data-bs-toggle="pill" data-bs-target="#pills-upcoming" type="button" role="tab" aria-controls="pills-upcoming" aria-selected="false">Upcoming<span>{{ $upcomingCount }}</span></button>
							</li>	
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-complete-tab" data-bs-toggle="pill" data-bs-target="#pills-complete" type="button" role="tab" aria-controls="pills-complete" aria-selected="true">Completed<span>{{ $completedCount }}</span></button>
							</li>
						</ul>
					</div>
					{{-- <div class="filter-head">
						<div class="form-sorts dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" id="table-filter"><i class="isax isax-filter me-2"></i>Filter By</a>
							<div class="filter-dropdown-menu">
								<div class="filter-set-view">
									<div class="accordion" id="accordionExample">
										<div class="filter-set-content">
											<div class="filter-set-content-head">
												<a href="#" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Name<i class="fa-solid fa-chevron-right"></i></a>
											</div>
											<div class="filter-set-contents accordion-collapse collapse show" id="collapseTwo" data-bs-parent="#accordionExample">
												<ul>
													<li>
														<div class="input-block dash-search-input w-100">
															<input type="text" class="form-control" placeholder="Search">
															<span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
														</div>
													</li>
												</ul>
											</div>
										</div>
										<div class="filter-set-content">
											<div class="filter-set-content-head">
												<a href="#" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Appointment Type<i class="fa-solid fa-chevron-right"></i></a>
											</div>
											<div class="filter-set-contents accordion-collapse collapse show" id="collapseOne" data-bs-parent="#accordionExample">
												<ul>
													<li>
														<div class="filter-checks">
															<label class="checkboxs">
																<input type="checkbox" checked>
																<span class="checkmarks"></span>
																<span class="check-title">All Type</span>
															</label>
														</div>																
													</li>
													<li>
														<div class="filter-checks">
															<label class="checkboxs">
																<input type="checkbox">
																<span class="checkmarks"></span>
																<span class="check-title">Video Call</span>
															</label>
														</div>																
													</li>
													<li>
														<div class="filter-checks">
															<label class="checkboxs">
																<input type="checkbox">
																<span class="checkmarks"></span>
																<span class="check-title">Audio Call</span>
															</label>
														</div>																
													</li>
													<li>
														<div class="filter-checks">
															<label class="checkboxs">
																<input type="checkbox">
																<span class="checkmarks"></span>
																<span class="check-title">Chat</span>
															</label>
														</div>																
													</li>
													<li>
														<div class="filter-checks">
															<label class="checkboxs">
																<input type="checkbox">
																<span class="checkmarks"></span>
																<span class="check-title">Direct Visit</span>
															</label>
														</div>																
													</li>
												</ul>
											</div>
										</div>												
										<div class="filter-set-content">
											<div class="filter-set-content-head">
												<a href="#" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Visit Type<i class="fa-solid fa-chevron-right"></i></a>
											</div>
											<div class="filter-set-contents accordion-collapse collapse show" id="collapseThree" data-bs-parent="#accordionExample">
												<ul>
													<li>
														<div class="filter-checks">
															<label class="checkboxs">
																<input type="checkbox" checked>
																<span class="checkmarks"></span>
																<span class="check-title">All Visit</span>
															</label>
														</div>
														
													</li>
													<li>
														<div class="filter-checks">
															<label class="checkboxs">
																<input type="checkbox">
																<span class="checkmarks"></span>
																<span class="check-title">General</span>
															</label>
														</div>
														
													</li>
													<li>
														<div class="filter-checks">
															<label class="checkboxs">
																<input type="checkbox">
																<span class="checkmarks"></span>
																<span class="check-title">Consultation</span>
															</label>
														</div>
														
													</li>
													<li>
														<div class="filter-checks">
															<label class="checkboxs">
																<input type="checkbox">
																<span class="checkmarks"></span>
																<span class="check-title">Follow-up</span>
															</label>
														</div>
														
													</li>
													<li>
														<div class="filter-checks">
															<label class="checkboxs">
																<input type="checkbox">
																<span class="checkmarks"></span>
																<span class="check-title">Direct Visit</span>
															</label>
														</div>
														
													</li>
												</ul>
											</div>
										</div>
									</div>
									
									<div class="filter-reset-btns">
										<a href="#" class="btn btn-light">Reset</a>
										<a href="#" class="btn btn-primary">Filter Now</a>
									</div>
								</div>
							</div>
						</div>
					</div> --}}
				</div>

				<div class="tab-content appointment-tab-content appoint-patient">
					<div class="tab-pane fade show active" id="pills-upcoming" role="tabpanel" aria-labelledby="pills-upcoming-tab">
						<div class="row load-container" id="upcoming-load" data-type="upcoming">
							@include('patient_admin.appointment.partials.cards', ['appointments' => $appointments])
						</div>
					</div>
					
					<div class="tab-pane fade" id="pills-complete" role="tabpanel" aria-labelledby="pills-complete-tab">
						<div class="row load-container d-none" id="completed-load" data-type="completed">
						</div>
					</div>
				</div>
				
			</div>
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
@push('scripts')
<script src="{{ URL::asset('/assets/js/popupForm.js') }}"></script>
<script src="{{ URL::asset('/assets/js/calendar.js') }}"></script>
<script>
    window.Laravel = {
        csrfToken: "{{ csrf_token() }}"
    };
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
	};

	// Load Completed tab on first click
	$('#pills-complete-tab').one('click', function () {
		let container = $('#completed-load');

		$.ajax({
			url: "{{ guard_route('patients.appointments.main.index', $patient->id) }}",
			data: { type: 'completed' },
			success: function (html) {
				container.html(html).removeClass('d-none');
			}
		});
	});

	// Initialize Book Appointment Form
	PopupForm.init('#bookAppointmentModal', '#bookAppointmentForm', function(response) {
			Swal.fire({
				icon: 'success',
				title: 'Success!',
				text: 'Appointment booked successfully!'
			});
        });

        // Initialize Hospital Booking Form
        PopupForm.init('#manualBookingModal', '#manualBookingForm', function(response) {
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
        const consultant = button.dataset.consultant;

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
        document.getElementById('consultant').value = consultant;

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
    $('#clinic_consultant').val(btn.data('consultant'));
    $('#modal-patient-name').val(btn.data('patient_name') || '');
    $('#modal-dob').val(btn.data('dob') || '');
    $('#appointment-clinic-id').val(btn.data('clinic_id') || '');

    let appointmentId = btn.data('id');
    let route = btn.data('action');

    $('#bookAppointmentForm').attr('action', route);

    $('#bookAppointmentModal').modal('show');
});
</script>
@endpush
