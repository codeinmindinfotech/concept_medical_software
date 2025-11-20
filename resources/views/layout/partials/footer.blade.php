@if (Route::is(['index-2']))
    <!-- Footer -->
    <footer class="footer">

        <!-- Footer Top -->
        <div class="footer-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-6">

                        <!-- Footer Widget -->
                        <div class="footer-widget footer-about">
                            <div class="footer-logo">
                                <img src="{{ URL::asset('/assets/img/footer-logo.png') }}" alt="logo">
                            </div>
                            <div class="footer-about-content">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua. </p>
                                <div class="social-icon">
                                    <ul>
                                        <li>
                                            <a href="javascript:;" target="_blank"><i class="fab fa-facebook-f"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" target="_blank"><i class="fab fa-twitter"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" target="_blank"><i
                                                    class="fab fa-linkedin-in"></i></a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" target="_blank"><i class="fab fa-instagram"></i></a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" target="_blank"><i class="fab fa-dribbble"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /Footer Widget -->

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <!-- Footer Widget -->
                        <div class="footer-widget footer-menu">
                            <h2 class="footer-title">For Patients</h2>
                            <ul>
                                <li><a href="{{ url('search') }}">Search for Doctors</a></li>
                                <li><a href="{{ url('login') }}">Login</a></li>
                                <li><a href="{{ url('register') }}">Register</a></li>
                                <li><a href="{{ url('booking') }}">Booking</a></li>
                                <li><a href="{{ url('patient-dashboard') }}">Patient Dashboard</a></li>
                            </ul>
                        </div>
                        <!-- /Footer Widget -->

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <!-- Footer Widget -->
                        <div class="footer-widget footer-menu">
                            <h2 class="footer-title">For Doctors</h2>
                            <ul>
                                <li><a href="{{ url('appointments') }}">Appointments</a></li>
                                <li><a href="{{ url('chat') }}">Chat</a></li>
                                <li><a href="{{ url('login') }}">Login</a></li>
                                <li><a href="{{ url('doctor-register') }}">Register</a></li>
                                <li><a href="{{ url('doctor-dashboard') }}">Doctor Dashboard</a></li>
                            </ul>
                        </div>
                        <!-- /Footer Widget -->

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <!-- Footer Widget -->
                        <div class="footer-widget footer-contact">
                            <h2 class="footer-title">Contact Us</h2>
                            <div class="footer-contact-info">
                                <div class="footer-address">
                                    <span><i class="fas fa-map-marker-alt"></i></span>
                                    <p> 3556 Beech Street, San Francisco,<br> California, CA 94108 </p>
                                </div>
                                <p>
                                    <i class="fas fa-phone-alt"></i>
                                    +1 315 369 5943
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-envelope"></i>
                                    doccure@example.com
                                </p>
                            </div>
                        </div>
                        <!-- /Footer Widget -->

                    </div>

                </div>
            </div>
        </div>
        <!-- /Footer Top -->

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container-fluid">

                <!-- Copyright -->
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="copyright-text">
                                <p class="mb-0">&copy;
                                    <script>
                                        document.write(new Date().getFullYear())
                                    </script> Doccure. All rights reserved.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">

                            <!-- Copyright Menu -->
                            <div class="copyright-menu">
                                <ul class="policy-menu">
                                    <li><a href="{{ url('terms-condition') }}">Terms and Conditions </a></li>
                                    <li><a href="{{ url('privacy-policy') }}">Policy</a></li>
                                </ul>
                            </div>
                            <!-- /Copyright Menu -->

                        </div>
                    </div>
                </div>
                <!-- /Copyright -->

            </div>
        </div>
        <!-- /Footer Bottom -->

    </footer>
    <!-- /Footer -->
@endif
@if (Route::is(['index-4']))
<!-- Footer -->
<footer class="footer footer-one">
    <div class="footer-top aos" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="footer-widget footer-about">
                        <div class="footer-logo">
                            <a href="{{url('index')}}"><img src="{{URL::asset('assets/img/logo.svg')}}" alt="logo"></a>
                        </div>
                        <div class="footer-about-content">
                            <p>Effortlessly schedule your medical appointments with Doccure. Connect with healthcare professionals, manage appointments & prioritize your well being </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <div class="footer-widget footer-menu">
                                <h2 class="footer-title">Company</h2>
                                <ul>
                                    <li><a href="{{url('index')}}">Home</a></li>
                                    <li><a href="{{url('search')}}">Specialities</a></li>
                                    <li><a href="{{url('video-call')}}">Video Consult</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="footer-widget footer-menu">
                                <h2 class="footer-title">Specialities</h2>
                                <ul>
                                    <li><a href="{{url('search')}}">Neurology</a></li>
                                    <li><a href="{{url('search')}}">Cardiologist</a></li>
                                    <li><a href="{{url('search')}}">Dentist</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-4">
                            <div class="footer-widget footer-contact">
                                <h2 class="footer-title">Contact Us</h2>
                                <div class="footer-contact-info">
                                    <div class="footer-address">
                                        <p><i class="isax isax-location"></i> 3556 Beech Street, USA</p>
                                    </div>
                                    <div class="footer-address">
                                        <p><i class="feather-phone-call"></i> +1 315 369 5943</p>
                                    </div>
                                    <div class="footer-address mb-0">
                                        <p><i class="feather-mail"></i> doccure@example.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-7">
                    <div class="footer-widget">
                        <h2 class="footer-title">Join Our Newsletter</h2>
                        <div class="subscribe-form">
                            <form action="#">
                                <input type="email" class="form-control" placeholder="Enter Email">
                                <button type="submit" class="btn">Submit</button>
                            </form>
                        </div>
                        <div class="social-icon">
                        <ul>
                            
                            <li>
                                <a href="javascript:void(0);"><i class="fa-brands fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"><i class="fa-brands fa-x-twitter"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"><i class="fa-brands fa-linkedin"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"><i class="fa-brands fa-pinterest"></i></a>
                            </li>
                        </ul>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <!-- Copyright -->
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <div class="copyright-text">
                            <p class="mb-0">Copyright © 2025 Doccure. All Rights Reserved</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                    
                        <!-- Copyright Menu -->
                        <div class="copyright-menu">
                            <ul class="policy-menu">
                                <li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
                                <li><a href="{{url('terms-condition')}}">Terms and Conditions</a></li>
                            </ul>
                        </div>
                        <!-- /Copyright Menu -->
                        
                    </div>
                </div>
            </div>
            <!-- /Copyright -->					
        </div>
    </div>
</footer>
<!-- /Footer -->
@endif

