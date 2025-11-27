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
								<button class="nav-link active" id="pills-upcoming-tab" data-bs-toggle="pill" data-bs-target="#pills-upcoming" type="button" role="tab" aria-controls="pills-upcoming" aria-selected="false">Upcoming<span>21</span></button>
							</li>	
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-cancel-tab" data-bs-toggle="pill" data-bs-target="#pills-cancel" type="button" role="tab" aria-controls="pills-cancel" aria-selected="true">Cancelled<span>16</span></button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-complete-tab" data-bs-toggle="pill" data-bs-target="#pills-complete" type="button" role="tab" aria-controls="pills-complete" aria-selected="true">Completed<span>214</span></button>
							</li>
						</ul>
					</div>
					<div class="filter-head">
						<div class="position-relative daterange-wraper me-2">
							<div class="input-groupicon calender-input">
								<input type="text" class="form-control  date-range bookingrange" placeholder="From Date - To Date ">
							</div>
							<i class="isax isax-calendar-1"></i>
						</div>
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
										<a href="appointments.html" class="btn btn-md btn-light rounded-pill">Reset</a>
										<a href="appointments.html" class="btn btn-md btn-primary-gradient rounded-pill">Filter Now</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-content appointment-tab-content appoint-patient">
					<div class="tab-pane fade show active" id="pills-upcoming" role="tabpanel" aria-labelledby="pills-upcoming-tab">
						<div class="row">
						

							@foreach ($appointments as $appointment)
								<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
									<div class="appointment-wrap appointment-grid-wrap">
										<ul>
											<li>
												<div class="appointment-grid-head">
													<div class="patinet-information">
														
														@if ($appointment->patient->patient_picture)
														<a href="#">
															<img src="{{ asset('storage/' . $appointment->patient->patient_picture) }}"
																	class="rounded-circle me-2" width="40" height="40" alt="Patient">
														</a>
														@else
															<div class="rounded-circle bg-secondary text-white text-center me-2"
																	style="width: 40px; height: 40px; line-height: 40px;">
																<i class="fas fa-user"></i>
															</div>
														@endif
														
														<div class="patient-info">
															<p>#Apt{{ $appointment->id }}</p>

															<h6>
																<a href="#">
																	{{ $appointment->patient->doctor->name ?? 'No Doctor Assigned' }}
																</a>
															</h6>

															<p class="visit">
																{{ $appointment->appointmentType->value ?? 'N/A' }}
															</p>
														</div>
													</div>

													<div class="grid-user-msg">
														<span class="video-icon">
															<a href="#"><i class="isax isax-hospital5"></i></a>
														</span>
													</div>
												</div>
											</li>

											<li class="appointment-info">
												<p>
													<i class="isax isax-calendar5"></i>
													{{ format_date($appointment->appointment_date) }}
												</p>

												<p>
													<i class="isax isax-clock5"></i>
													{{ format_time($appointment->start_time) }}
												</p>
											</li>

											<li class="appointment-action">
												<ul>
													<li><a href="#"><i class="isax isax-eye4"></i></a></li>
													<li><a href="#"><i class="isax isax-messages-25"></i></a></li>
													<li><a href="#"><i class="isax isax-close-circle5"></i></a></li>
												</ul>

												<div class="appointment-detail-btn">
													<a href="#" class="start-link">
														<i class="isax isax-calendar-tick5 me-1"></i>Attend
													</a>
												</div>
											</li>

										</ul>
									</div>
								</div>
							@endforeach						

							

							<div class="col-md-12">
								<div class="loader-item text-center">
									<a href="javascript:void(0);" class="btn btn-outline-primary rounded-pill">Load More</a>
								</div>
							</div>	

						</div>
					</div>
					<div class="tab-pane fade" id="pills-cancel" role="tabpanel" aria-labelledby="pills-cancel-tab">
						<div class="row">
							<!-- Appointment Grid -->
							<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
								<div class="appointment-wrap appointment-grid-wrap">
									<ul>
										<li>
											<div class="appointment-grid-head">
												<div class="patinet-information">
													<a href="patient-cancelled-appointment.html">
														<img src="assets/img/doctors/doctor-thumb-21.jpg" alt="User Image">
													</a>
													<div class="patient-info">
														<p>#Apt0001</p>
														<h6><a href="patient-cancelled-appointment.html">Dr Edalin Hendry</a></h6>
														<p class="visit">General Visit</p>
													</div>
												</div>
												<div class="grid-user-msg">
													<span class="video-icon"><a href="#"><i class="isax isax-video5"></i></a></span>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<p><i class="isax isax-calendar5"></i>11 Nov 2024</p>
											<p><i class="isax isax-clock5"></i>10.45 AM</p>
										</li>
										<li class="appointment-detail-btn">
											<a href="patient-cancelled-appointment.html" class="start-link w-100">View Details</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /Appointment Grid -->

							<!-- Appointment Grid -->
							<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
								<div class="appointment-wrap appointment-grid-wrap">
									<ul>
										<li>
											<div class="appointment-grid-head">
												<div class="patinet-information">
													<a href="patient-cancelled-appointment.html">
														<img src="assets/img/doctors/doctor-thumb-13.jpg" alt="User Image">
													</a>
													<div class="patient-info">
														<p>#Apt0002</p>
														<h6><a href="patient-cancelled-appointment.html">Dr.Shanta Nesmith</a></h6>
														<p class="visit">General Visit</p>
													</div>
												</div>
												<div class="grid-user-msg">
													<span class="video-icon"><a href="#"><i class="isax isax-video5"></i></a></span>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<p><i class="isax isax-calendar5"></i>05 Nov 2024</p>
											<p><i class="isax isax-clock5"></i>11.50 AM</p>
										</li>
										<li class="appointment-detail-btn">
											<a href="patient-cancelled-appointment.html" class="start-link w-100">View Details</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /Appointment Grid -->

							<!-- Appointment Grid -->
							<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
								<div class="appointment-wrap appointment-grid-wrap">
									<ul>
										<li>
											<div class="appointment-grid-head">
												<div class="patinet-information">
													<a href="patient-cancelled-appointment.html">
														<img src="assets/img/doctors/doctor-thumb-14.jpg" alt="User Image">
													</a>
													<div class="patient-info">
														<p>#Apt0003</p>
														<h6><a href="patient-cancelled-appointment.html">Dr.John Ewel</a></h6>
														<p class="visit">General Visit</p>
													</div>
												</div>
												<div class="grid-user-msg">
													<span class="video-icon"><a href="#"><i class="isax isax-video5"></i></a></span>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<p><i class="isax isax-calendar5"></i>27 Oct 2024</p>
											<p><i class="isax isax-clock5"></i>09.30 AM</p>
										</li>
										<li class="appointment-detail-btn">
											<a href="patient-cancelled-appointment.html" class="start-link w-100">View Details</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /Appointment Grid -->

							<!-- Appointment Grid -->
							<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
								<div class="appointment-wrap appointment-grid-wrap">
									<ul>
										<li>
											<div class="appointment-grid-head">
												<div class="patinet-information">
													<a href="patient-cancelled-appointment.html">
														<img src="assets/img/doctors/doctor-thumb-15.jpg" alt="User Image">
													</a>
													<div class="patient-info">
														<p>#Apt0004</p>
														<h6><a href="patient-cancelled-appointment.html">Dr.Susan Fenimore</a></h6>
														<p class="visit">General Visit</p>
													</div>
												</div>
												<div class="grid-user-msg">
													<span class="hospital-icon"><a href="#"><i class="isax isax-hospital5"></i></a></span>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<p><i class="isax isax-calendar5"></i>18 Oct 2024</p>
											<p><i class="isax isax-clock5"></i>12.20 PM</p>
										</li>
										<li class="appointment-detail-btn">
											<a href="patient-cancelled-appointment.html" class="start-link w-100">View Details</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /Appointment Grid -->

							<!-- Appointment Grid -->
							<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
								<div class="appointment-wrap appointment-grid-wrap">
									<ul>
										<li>
											<div class="appointment-grid-head">
												<div class="patinet-information">
													<a href="patient-cancelled-appointment.html">
														<img src="assets/img/doctors/doctor-thumb-16.jpg" alt="User Image">
													</a>
													<div class="patient-info">
														<p>#Apt0005</p>
														<h6><a href="patient-cancelled-appointment.html">Dr.Juliet Rios</a></h6>
														<p class="visit">General Visit</p>
													</div>
												</div>
												<div class="grid-user-msg">
													<span class="video-icon"><a href="#"><i class="isax isax-video5"></i></a></span>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<p><i class="isax isax-calendar5"></i>10 Oct 2024</p>
											<p><i class="isax isax-clock5"></i>11.30 AM</p>
										</li>
										<li class="appointment-detail-btn">
											<a href="patient-cancelled-appointment.html" class="start-link w-100">View Details</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /Appointment Grid -->

							<!-- Appointment Grid -->
							<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
								<div class="appointment-wrap appointment-grid-wrap">
									<ul>
										<li>
											<div class="appointment-grid-head">
												<div class="patinet-information">
													<a href="patient-cancelled-appointment.html">
														<img src="assets/img/doctors/doctor-thumb-17.jpg" alt="User Image">
													</a>
													<div class="patient-info">
														<p>#Apt0006</p>
														<h6><a href="patient-cancelled-appointment.html">Dr.Joseph Engels</a></h6>
														<p class="visit">General Visit</p>
													</div>
												</div>
												<div class="grid-user-msg">
													<span class="video-icon"><a href="#"><i class="isax isax-video5"></i></a></span>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<p><i class="isax isax-calendar5"></i>26 Sep 2024</p>
											<p><i class="isax isax-clock5"></i>10.20 AM</p>
										</li>
										<li class="appointment-detail-btn">
											<a href="patient-cancelled-appointment.html" class="start-link w-100">View Details</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /Appointment Grid -->

							<div class="col-md-12">
								<div class="loader-item text-center">
									<a href="javascript:void(0);" class="btn btn-outline-primary rounded-pill">Load More</a>
								</div>
							</div>									
						</div>
					</div>
					<div class="tab-pane fade" id="pills-complete" role="tabpanel" aria-labelledby="pills-complete-tab">
						<div class="row">
							<!-- Appointment Grid -->
							<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
								<div class="appointment-wrap appointment-grid-wrap">
									<ul>
										<li>
											<div class="appointment-grid-head">
												<div class="patinet-information">
													<a href="patient-completed-appointment.html">
														<img src="assets/img/doctors/doctor-thumb-21.jpg" alt="User Image">
													</a>
													<div class="patient-info">
														<p>#Apt0001</p>
														<h6><a href="patient-completed-appointment.html">Dr Edalin Hendry</a></h6>
														<p class="visit">General Visit</p>
													</div>
												</div>
												<div class="grid-user-msg">
													<span class="video-icon"><a href="#"><i class="isax isax-video5"></i></a></span>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<p><i class="isax isax-calendar5"></i>11 Nov 2024</p>
											<p><i class="isax isax-clock5"></i>10.45 AM</p>
										</li>
										<li class="appointment-detail-btn">
											<a href="patient-completed-appointment.html" class="start-link w-100">View Details</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /Appointment Grid -->

							<!-- Appointment Grid -->
							<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
								<div class="appointment-wrap appointment-grid-wrap">
									<ul>
										<li>
											<div class="appointment-grid-head">
												<div class="patinet-information">
													<a href="patient-completed-appointment.html">
														<img src="assets/img/doctors/doctor-thumb-13.jpg" alt="User Image">
													</a>
													<div class="patient-info">
														<p>#Apt0002</p>
														<h6><a href="patient-completed-appointment.html">Dr.Shanta Nesmith</a></h6>
														<p class="visit">General Visit</p>
													</div>
												</div>
												<div class="grid-user-msg">
													<span class="hospital-icon"><a href="#"><i class="isax isax-hospital5"></i></a></span>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<p><i class="isax isax-calendar5"></i>05 Nov 2024</p>
											<p><i class="isax isax-clock5"></i>11.50 AM</p>
										</li>
										<li class="appointment-detail-btn">
											<a href="patient-completed-appointment.html" class="start-link w-100">View Details</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /Appointment Grid -->

							<!-- Appointment Grid -->
							<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
								<div class="appointment-wrap appointment-grid-wrap">
									<ul>
										<li>
											<div class="appointment-grid-head">
												<div class="patinet-information">
													<a href="patient-completed-appointment.html">
														<img src="assets/img/doctors/doctor-thumb-14.jpg" alt="User Image">
													</a>
													<div class="patient-info">
														<p>#Apt0003</p>
														<h6><a href="patient-completed-appointment.html">Dr.John Ewel</a></h6>
														<p class="visit">General Visit</p>
													</div>
												</div>
												<div class="grid-user-msg">
													<span class="telephone-icon"><a href="#"><i class="isax isax-call5"></i></a></span>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<p><i class="isax isax-calendar5"></i>27 Oct 2024</p>
											<p><i class="isax isax-clock5"></i>09.30 AM</p>
										</li>
										<li class="appointment-detail-btn">
											<a href="patient-completed-appointment.html" class="start-link w-100">View Details</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /Appointment Grid -->

							<!-- Appointment Grid -->
							<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
								<div class="appointment-wrap appointment-grid-wrap">
									<ul>
										<li>
											<div class="appointment-grid-head">
												<div class="patinet-information">
													<a href="patient-completed-appointment.html">
														<img src="assets/img/doctors/doctor-thumb-15.jpg" alt="User Image">
													</a>
													<div class="patient-info">
														<p>#Apt0004</p>
														<h6><a href="patient-completed-appointment.html">Dr.Susan Fenimore</a></h6>
														<p class="visit">General Visit</p>
													</div>
												</div>
												<div class="grid-user-msg">
													<span class="hospital-icon"><a href="#"><i class="isax isax-hospital5"></i></a></span>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<p><i class="isax isax-calendar5"></i>18 Oct 2024</p>
											<p><i class="isax isax-clock5"></i>12.20 PM</p>
										</li>
										<li class="appointment-detail-btn">
											<a href="patient-completed-appointment.html" class="start-link w-100">View Details</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /Appointment Grid -->

							<!-- Appointment Grid -->
							<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
								<div class="appointment-wrap appointment-grid-wrap">
									<ul>
										<li>
											<div class="appointment-grid-head">
												<div class="patinet-information">
													<a href="patient-completed-appointment.html">
														<img src="assets/img/doctors/doctor-thumb-16.jpg" alt="User Image">
													</a>
													<div class="patient-info">
														<p>#Apt0005</p>
														<h6><a href="patient-completed-appointment.html">Dr.Juliet Rios</a></h6>
														<p class="visit">General Visit</p>
													</div>
												</div>
												<div class="grid-user-msg">
													<span class="video-icon"><a href="#"><i class="isax isax-video5"></i></a></span>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<p><i class="isax isax-calendar5"></i>10 Oct 2024</p>
											<p><i class="isax isax-clock5"></i>11.30 AM</p>
										</li>
										<li class="appointment-detail-btn">
											<a href="patient-completed-appointment.html" class="start-link w-100">View Details</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /Appointment Grid -->

							<!-- Appointment Grid -->
							<div class="col-xl-4 col-lg-6 col-md-12 d-flex">
								<div class="appointment-wrap appointment-grid-wrap">
									<ul>
										<li>
											<div class="appointment-grid-head">
												<div class="patinet-information">
													<a href="patient-completed-appointment.html">
														<img src="assets/img/doctors/doctor-thumb-17.jpg" alt="User Image">
													</a>
													<div class="patient-info">
														<p>#Apt0006</p>
														<h6><a href="patient-completed-appointment.html">Dr.Joseph Engels</a></h6>
														<p class="visit">General Visit</p>
													</div>
												</div>
												<div class="grid-user-msg">
													<span class="video-icon"><a href="#"><i class="isax isax-video5"></i></a></span>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<p><i class="isax isax-calendar5"></i>26 Sep 2024</p>
											<p><i class="isax isax-clock5"></i>10.20 AM</p>
										</li>
										<li class="appointment-detail-btn">
											<a href="patient-completed-appointment.html" class="start-link w-100">View Details</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /Appointment Grid -->

							<div class="col-md-12">
								<div class="loader-item text-center">
									<a href="javascript:void(0);" class="btn btn-outline-primary rounded-pill">Load More</a>
								</div>
							</div>	

						</div>
					</div>
				</div>
				
			</div>
        </div>

    </div>

</div>
<!-- /Page Content -->
@endsection