@if (Route::is(['index-3']))
   	<!-- Footer -->
		<footer class="footer footer-three">

			<!-- Footer Top -->
			<div class="footer-top">
				<div class="container">
					<div class="row">
						<div class="col-lg-3 col-md-6">
							<!-- Footer Widget -->
							<div class="footer-widget footer-about">
								<div class="footer-logo">
									<img src="{{URL::asset('assets/img/footer-logo.png')}}" alt="logo">
								</div>
								<div class="footer-about-content">
									<p>Effortlessly schedule your medical appointments with Doccure. Connect with healthcare professionals, manage appointments & prioritize your well being </p>
									<div class="social-icon">
										<ul>
											<li> <a href="javascript:void(0);"><i class="fa-brands fa-facebook"></i></a>
											</li>
											<li> <a href="javascript:void(0);"><i class="fab fa-twitter"></i> </a>
											</li>
											<li> <a href="javascript:void(0);"><i class="fab fa-linkedin-in"></i></a>
											</li>
											<li> <a href="javascript:void(0);"><i class="fab fa-instagram"></i></a>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<!-- /Footer Widget -->
						</div>
						<div class="col-lg-3 col-md-6">
							<!-- Footer Widget -->
							<div class="footer-widget footer-menu">
								<h2 class="footer-title">For Patients</h2>
								<ul>
									<li><a href="{{url('search')}}">Search for Doctors</a>
									</li>
									<li><a href="{{url('login')}}">Login</a>
									</li>
									<li><a href="{{url('register')}}">Register</a>
									</li>
									<li><a href="{{url('booking')}}">Booking</a>
									</li>
									<li><a href="{{url('patient-dashboard')}}">Patient Dashboard</a>
									</li>
								</ul>
							</div>
							<!-- /Footer Widget -->
						</div>
						<div class="col-lg-3 col-md-6">
							<!-- Footer Widget -->
							<div class="footer-widget footer-menu">
								<h2 class="footer-title">For Doctors</h2>
								<ul>
									<li><a href="{{url('appointments')}}">Appointments</a>
									</li>
									<li><a href="{{url('chat')}}">Chat</a>
									</li>
									<li><a href="{{url('login')}}">Login</a>
									</li>
									<li><a href="{{url('doctor-register')}}">Register</a>
									</li>
									<li><a href="{{url('doctor-dashboard')}}">Doctor Dashboard</a>
									</li>
								</ul>
							</div>
							<!-- /Footer Widget -->
						</div>
						<div class="col-lg-3 col-md-6">
							<!-- Footer Widget -->
							<div class="footer-widget footer-contact">
								<h2 class="footer-title">Contact Us</h2>
								<div class="footer-contact-info">
									<div class="footer-address"> <span><i class="isax isax-location5"></i></span>
										<p>3556 Beech Street, San Francisco,
											<br>California, CA 94108
										</p>
									</div>
									<p><i class="isax isax-call5"></i>+1 315 369 5943</p>
									<p class="mb-0"> <i class="isax isax-sms5"></i>
										doccure@example.com</p>
								</div>
							</div>
							<!-- /Footer Widget -->
						</div>
					</div>
				</div>
			</div>
			<!-- /Footer Top -->

			<!-- Footer Bottom -->
			<div class="footer-bottom">
				<div class="container">
					<!-- Copyright -->
					<div class="copyright">
						<div class="row">
							<div class="col-md-6 col-lg-6">
								<div class="copyright-text">
									<p class="mb-0">Copyright © 2025 Doccure. All Rights Reserved</p>
								</div>
							</div>
							<div class="col-md-6 col-lg-6">
								<!-- Copyright Menu -->
								<div class="copyright-menu">
									<ul class="policy-menu">
										<li><a href="{{url('terms-condition')}}">Terms and Conditions</a>
										</li>
										<li><a href="{{url('privacy-policy')}}">Policy</a>
										</li>
									</ul>
								</div>
								<!-- /Copyright Menu -->
							</div>
						</div>
					</div>
					<!-- /Copyright -->

				</div>
			</div>
			<!-- /Footer Bottom -->

		</footer>
		<!-- /Footer -->

@endif

@if (Route::is(['index-5']))
    <!-- Footer -->
    <footer class="footer footer-three footer-ten">
        <div class="footer-ten-bg">
            <img src="{{ URL::asset('/assets/img/bg/hexagen-group-4.png') }}" alt="design-image">
            <img src="{{ URL::asset('/assets/img/bg/hexagen-group-5.png') }}" alt="design-image">
        </div>
        <div class="footer-top">
            <div class="container">
                <div class="footer-details">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="footer-widget footer-about">
                                <h2 class="footer-title">About Doccure </h2>
                                <div class="footer-about-content">
                                    <p> We can guide you through issues like Cardiac arrest, Heart Failure, Peripheral
                                        Artery Disease, Arrhythmia, Atrial Fibrillation, Cholesterol and High BP.</p>
                                </div>
                                <div class="subscribe-form">
                                    <form action="#">
                                        <input type="email" class="form-control" placeholder="Email Address">
                                        <button type="submit" class="btn">Subscribe</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="footer-widget footer-menu">
                                <h2 class="footer-title">Useful Links</h2>
                                <ul>
                                    <li><a href="{{ url('index') }}">Home</a></li>
                                    <li><a href="{{ url('search') }}">Doctors</a></li>
                                    <li><a href="{{ url('my-patients') }}">Patients</a></li>
                                    <li><a href="{{ url('pharmacy-index') }}">Pharmacy</a></li>
                                    <li><a href="{{ url('about-us') }}">Pages</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="footer-widget footer-menu">
                                <h2 class="footer-title">Services</h2>
                                <ul>
                                    <li><a href="javascript:void(0);">Cardiac Consultation</a></li>
                                    <li><a href="javascript:void(0);">Diagnostic Testing</a></li>
                                    <li><a href="javascript:void(0);">Treatments</a></li>
                                    <li><a href="javascript:void(0);">Interventional Procedure</a></li>
                                    <li><a href="javascript:void(0);">Electrophysiology</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="footer-widget footer-contact">
                                <h2 class="footer-title">Contact Us</h2>
                                <div class="footer-contact-info">
                                    <div class="footer-address">
                                        <span>
                                            <i class="feather-phone-call"></i>
                                        </span>
                                        <div class="addr-info">
                                            <h6>Looking for Consultation</h6>
                                            <p>+1 315 369 5943</p>
                                        </div>
                                    </div>
                                    <div class="footer-address">
                                        <span>
                                            <i class="feather-mail"></i>
                                        </span>
                                        <div class="addr-info">
                                            <h6>Email Address</h6>
                                            <p>doccure@example.com</p>
                                        </div>
                                    </div>
                                    <div class="footer-address mb-0">
                                        <span>
                                            <i class="feather-map-pin"></i>
                                        </span>
                                        <div class="addr-info">
                                            <h6>Address</h6>
                                            <p>123 Street Name, City, USA</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <!-- Copyright -->
                <div class="copyright">
                    <div class="copyright-text">
                        <p class="mb-0">Copyright © 2024 Doccure. All Rights Reserved</p>
                    </div>
                    <!-- Copyright Menu -->
                    <div class="copyright-menu">
                        <ul class="policy-menu">
                            <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ url('terms-condition') }}">Terms and Conditions</a></li>
                        </ul>
                    </div>
                    <div class="social-icon">
                        <ul>
                            <li>
                                <a href="javascript:void(0);"><i class="fab fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"><i class="fab fa-instagram"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"><i class="fab fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"><i class="fab fa-linkedin-in"></i></a>
                            </li>
                        </ul>
                    </div>
                    <!-- /Copyright Menu -->
                </div>
                <!-- /Copyright -->
            </div>
        </div>
    </footer>
    <!-- /Footer -->
@endif

@if (Route::is('index-6'))
    <!-- Footer -->
    <footer class="footer footer-eleven">
        <div class="sec-bg-img">
            <img src="{{ URL::asset('/assets/img/bg/vector-bg-02.png') }}" alt="eye-icon"
                class="img-fluid vector-left-bg">
        </div>
        <div class="footer-top aos aos-init aos-animate" data-aos="fade-up">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget footer-about">
                            <div class="footer-widget-title">
                                <h4>About Doccure </h4>
                            </div>
                            <p>We can guide you through issues like Cardiac arrest, Heart Failure,
                                Peripheral Artery Disease, Arrhythmia, Atrial Fibrillation, Cholesterol and High BP.
                            </p>
                            <div class="subscribe-form">
                                <form action="#">
                                    <input type="email" class="form-control" placeholder="Email Address">
                                    <button type="submit" class="btn">Subscribe</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-3 col-md-6">
                        <div class="footer-widget footer-menu">
                            <div class="footer-widget-title">
                                <h4>Contact Us</h4>
                            </div>
                            <ul>
                                <li>
                                    <span class="icon-contact"><i class="fa-solid fa-phone"></i></span>
                                    <p><span>Looking for Consultation</span>+1 315 369 5943</p>
                                </li>
                                <li>
                                    <span class="icon-contact"><i class="fa-regular fa-envelope"></i></span>
                                    <p><span>Email Address</span>doccure@example.com</p>
                                </li>
                                <li>
                                    <span class="icon-contact"><i class="fa-solid fa-location-dot"></i></span>
                                    <p><span>Address</span>123 Street Name, City, USA</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-widget footer-menu">
                            <div class="footer-widget-title">
                                <h4>Useful Links</h4>
                            </div>
                            <ul>
                                <li><a href="{{ url('index-7') }}">Home</a></li>
                                <li><a href="{{ url('doctor-dashboard') }}">Doctors</a></li>
                                <li><a href="{{ url('patient-dashboard') }}">Patients</a></li>
                                <li><a href="{{ url('pharmacy-index') }}">Pharmacy</a></li>
                                <li><a href="javascript:void(0);">Pages</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <div class="footer-widget footer-menu">
                            <div class="footer-widget-title">
                                <h4>Services</h4>
                            </div>
                            <ul>
                                <li><a href="{{ url('index-10') }}">Cardiac Consultation</a></li>
                                <li><a href="javascript:void(0);">Diagnostic Testing</a></li>
                                <li><a href="javascript:void(0);">Treatments</a></li>
                                <li><a href="javascript:void(0);">Interventional Procedure</a></li>
                                <li><a href="javascript:void(0);">Electrophysiology</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <!-- Copyright -->
                <div class="copyright">
                    <div class="row align-items-center">
                        <div class="col-xl-5">
                            <div class="copyright-text">
                                <p>Copyright © 2024 Doccure. All Rights Reserved</p>
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="footer-bottom-logo">
                                <div class="social-icon">
                                    <ul>
                                        <li>
                                            <a href="javascript:void(0);"><i class="fab fa-facebook"></i> </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);"><i class="fab fa-instagram"></i></a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);"><i class="fab fa-twitter"></i> </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);"><i class="fab fa-linkedin-in"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5">

                            <!-- Copyright Menu -->
                            <div class="copyright-menu">
                                <ul class="policy-menu">
                                    <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                                    <li><a href="{{ url('terms-condition') }}">Terms and Conditions</a></li>
                                    <li><a href="pr">Refund Policy</a></li>
                                </ul>
                            </div>
                            <!-- /Copyright Menu -->

                        </div>
                    </div>
                </div>
                <!-- /Copyright -->
            </div>
        </div>
    </footer>
    <!-- /Footer -->
@endif

@if (Route::is(['index-7']))
    <!-- Footer -->
    <footer class="footer-fourteen footer-twelve">
        <div class="footer-top">
            <div class="floating-bg">
                <img src="{{ URL::asset('/assets/img/bg/small-paw-2.png') }}" alt="paw-image">
                <img src="{{ URL::asset('/assets/img/bg/small-paw-3.png') }}" alt="paw-image">
                <img src="{{ URL::asset('/assets/img/bg/footer-round-bg.png') }}" alt="paw-image">
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget footer-about">
                            <div class="footer-widget-title">
                                <h4>About Doccure </h4>
                            </div>
                            <p>We can guide you through issues like Cardiac arrest, Heart Failure,
                                Peripheral Artery Disease, Arrhythmia, Atrial Fibrillation, Cholesterol and High BP.
                            </p>
                            <div class="subscribe-form">
                                <form action="#">
                                    <input type="email" class="form-control" placeholder="Email Address">
                                    <button type="submit" class="btn">Subscribe</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <div class="footer-widget footer-menu">
                            <div class="footer-widget-title">
                                <h4>Useful Links</h4>
                            </div>
                            <ul>
                                <li><a href="{{ url('index-7') }}">Home</a></li>
                                <li><a href="{{ url('doctor-dashboard') }}">Doctors</a></li>
                                <li><a href="{{ url('patient-dashboard') }}">Patients</a></li>
                                <li><a href="{{ url('pharmacy-index') }}">Pharmacy</a></li>
                                <li><a href="javascript:void(0);">Pages</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <div class="footer-widget footer-menu">
                            <div class="footer-widget-title">
                                <h4>Services</h4>
                            </div>
                            <ul>
                                <li><a href="{{ url('index-10') }}">Obstetric care</a></li>
                                <li><a href="javascript:void(0);">Prenatal care</a></li>
                                <li><a href="javascript:void(0);">Ultrasound imaging</a></li>
                                <li><a href="javascript:void(0);">Maternity and baby supplies</a></li>
                                <li><a href="javascript:void(0);">Counseling & Education</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <div class="footer-widget footer-menu">
                            <div class="footer-widget-title">
                                <h4>Quick Links</h4>
                            </div>
                            <ul>
                                <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                                <li><a href="{{ url('terms-condition') }}">Terms & Conditions</a></li>
                                <li><a href="{{ url('privacy-policy') }}">Refund Policy</a></li>
                                <li><a href="{{ url('faq') }}">FAQ</a></li>
                                <li><a href="{{ url('blog-grid') }}">Blogs</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <div class="footer-widget footer-menu">
                            <div class="footer-widget-title">
                                <h4>Get In Touch</h4>
                            </div>
                            <ul>
                                <li>
                                    <span><i class="fa-solid fa-location-dot"></i>Address</span>
                                    <p>123 Street Name, City, USA</p>
                                </li>
                                <li>
                                    <span><i class="fa-solid fa-phone"></i>Phone Number</span>
                                    <p>+1 315 369 5943</p>
                                </li>
                                <li>
                                    <span><i class="fa-regular fa-envelope"></i>Email Address</span>
                                    <p>doccure@example.com</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <!-- Copyright -->
                <div class="copyright">
                    <div class="row align-items-center">
                        <div class="col-md-6 col-lg-6">
                            <div class="copyright-text">
                                <p class="mb-0"> Copyright © 2024 Doccure. All Rights Reserved</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">

                            <!-- Social Links -->
                            <ul class="social-links">
                                <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
                            </ul>
                            <!-- /Social Links -->

                        </div>
                    </div>
                </div>
                <!-- /Copyright -->
            </div>
        </div>
    </footer>
    <!-- /Footer -->
@endif

@if (Route::is(['index-8']))
  <!-- Footer -->
  <footer class="footer footer-thirteen">
    <div class="footer-bg-icon">
        <img src="{{URL::asset('assets/img/bg/footer-bg.png')}}" alt="Rainbow" class="aos" data-aos="fade-left">
    </div>
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="footer-widget footer-menu">
                                <h2 class="footer-title">About us</h2>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);">Our Doctors</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Why us</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">How it works</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Our News & Events</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">FAQ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="footer-widget footer-menu">
                                <h2 class="footer-title">Our cabins</h2>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);">North of London</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Golden Hideaway</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Oak Treehouse</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Acacia Retreat</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Blue Lagoon</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="footer-widget footer-menu">
                                <h2 class="footer-title">Services</h2>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);">Prenatal/New-born</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Vaccinations</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Blood tests</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Diagnostic tests</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Parents training classes</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="footer-widget footer-menu">
                                <h2 class="footer-title">Support</h2>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);">Help Us</a>
                                    </li>
                                    <li>
                                        <a href="{{url('privacy-policy')}}">Privacy Policy</a>
                                    </li>
                                    <li>
                                        <a href="{{url('contact-us')}}">Contact Us</a>
                                    </li>
                                    <li>
                                        <a href="{{url('terms-condition')}}">Terms of Service</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Complaints Policy</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-contents-thirteen-main">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-8">
                        <h6 class="mb-3 text-white">Sign up to our Newsletter</h6>
                        <div class="subscribe-form">
                            <form action="#">
                                <input type="email" class="form-control" placeholder="Email Address">
                                <button type="submit" class="btn btn-primary">Subscribe</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4">
                        <div class="social-icon">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);"><i class="fab fa-facebook"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><i class="fab fa-instagram"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><i class="fab fa-linkedin-in"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>	
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <!-- Copyright -->
            <div class="copyright">
                <div class="row">
                    <div class="col-md-12">
                        <div class="copyright-text">
                            <p class="mb-0"> Copyright © 2025 All Rights Reserved</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Copyright -->
        </div>
    </div>
</footer>
<!-- /Footer -->

@endif

@if (Route::is(['index-9']))
    <!-- Footer -->
    <footer class="footer footer-three footer-fourteen">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <div class="footer-widget footer-about">
                            <div class="footer-logo">
                                <a href="{{ url('index-10') }}"><img
                                        src="{{ URL::asset('/assets/img/footer-logo.png') }}" alt="logo"></a>
                            </div>
                            <div class="footer-about-content">
                                <p>Effortlessly schedule your medical appointments with Doccure. Connect with healthcare
                                    professionals, manage appointments & prioritize your well being </p>
                            </div>
                            <div class="social-icon">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fab fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fab fa-instagram"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fab fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fab fa-linkedin-in"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <div class="footer-widget footer-menu">
                            <h2 class="footer-title">Useful Links</h2>
                            <ul>
                                <li><a href="javascript:void(0);">Home</a></li>
                                <li><a href="javascript:void(0);">Doctors</a></li>
                                <li><a href="javascript:void(0);">Patients</a></li>
                                <li><a href="javascript:void(0);">Pharmacy</a></li>
                                <li><a href="javascript:void(0);">Pages</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <div class="footer-widget footer-menu">
                            <h2 class="footer-title">Services</h2>
                            <ul>
                                <li><a href="javascript:void(0);">Obstetric care</a></li>
                                <li><a href="javascript:void(0);">Terms & Conditions</a></li>
                                <li><a href="javascript:void(0);">Refund Policy</a></li>
                                <li><a href="javascript:void(0);">FAQ</a></li>
                                <li><a href="javascript:void(0);">Blogs</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <div class="footer-widget footer-menu">
                            <h2 class="footer-title">Get In Touch</h2>
                            <ul>
                                <li><a href="javascript:void(0);">Privacy Policy</a></li>
                                <li><a href="javascript:void(0);">Prenatal care</a></li>
                                <li><a href="javascript:void(0);">Ultrasound imaging</a></li>
                                <li><a href="javascript:void(0);">Maternity and baby supplies</a></li>
                                <li><a href="javascript:void(0);">Counseling & Education</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <div class="footer-widget footer-contact">
                            <h2 class="footer-title">Contact Us</h2>
                            <div class="footer-contact-info">
                                <div class="footer-address">
                                    <p class="loc-info"><i class="feather-map-pin"></i>Address</p>
                                    <p>123 Street Name, City, USA</p>
                                </div>
                                <div class="footer-address">
                                    <p class="loc-info"><i class="feather-phone-call"></i>Phone Number</p>
                                    <p>+1 315 369 5943</p>
                                </div>
                                <div class="footer-address mb-0">
                                    <p class="loc-info"><i class="feather-mail"></i>Email Address</p>
                                    <p>doccure@example.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <!-- Copyright -->
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="copyright-text text-center">
                                <p class="mb-0"> Copyright © 2024 All Rights Reserved</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Copyright -->
            </div>
        </div>
    </footer>
    <!-- /Footer -->
@endif

@if (Route::is(['index-10']))
    <!-- Footer -->
    <footer class="footer footer-fifteen">
        <div class="footer-top footer-top-fifteen aos" data-aos="fade-up">
            <div class="container">
                <div class="row join-news-row">
                    <div class="col-lg-8 col-md-6">
                        <div class="join-news-foot">
                            <h4>Join Our Newsletter</h4>
                            <p>Subscribe Now for Exclusive Insights & Offers</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="form-set">
                            <form action="{{ url('index-10') }}">
                                <input type="text" class="form-control" placeholder="Enter your Email">
                                <button type="submit" class="btn btn-primary">Subscribe</button>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget">
                            <div class="footer-logo">
                                <img src="{{ URL::asset('/assets/img/logo-15.png') }}" alt="Img">
                            </div>
                            <p>Effortlessly schedule your medical appointments with Doccure.
                                Connect with healthcare professionals, manage appointments & prioritize your well being
                            </p>
                            <div class="social-links">
                                <ul>
                                    <li><a href="javascript:void(0);"><i class="fa-brands fa-facebook-f"></i></a></li>
                                    <li><a href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a></li>
                                    <li><a href="javascript:void(0);"><i class="fa-brands fa-twitter"></i></a></li>
                                    <li><a href="javascript:void(0);"><i class="fa-brands fa-linkedin-in"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="footer-widget">
                            <div class="footer-title">
                                <h4>Quick Links</h4>
                            </div>
                            <ul class="link-foot">
                                <li><a href="{{ url('about-us') }}">About</a></li>
                                <li><a href="javascript:void(0);">Faciities</a></li>
                                <li><a href="{{ url('my-patients') }}">Patients</a></li>
                                <li><a href="javascript:void(0);">Camp</a></li>
                                <li><a href="{{ url('doctor-profile') }}">Doctors</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="footer-widget">
                            <div class="footer-title">
                                <h4>Services</h4>
                            </div>
                            <ul class="link-foot">
                                <li><a href="javascript:void(0);">Hearing and Balance Disorders</a></li>
                                <li><a href="javascript:void(0);">Snoring and Sleep Apnea</a></li>
                                <li><a href="javascript:void(0);">Voice and Swallowing Disorders</a></li>
                                <li><a href="javascript:void(0);">Sinusitis and Rhinitis</a></li>
                                <li><a href="javascript:void(0);">Allergy Testing and Treatment</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="footer-widget">
                            <div class="footer-title">
                                <h4>Contact</h4>
                            </div>
                            <ul class="link-foot address">
                                <li>
                                    <span>
                                        <i class="fa-solid fa-location-dot"></i>
                                    </span>
                                    3556 Beech Street, San Francisco, California, CA 94108
                                </li>
                                <li>
                                    <span>
                                        <i class="fa-solid fa-mobile-screen-button"></i>
                                    </span>
                                    +1 315 369 5943
                                </li>
                                <li>
                                    <span>
                                        <i class="fa-solid fa-envelope"></i>
                                    </span>
                                    doccure@example.com
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="bottom-items">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="copy-right">
                                <p>Copyright © 2024 Doccure. All Rights Reserved</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="terms-con">
                                <ul>
                                    <li>
                                        <a href="{{ url('terms-condition') }}">Terms and Conditions</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('privacy-policy') }}">Policy</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- /Footer -->
@endif

@if (Route::is(['index-11']))
    <!-- Footer -->
    <footer class="footer footer-one footer-sixteen">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="footer-widget footer-about">
                            <div class="footer-logo">
                                <img src="{{ URL::asset('/assets/img/logo-dark-blue.svg') }}" alt="logo">
                            </div>
                            <div class="footer-about-content">
                                <p>We can guide you through issues like Cardiac arrest, Heart Failure, Peripheral Artery
                                    Disease.</p>
                            </div>
                            <div class="subscribe-form">
                                <input type="email" class="form-control" placeholder="Enter Email Address">
                                <button type="submit" class="btn footer-btn">
                                    Subscribe
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <div class="footer-widget footer-menu">
                            <h2 class="footer-title">Company</h2>
                            <ul>
                                <li><a href="{{ url('about-us') }}">About us</a></li>
                                <li><a href="{{ url('cart') }}">Careers</a></li>
                                <li><a href="javascript:void(0);">News</a></li>
                                <li><a href="{{ url('contact-us') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <div class="footer-widget footer-menu">
                            <h2 class="footer-title">Resources</h2>
                            <ul>
                                <li><a href="javascript:void(0);">Events</a></li>
                                <li><a href="javascript:void(0);">Help Centre</a></li>
                                <li><a href="javascript:void(0);">Tutorials</a></li>
                                <li><a href="javascript:void(0);">Support</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-5">
                        <div class="footer-widget footer-menu">
                            <h2 class="footer-title">Social</h2>
                            <ul>
                                <li><a href="javascript:void(0);">Twitter</a></li>
                                <li><a href="javascript:void(0);">LinkedIn</a></li>
                                <li><a href="javascript:void(0);">Facebook</a></li>
                                <li><a href="javascript:void(0);">Dribble</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-7">
                        <div class="footer-widget footer-menu">
                            <h2 class="footer-title">Legal</h2>
                            <ul>
                                <li><a href="{{ url('terms-condition') }}">Terms & Condition</a></li>
                                <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                                <li><a href="{{ url('coming-soon') }}">Cookies</a></li>
                                <li><a href="javascript:void(0);">Licenses</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <!-- Copyright -->
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="copyright-text">
                                <p>© 2024 Doccure. All rights reserved.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">

                            <!-- Copyright Menu -->
                            <div class="copyright-menu">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fa-brands fa-facebook-f"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fab fa-twitter"></i> </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fa-brands fa-whatsapp"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fa-brands fa-youtube"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Copyright Menu -->

                        </div>
                    </div>
                </div>
                <!-- /Copyright -->
            </div>
        </div>
    </footer>
    <!-- /Footer -->
@endif

@if (Route::is(['index-12']))
    <!-- Footer -->
    <footer class="footer-twelve">
        <div class="footer-top-sec">
            <div class="container">
                <div class="footer-top">
                    <div class="logo-footer">
                        <img src="{{ URL::asset('/assets/img/icons/footer-logo-.svg') }}" alt="Img">
                    </div>
                    <div class="footer-buy-btn">
                        <h6>Ready to get started with Doccure?</h6>
                        <a href="{{ url('booking-2') }}" class="btn btn-primary">Book Now</a>
                    </div>
                </div>
                <div class="footer-middle">
                    <div class="row">
                        <div class="col-lg-3 col-md-5 col-sm-6">
                            <div class="footer-links-middle">
                                <h4>Contact Us</h4>
                                <ul>
                                    <li><span><i class="feather-map-pin"></i></span>3556 Beech Street, USA</li>
                                    <li><span><i class="feather-phone"></i></span>+1 315 369 5943</li>
                                    <li><span><i class="feather-mail"></i></span>info@example.com</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6">
                            <div class="footer-links-middle">
                                <h4>Company</h4>
                                <ul>
                                    <li><a href="{{ url('about-us') }}">About Us</a></li>
                                    <li><a href="{{ url('blog-grid') }}">Blogs</a></li>
                                    <li><a href="javascript:void(0);">Careers</a></li>
                                    <li><a href="javascript:void(0);">Success Stories</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="footer-links-middle">
                                <h4>Specialities</h4>
                                <ul>
                                    <li><a href="javascript:void(0);">Pregnancy Test</a></li>
                                    <li><a href="javascript:void(0);">Vitamin D & B12 Combo</a></li>
                                    <li><a href="javascript:void(0);">Cancer Screening - Male</a></li>
                                    <li><a href="javascript:void(0);">Advanced Renal Package</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="footer-links-middle">
                                <h4>Join Our Newsletter</h4>
                                <form action="{{ url('index-12') }}">
                                    <div class="input-block">
                                        <input type="email" class="form-control" placeholder="Enter Email Address">
                                        <button class="submit-btn">Submit</button>
                                    </div>
                                </form>
                                <p>* Will send you weekly updates for your better</p>
                                <ul class="social-icons">
                                    <li><a href="javascript:void(0);" class="fb"><i
                                                class="fa-brands fa-facebook-f"></i></a></li>
                                    <li><a href="javascript:void(0);" class="insta"><i
                                                class="fa-brands fa-instagram"></i></a></li>
                                    <li><a href="javascript:void(0);" class="linked-in"><i
                                                class="fa-brands fa-linkedin-in"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="fooeter-bottom">
            <div class="container">
                <div class="copy-right-footer">
                    <h5>Copyright © 2024 Doccure. All Rights Reserved</h5>
                    <ul class="privacy-policy">
                        <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ url('terms-condition') }}">Terms & Conditions</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- /Footer -->
@endif

@if (Route::is(['index-13']))
    <!-- Footer -->
    <footer class="footer footer-sec-fourteen">
        <div class="footer-top">
            <div class="row">
                <div class="col-sm-3 col-6 p-0">
                    <div class="footer-img">
                        <img src="{{ URL::asset('/assets/img/service/footer-img-01.jpg') }}" class="img-fluid w-100"
                            alt="Img">
                    </div>
                </div>
                <div class="col-sm-3 col-6 p-0">
                    <div class="footer-img">
                        <img src="{{ URL::asset('/assets/img/service/footer-img-02.jpg') }}" class="img-fluid w-100"
                            alt="Img">
                    </div>
                </div>
                <div class="col-sm-3 col-6 p-0">
                    <div class="footer-img">
                        <img src="{{ URL::asset('/assets/img/service/footer-img-03.jpg') }}" class="img-fluid w-100"
                            alt="Img">
                    </div>
                </div>
                <div class="col-sm-3 col-6 p-0">
                    <div class="footer-img">
                        <img src="{{ URL::asset('/assets/img/service/footer-img-04.jpg') }}" class="img-fluid w-100"
                            alt="Img">
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-middle">
            <div class="container">
                <div class="footer-mid-content">
                    <p>Would you like to connect with us</p>
                    <ul class="social-icons">
                        <li><a href="javascript:void(0);"><i class="fa-brands fa-facebook-f"></i></a></li>
                        <li><a href="javascript:void(0);"><i class="fa-brands fa-twitter"></i></a></li>
                        <li><a href="javascript:void(0);"><i class="fa-brands fa-linkedin-in"></i></a></li>
                        <li><a href="javascript:void(0);"><i class="fa-brands fa-youtube"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-mid-two">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-md-6 col-sm-12" data-aos="fade-down" data-aos-delay="500">
                        <div class="footer-logo-col">
                            <div class="footer-logo">
                                <img src="{{ URL::asset('/assets/img/icons/footer-logo-.svg') }}" alt="Img">
                            </div>
                            <p>Connect with healthcare professionals, manage appointments & prioritize your well being
                            </p>
                            <form action="{{ url('index-13') }}">
                                <div class="input-block">
                                    <input type="text" class="form-control" placeholder="Enter Email">
                                    <button class="btn" type="submit">Subscribe</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-3 col-sm-6" data-aos="fade-down" data-aos-delay="600">
                        <div class="footer-links-middle">
                            <h4>QUICK LINKS</h4>
                            <ul>
                                <li><a href="{{ url('index') }}">Home</a></li>
                                <li><a href="{{ url('about-us') }}">About us</a></li>
                                <li><a href="{{ url('payment') }}">Payments</a></li>
                                <li><a href="{{ url('booking') }}">Lab Test</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-3 col-sm-6" data-aos="fade-down" data-aos-delay="700">
                        <div class="footer-links-middle">
                            <h4>SERVICES</h4>
                            <ul>
                                <li><a href="{{ url('search-2') }}">Nurse at Home</a></li>
                                <li><a href="{{ url('search-2') }}">Physiotherapy</a></li>
                                <li><a href="{{ url('search-2') }}">Medical Equipment</a></li>
                                <li><a href="{{ url('booking-2') }}">Doctor Consultation</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6" data-aos="fade-down" data-aos-delay="800">
                        <div class="footer-links-middle">
                            <h4>CONSUMER POLICY</h4>
                            <ul>
                                <li><a href="{{ url('privacy-policy') }}">Privacy</a></li>
                                <li><a href="{{ url('terms-condition') }}">Terms and Condition</a></li>
                                <li><a href="{{ url('booking') }}">Aggrements</a></li>
                                <li><a href="{{ url('map-grid') }}">Sitemap</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="footer-bottom">
                <div class="copy-right">
                    <P>Copyright © 2024 Doccure. All rights are reserved.</P>
                </div>
                <div class="footer-select-drops">
                    <div class="input-select">
                        <span class="select-icon"><i class="feather-globe"></i></span>
                        <select class="select">
                            <option>India</option>
                            <option>US</option>
                        </select>
                    </div>
                    <div class="input-select currency-select">
                        <select class="select">
                            <option>USD</option>
                            <option>INR</option>
                        </select>
                    </div>
                </div>
                <ul class="payment-method">
                    <li><a href="#"><img src="{{ URL::asset('/assets/img/icons/footer-card-icon-01.svg') }}"
                                alt="Img"></a>
                    </li>
                    <li><a href="#"><img src="{{ URL::asset('/assets/img/icons/footer-card-icon-02.svg') }}"
                                alt="Img"></a>
                    </li>
                    <li><a href="#"><img src="{{ URL::asset('/assets/img/icons/footer-card-icon-03.svg') }}"
                                alt="Img"></a>
                    </li>
                    <li><a href="#"><img src="{{ URL::asset('/assets/img/icons/footer-card-icon-04.svg') }}"
                                alt="Img"></a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
    <!-- /Footer -->
@endif

@if (Route::is(['index-14']))
<!-- Footer -->
<footer class="footer footer-eleven dentist-footer">
    <div class="footer-top aos aos-init aos-animate" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget footer-about">
                        <div class="footer-widget-title">
                            <h4>About </h4>
                        </div>
                        <p>Dental care is essential for maintaining oral health and overall well-being. It involves practices aimed at preventing dental problems, treating existing issues.</p>
                        <div class="subscribe-form">
                            <form action="#">
                                <input type="email" class="form-control" placeholder="Email Address">
                                <button type="submit" class="btn">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="footer-widget footer-menu">
                        <div class="footer-widget-title">
                            <h4>Contact Us</h4>
                        </div>
                        <ul>
                            <li>
                                <span class="icon-contact"><i class="fa-solid fa-phone"></i></span>
                                <p><span>Looking for Consultation</span>+1 315 369 5943</p>
                            </li>
                            <li>
                                <span class="icon-contact"><i class="fa-regular fa-envelope"></i></span>
                                <p><span>Email Address</span>doccure@example.com</p>
                            </li>
                            <li>
                                <span class="icon-contact"><i class="fa-solid fa-location-dot"></i></span>
                                <p><span>Address</span>123 Street Name, City, USA</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget footer-menu">
                        <div class="footer-widget-title">
                            <h4>Useful Links</h4>
                        </div>
                        <ul>
                            <li><a href="{{url('index-14')}}">Home</a></li>
                            <li><a href="{{url('doctor-dashboard')}}">Doctors</a></li>
                            <li><a href="{{url('patient-dashboard')}}">Patients</a></li>
                            <li><a href="{{url('pharmacy-index')}}">Pharmacy</a></li>
                            <li><a href="javascript:void(0);">Pages</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="footer-widget footer-menu">
                        <div class="footer-widget-title">
                            <h4>Services</h4>
                        </div>
                        <ul>
                            <li><a href="{{url('index-10')}}">Cardiac Consultation</a></li>
                            <li><a href="javascript:void(0);">Diagnostic Testing</a></li>
                            <li><a href="javascript:void(0);">Treatments</a></li>
                            <li><a href="javascript:void(0);">Interventional Procedure</a></li>
                            <li><a href="javascript:void(0);">Electrophysiology</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <!-- Copyright -->
            <div class="copyright">
                <div class="row align-items-center">
                    <div class="col-xl-4">
                        <div class="copyright-text">
                            <p>Copyright © 2025 Doccure. All Rights Reserved</p>
                        </div>
                    </div>
                    <div class="col-xl-3">
                        <div class="footer-bottom-logo">
                            <div class="social-icon">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fab fa-facebook"></i> </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fab fa-x"></i> </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fab fa-instagram"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fab fa-linkedin-in"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"><i class="fab fa-pinterest"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <!-- Copyright Menu -->
                        <div class="copyright-menu">
                            <ul class="policy-menu">
                                <li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
                                <li><a href="{{url('terms-condition')}}">Terms and Conditions</a></li>
                                <li><a href="pr">Refund Policy</a></li>
                            </ul>
                        </div>
                        <!-- /Copyright Menu -->

                    </div>
                </div>
            </div>
            <!-- /Copyright -->
        </div>
    </div>
</footer>
<!-- /Footer -->


@endif



@if (Route::is([
        'accounts',
        'appointments',
        'available-timings',
        'change-password',
        'chat-doctor',
        'chat',
        'dependent',
        'doctor-appointment-details',
        'doctor-appointment-start',
        'doctor-appointments-grid',
        'doctor-awards-settings',
        'doctor-business-settings',
        'doctor-cancelled-appointment-2',
        'doctor-cancelled-appointment',
        'doctor-change-password',
        'doctor-clinics-settings',
        'doctor-completed-appointment',
        'doctor-dashboard',
        'doctor-education-settings',
        'doctor-experience-settings',
        'doctor-payment',
        'doctor-profile-settings',
        'doctor-request',
        'doctor-specialities',
        'doctor-upcoming-appointment',
        'doctor-insurance-settings',
        'favourites',
        'invoices',
        'medical-details',
        'medical-records',
        'my-patients',
        'patient-accounts',
        'patient-appointment-details',
        'patient-appointments-grid',
        'patient-cancelled-appointment',
        'patient-completed-appointment',
        'patient-dashboard',
        'patient-invoices',
        'patient-profile',
        'patient-upcoming-appointment',
        'pharmacy-index',
        'profile-settings',
        'reviews'
    ]))
   <!-- Footer Section -->
   <footer class="footer inner-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <div class="footer-widget footer-menu">
                                <h6 class="footer-title">Company</h6>
                                <ul>
                                    <li><a href="{{url('about-us')}}">About</a></li>
                                    <li><a href="{{url('search')}}">Features</a></li>
                                    <li><a href="javascript:void(0);">Works</a></li>
                                    <li><a href="javascript:void(0);">Careers</a></li>
                                    <li><a href="javascript:void(0);">Locations</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="footer-widget footer-menu">
                                <h6 class="footer-title">Treatments</h6>
                                <ul>
                                    <li><a href="{{url('search')}}">Dental</a></li>
                                    <li><a href="{{url('search')}}">Cardiac</a></li>
                                    <li><a href="{{url('search')}}">Spinal Cord</a></li>
                                    <li><a href="{{url('search')}}">Hair Growth</a></li>
                                    <li><a href="{{url('search')}}">Anemia & Disorder</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="footer-widget footer-menu">
                                <h6 class="footer-title">Specialities</h6>
                                <ul>
                                    <li><a href="{{url('search')}}">Transplant</a></li>
                                    <li><a href="{{url('search')}}">Cardiologist</a></li>
                                    <li><a href="{{url('search')}}">Oncology</a></li>
                                    <li><a href="{{url('search')}}">Pediatrics</a></li>
                                    <li><a href="{{url('search')}}">Gynacology</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="footer-widget footer-menu">
                                <h6 class="footer-title">Utilites</h6>
                                <ul>
                                    <li><a href="{{url('pricing')}}">Pricing</a></li>
                                    <li><a href="{{url('contact-us')}}">Contact</a></li>
                                    <li><a href="{{url('contact-us')}}">Request A Quote</a></li>
                                    <li><a href="javascript:void(0);">Premium Membership</a></li>
                                    <li><a href="javascript:void(0);">Integrations</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-7">
                    <div class="footer-widget">
                        <h6 class="footer-title">Newsletter</h6>
                        <p class="mb-2">Subscribe & Stay Updated from the Doccure</p>
                        <div class="subscribe-input">
                            <form action="#">
                                <input type="email" class="form-control" placeholder="Enter Email Address">
                                <button type="submit" class="btn btn-md btn-primary-gradient inline-flex align-items-center"><i class="isax isax-send-25 me-1"></i>Send</button>
                            </form>
                        </div>
                        <div class="social-icon">
                            <h6 class="mb-3">Connect With Us</h6>
                            <ul>
                                <li>
                                    <a href="javascript:void(0);"><i class="fa-brands fa-facebook"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><i class="fa-brands fa-x-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><i class="fa-brands fa-linkedin"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><i class="fa-brands fa-pinterest"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bg">
            <img src="{{URL::asset('assets/img/bg/footer-bg-01.png')}}" alt="img" class="footer-bg-01">
            <img src="{{URL::asset('assets/img/bg/footer-bg-02.png')}}" alt="img" class="footer-bg-02">
            <img src="{{URL::asset('assets/img/bg/footer-bg-03.png')}}" alt="img" class="footer-bg-03">
            <img src="{{URL::asset('assets/img/bg/footer-bg-04.png')}}" alt="img" class="footer-bg-04">
            <img src="{{URL::asset('assets/img/bg/footer-bg-05.png')}}" alt="img" class="footer-bg-05">
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <!-- Copyright -->
            <div class="copyright">
                <div class="copyright-text">
                    <p class="mb-0">Copyright © 2025 Doccure. All Rights Reserved</p>
                </div>
                <!-- Copyright Menu -->
                <div class="copyright-menu">
                    <ul class="policy-menu">
                        <li><a href="javascript:void(0);">Legal Notice</a></li>
                        <li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
                        <li><a href="javascript:void(0);">Refund Policy</a></li>
                    </ul>
                </div>
                <!-- /Copyright Menu -->
                <ul class="payment-method">
                    <li><a href="javascript:void(0);"><img src="{{URL::asset('assets/img/icons/card-01.svg')}}" alt="Img"></a></li>
                    <li><a href="javascript:void(0);"><img src="{{URL::asset('assets/img/icons/card-02.svg')}}" alt="Img"></a></li>
                    <li><a href="javascript:void(0);"><img src="{{URL::asset('assets/img/icons/card-03.svg')}}" alt="Img"></a></li>
                    <li><a href="javascript:void(0);"><img src="{{URL::asset('assets/img/icons/card-04.svg')}}" alt="Img"></a></li>
                    <li><a href="javascript:void(0);"><img src="{{URL::asset('assets/img/icons/card-05.svg')}}" alt="Img"></a></li>
                    <li><a href="javascript:void(0);"><img src="{{URL::asset('assets/img/icons/card-06.svg')}}" alt="Img"></a></li>
                </ul>
            </div>
            <!-- /Copyright -->					
        </div>
    </div>
</footer>
<!-- /Footer Section -->
@endif


@if (
    !Route::is([
       
        'index-2',
        'index-3',
        'index-4',
        'index-5',
        'index-6',
        'index-7',
        'index-8',
        'index-9',
        'index-10',
        'index-11',
        'index-12',
        'index-13',
        'index-14',
        'accounts',
        'appointments',
        'available-timings',
        'change-password',
        'chat-doctor',
        'chat',
        'dependent',
        'doctor-appointment-details',
        'doctor-appointment-start',
        'doctor-appointments-grid',
        'doctor-awards-settings',
        'doctor-business-settings',
        'doctor-cancelled-appointment-2',
        'doctor-cancelled-appointment',
        'doctor-change-password',
        'doctor-clinics-settings',
        'doctor-completed-appointment',
        'doctor-dashboard',
        'doctor-education-settings',
        'doctor-experience-settings',
        'doctor-payment',
        'doctor-profile-settings',
        'doctor-request',
        'doctor-specialities',
        'doctor-upcoming-appointment',
        'doctor-insurance-settings',
        'favourites',
        'invoices',
        'medical-details',
        'medical-records',
        'my-patients',
        'patient-accounts',
        'patient-appointment-details',
        'patient-appointments-grid',
        'patient-cancelled-appointment',
        'patient-completed-appointment',
        'patient-dashboard',
        'patient-invoices',
        'patient-profile',
        'patient-upcoming-appointment',
        'pharmacy-index',
        'profile-settings',
        'reviews',
    ]))
   <!-- Footer Section -->
   <footer class="footer inner-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <div class="footer-widget footer-menu">
                                <h6 class="footer-title">Company</h6>
                                <ul>
                                    <li><a href="{{url('about-us')}}">About</a></li>
                                    <li><a href="{{url('search')}}">Features</a></li>
                                    <li><a href="javascript:void(0);">Works</a></li>
                                    <li><a href="javascript:void(0);">Careers</a></li>
                                    <li><a href="javascript:void(0);">Locations</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="footer-widget footer-menu">
                                <h6 class="footer-title">Treatments</h6>
                                <ul>
                                    <li><a href="{{url('search')}}">Dental</a></li>
                                    <li><a href="{{url('search')}}">Cardiac</a></li>
                                    <li><a href="{{url('search')}}">Spinal Cord</a></li>
                                    <li><a href="{{url('search')}}">Hair Growth</a></li>
                                    <li><a href="{{url('search')}}">Anemia & Disorder</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="footer-widget footer-menu">
                                <h6 class="footer-title">Specialities</h6>
                                <ul>
                                    <li><a href="{{url('search')}}">Transplant</a></li>
                                    <li><a href="{{url('search')}}">Cardiologist</a></li>
                                    <li><a href="{{url('search')}}">Oncology</a></li>
                                    <li><a href="{{url('search')}}">Pediatrics</a></li>
                                    <li><a href="{{url('search')}}">Gynacology</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="footer-widget footer-menu">
                                <h6 class="footer-title">Utilites</h6>
                                <ul>
                                    <li><a href="{{url('pricing')}}">Pricing</a></li>
                                    <li><a href="{{url('contact-us')}}">Contact</a></li>
                                    <li><a href="{{url('contact-us')}}">Request A Quote</a></li>
                                    <li><a href="javascript:void(0);">Premium Membership</a></li>
                                    <li><a href="javascript:void(0);">Integrations</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-7">
                    <div class="footer-widget">
                        <h6 class="footer-title">Newsletter</h6>
                        <p class="mb-2">Subscribe & Stay Updated from the Doccure</p>
                        <div class="subscribe-input">
                            <form action="#">
                                <input type="email" class="form-control" placeholder="Enter Email Address">
                                <button type="submit" class="btn btn-md btn-primary-gradient inline-flex align-items-center"><i class="isax isax-send-25 me-1"></i>Send</button>
                            </form>
                        </div>
                        <div class="social-icon">
                            <h6 class="mb-3">Connect With Us</h6>
                            <ul>
                                <li>
                                    <a href="javascript:void(0);"><i class="fa-brands fa-facebook"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><i class="fa-brands fa-x-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><i class="fa-brands fa-linkedin"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><i class="fa-brands fa-pinterest"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bg">
            <img src="{{URL::asset('assets/img/bg/footer-bg-01.png')}}" alt="img" class="footer-bg-01">
            <img src="{{URL::asset('assets/img/bg/footer-bg-02.png')}}" alt="img" class="footer-bg-02">
            <img src="{{URL::asset('assets/img/bg/footer-bg-03.png')}}" alt="img" class="footer-bg-03">
            <img src="{{URL::asset('assets/img/bg/footer-bg-04.png')}}" alt="img" class="footer-bg-04">
            <img src="{{URL::asset('assets/img/bg/footer-bg-05.png')}}" alt="img" class="footer-bg-05">
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <!-- Copyright -->
            <div class="copyright">
                <div class="copyright-text">
                    <p class="mb-0">Copyright © 2025 Doccure. All Rights Reserved</p>
                </div>
                <!-- Copyright Menu -->
                <div class="copyright-menu">
                    <ul class="policy-menu">
                        <li><a href="javascript:void(0);">Legal Notice</a></li>
                        <li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
                        <li><a href="javascript:void(0);">Refund Policy</a></li>
                    </ul>
                </div>
                <!-- /Copyright Menu -->
                <ul class="payment-method">
                    <li><a href="javascript:void(0);"><img src="{{URL::asset('assets/img/icons/card-01.svg')}}" alt="Img"></a></li>
                    <li><a href="javascript:void(0);"><img src="{{URL::asset('assets/img/icons/card-02.svg')}}" alt="Img"></a></li>
                    <li><a href="javascript:void(0);"><img src="{{URL::asset('assets/img/icons/card-03.svg')}}" alt="Img"></a></li>
                    <li><a href="javascript:void(0);"><img src="{{URL::asset('assets/img/icons/card-04.svg')}}" alt="Img"></a></li>
                    <li><a href="javascript:void(0);"><img src="{{URL::asset('assets/img/icons/card-05.svg')}}" alt="Img"></a></li>
                    <li><a href="javascript:void(0);"><img src="{{URL::asset('assets/img/icons/card-06.svg')}}" alt="Img"></a></li>
                </ul>
            </div>
            <!-- /Copyright -->					
        </div>
    </div>
</footer>
<!-- /Footer Section -->

@endif
