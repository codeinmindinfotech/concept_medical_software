
<!-- jQuery -->
<script src="{{ URL::asset('/assets/js/jquery-3.7.1.min.js') }}"></script>

<!-- Bootstrap Bundle JS -->
<script src="{{ URL::asset('/assets/js/bootstrap.min.js') }}"></script>

@if (Route::is([
        'about-us',
        'booking-success-one',
        'coming-soon',
        'consultation',
        'doctor-search-grid',
        'doctor-signup',
        'email-otp',
        'error-404',
        'error-500',
        'forgot-password2',
        'index-5',
        'index-7',
        'index-8',
        'index-9',
        'index-10',
        'index-11',
        'index-12',
        'index-13',
        'index',
        'login-email-otp',
        'login-email',
        'login-phone-otp',
        'login-phone',
        'maintenance',
        'mobile-otp',
        'onboarding-consultation',
        'onboarding-identity',
        'onboarding-preferences',
        'onboarding-verification',
        'onboarding-verify-account',
        'patient-details',
        'patient-signup',
        'payment',
        'pricing',
        'privacy-policy',
        'reset-password',
        'search-2',
        'signup-success',
        'signup',
        'terms-condition',
        'paitent-details',
        'index-14'
    ]))
    <!-- Feather Icon JS -->
    <script src="{{ URL::asset('/assets/js/feather.min.js') }}"></script>
@endif

@if (Route::is([
        'about-us',
        'booking-2',
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
        'index',
        'pharmacy-index',
        'index-14',
        'map-list-availability'
    ]))
    <!-- Slick JS -->
    <script src="{{ URL::asset('/assets/js/slick.js') }}"></script>
@endif

@if (Route::is([
        'accounts',
        'add-billing',
        'add-dependent',
        'add-prescription',
        'appointments',
        'available-timings',
        'blog-details',
        'blog-grid',
        'blog-list',
        'calendar',
        'change-password',
        'checkout',
        'dependent',
        'doctor-add-blog',
        'doctor-blog',
        'doctor-change-password',
        'doctor-dashboard',
        'doctor-pending-blog',
        'doctor-profile-settings',
        'doctor-search-grid',
        'edit-billing',
        'edit-blog',
        'edit-dependent',
        'edit-prescription',
        'favourites',
        'invoices',
        'medical-details',
        'medical-records',
        'membership-details',
        'my-patients',
        'orders-list',
        'patient-accounts',
        'patient-dashboard',
        'patient-profile',
        'pharmacy-search',
        'product-all',
        'product-checkout',
        'product-description',
        'product-healthcare',
        'profile-settings',
        'reviews',
        'schedule-timings',
        'search-2',
        'search',
        'social-media',
        'doctor-request',
        'doctor-appointment-start',
        'doctor-upcoming-appointment',
        'doctor-appointments-grid',
        'doctor-specialities',
        'doctor-payment',
        'doctor-completed-appointment',
        'patient-dashboard',
        'profile-settings',
        'doctor-cancelled-appointment',
        'patient-appointments',
        'doctor-experience-settings',
        'patient-appointment-details',
        'patient-appointments-grid',
        'patient-cancelled-appointment',
        'patient-completed-appointment',
        'patient-invoices',
        'patient-upcoming-appointment',
        'doctor-appointment-details',
        'doctor-awards-settings',
        'doctor-business-settings',
        'doctor-clinics-settings',
        'doctor-education-settings',
        'doctor-insurance-settings',
        'doctor-cancelled-appointment-2',
        'delete-account',
        'two-factor-authentication',
        
    ]))
    <!-- Sticky Sidebar JS -->
    <script src="{{ URL::asset('/assets/plugins/theia-sticky-sidebar/ResizeSensor.js') }}"></script>
    <script src="{{ URL::asset('/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}"></script>
@endif

@if (Route::is([
        'doctor-dashboard',
        'patient-dashboard',
        'patient-appointment-details',
        'patient-cancelled-appointment',
        'patient-completed-appointment',
        'patient-upcoming-appointment',
    ]))
    <!-- Apexchart JS -->
    <script src="{{ URL::asset('assets/plugins/apex/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/apex/chart-data.js') }}"></script>

    <!-- Circle Progress JS -->
    <script src="{{ URL::asset('assets/js/circle-progress.min.js') }}"></script>
@endif

@if (Route::is(['reset-password']))
    <!-- Validation-->
    <script src="{{ URL::asset('/assets/js/validation.js') }}"></script>
@endif

@if (Route::is([
        'add-dependent',
        'calendar',
        'edit-dependent',
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
        'index',
        'index-12',
        'index-13',
        'onboarding-availability',
        'onboarding-consultation',
        'onboarding-payments',
        'onboarding-personalize',
        'onboarding-preferences',
        'onboarding-verify-account',
        'patient-details',
        'patient-personalize',
        'patient-profile',
        'pharmacy-search',
        'product-all',
        'product-description',
        'product-healthcare',
        'profile-settings',
        'search',
        'my-patients',
        'patient-dashboard',
        'profile-settings',
        'doctor-experience-settings',
        'dependent',
        'doctor-awards-settings',
        'doctor-business-settings',
        'doctor-education-settings',
        'doctor-cancelled-appointment-2',
        'available-timings',
        'medical-records',
        'medical-details',
        'index-14',
        'booking-popup',
        'booking',
        'doctor-grid',
        'search-2',
        'map-list-availability',
        'map-list',
        'map-grid',
        'doctor-clinics-settings',
        'doctor-insurance-settings',
        'doctor-specialities',
    ]))
    <!-- Datetimepicker JS -->
    <script src="{{ URL::asset('/assets/js/moment.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/bootstrap-datetimepicker.min.js') }}"></script>
@endif

@if (Route::is([
        'accounts',
        'available-timings',
        'doctor-add-blog',
        'doctor-profile-settings',
        'doctor-register-step2',
        'doctor-register-step3',
        'doctor-search-grid',
        'edit-blog',
        'edit-dependent',
        'index-3',
        'index-6',
        'index-8',
        'index-10',
        'index-11',
        'map-grid',
        'map-list',
        'medical-records',
        'onboarding-availability',
        'onboarding-consultation',
        'onboarding-cost',
        'onboarding-identity',
        'onboarding-payments',
        'onboarding-personalize',
        'onboarding-phone',
        'onboarding-preferences',
        'onboarding-verification',
        'onboarding-verify-account',
        'patient-details',
        'patient-details',
        'patient-other-details',
        'patient-personalize',
        'patient-register-step2',
        'patient-register-step5',
        'pharmacy-register-step2',
        'pharmacy-register-step3',
        'pharmacy-search',
        'product-all',
        'product-description',
        'product-healthcare',
        'profile-settings',
        'schedule-timings',
        'search-2',
        'search',
        'add-dependent',
        'index-12',
        'index-13',
        'dependent',
        'doctor-dashboard',
        'my-patients',
        'social-media',
        'doctor-request',
        'reviews',
        'invoices',
        'doctor-appointment-start',
        'doctor-upcoming-appointment',
        'doctor-appointments-grid',
        'doctor-specialities',
        'doctor-payment',
        'appointments',
        'doctor-completed-appointment',
        'patient-dashboard',
        'profile-settings',
        'doctor-cancelled-appointment',
        'patient-appointments',
        'doctor-experience-settings',
        'patient-profile',
        'patient-appointment-details',
        'patient-appointments-grid',
        'patient-cancelled-appointment',
        'patient-completed-appointment',
        'patient-upcoming-appointment',
        'calendar',
        'doctor-pending-blog',
        'doctor-appointment-details',
        'doctor-awards-settings',
        'doctor-blog',
        'doctor-business-settings',
        'doctor-change-password',
        'doctor-clinics-settings',
        'doctor-education-settings',
        'doctor-insurance-settings',
        'paitent-details',
        'doctor-cancelled-appointment-2',
        'index-14',
        'booking-success-one',
        'consultation', 
        'hospitals', 
        'clinic',
        'booking-popup',
        'booking',
        'map-list-availability',
        'map-list',
        'map-grid'
    ]))
    <!-- Select2 JS -->
    <script src="{{ URL::asset('/assets/plugins/select2/js/select2.min.js') }}"></script>
@endif

@if (Route::is(['map-grid', 'map-list','map-list-availability']))
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6adZVdzTvBpE2yBRK8cDfsss8QXChK0I"></script>
    <script src="{{ URL::asset('/assets/js/map.js') }}"></script>
@endif

@if (Route::is(['about-us', 'index-6', 'index','index-4']))
    <!-- Counter JS -->
    <script src="{{ URL::asset('/assets/js/counter.js') }}"></script>
@endif

@if (Route::is([
        'booking-2',
        'booking',
        'contact-us',
        'faq',
        'doctor-appointment-start',
        'doctor-upcoming-appointment',
        'doctor-appointments-grid',
        'my-patients',
        'appointments',
        'reviews',
        'doctor-cancelled-appointment',
        'patient-appointments',
        'patient-appointments-grid',
        'doctor-cancelled-appointment-2',
        'booking-1', 
        'doctor-appointment-details',
        'doctor-completed-appointment',
    ]))
    <!-- Daterangepikcer JS -->
    <script src="{{ URL::asset('/assets/js/moment.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
@endif

@if (Route::is(['dependent', 'medical-details']))
    <script src="{{ URL::asset('/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
@endif

@if (Route::is(['calendar']))
    <!-- Full Calendar JS -->
    <script src="{{ URL::asset('/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/fullcalendar.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/jquery.fullcalendar.js') }}"></script>
@endif



@if (Route::is([
        'cart',
        'doctor-profile',
        'map-list',
        'pharmacy-details',
        'pharmacy-search',
        'product-all',
        'product-description',
        'product-healthcare',
        'search',
        'index-10',
        'doctor-profile-2'
    ]))
    <!-- Fancybox JS -->
    <script src="{{ URL::asset('/assets/plugins/fancybox/jquery.fancybox.min.js') }}"></script>
@endif

@if (Route::is(['doctor-dashboard']))
    <!-- Circle Progress JS -->
    <script src="{{ URL::asset('/assets/js/circle-progress.min.js') }}"></script>
@endif

@if (Route::is(['doctor-profile-settings','doctor-appointment-start']))
    <!-- Dropzone JS -->
    <script src="{{ URL::asset('/assets/plugins/dropzone/dropzone.min.js') }}"></script>

    <!-- Bootstrap Tagsinput JS -->
    <script src="{{ URL::asset('/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}"></script>

    @if (Route::is(['dependent',
    'doctor-awards-settings',
    'doctor-business-settings',
    'doctor-clinics-settings',
    'doctor-education-settings',
    'doctor-experience-settings',
    'doctor-insurance-settings',
    'doctor-profile-settings'
    ]))
        <!-- Profile Settings JS -->
        <script src="{{ URL::asset('/assets/js/profile-settings.js') }}"></script>
    @endif
@endif

@if (Route::is(['doctor-search-grid', 'search-2']))
    <!-- Jquery UI -->
    <script src="{{ URL::asset('/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
@endif

@if (Route::is([
        'doctor-signup',
        'email-otp',
        'login-phone-otp',
        'login-phone',
        'mobile-otp',
        'onboarding-phone',
        'patient-signup',
        'doctor-register',
        'forgot-password',
        'forgot-password2',
    ]))
    <!-- Mobile Input -->
    <script src="{{ URL::asset('/assets/plugins/intltelinput/js/intlTelInput.js') }}"></script>
@endif

@if (Route::is([
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
        'index',
        'index-12',
        'index-13',
        'pharmacy-index',
        'index-14'
    ]))
    <!-- BacktoTop JS -->
    <script src="{{ URL::asset('/assets/js/backToTop.js') }}"></script>
@endif


<!-- Animation JS -->
<script src="{{ URL::asset('/assets/js/aos.js') }}"></script>


@if (Route::is([
        'index-3',
        'index-4',
        'index-5',
        'index-6',
        'index-7',
        'index-8',
        'index-9',
        'index-10',
        'index-11',
        'index',
        'index-12',
        'index-13',
        'onboarding-availability',
        'onboarding-consultation',
        'onboarding-cost',
        'onboarding-email-otp',
        'onboarding-email-step-2-verify',
        'onboarding-email',
        'onboarding-identity',
        'onboarding-lock',
        'onboarding-password',
        'onboarding-payments',
        'onboarding-personalize',
        'onboarding-phone-otp',
        'onboarding-phone',
        'onboarding-preferences',
        'onboarding-verification',
        'onboarding-verify-account',
        'patient-dependant-details',
        'patient-details',
        'patient-email',
        'patient-family-details',
        'patient-other-details',
        'patient-personalize',
        'pharmacy-index',
        'patient-dashboard',
        'patient-appointment-details',
        'patient-cancelled-appointment',
        'patient-completed-appointment',
        'patient-upcoming-appointment',
        'index-14',
        'doctor-profile'
    ]))
    <!-- Owl Carousel JS -->
    <script src="{{ URL::asset('/assets/js/owl.carousel.min.js') }}"></script>
@endif

@if (Route::is(['index-12', 'index-13']))
    <!-- Swiper Slider -->
    <script src="{{ URL::asset('/assets/plugins/swiper/js/swiper-bundle.min.js') }}"></script>
@endif

@if (Route::is(['index-11', 'index-10', 'index-12', 'index-13','index-7','index-14']))
    <!-- counterup JS -->
    <script src="{{ URL::asset('/assets/js/jquery.waypoints.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/jquery.counterup.min.js') }}"></script>
@endif

@if (Route::is(['chat-doctor', 'chat']))
    <!-- Swiper JS -->
    <script src="{{ URL::asset('assets/plugins/swiper/swiper.min.js') }}"></script>
@endif

@if (Route::is(['doctor-grid']))
	<!-- Rangeslider JS -->
	<script src="{{URL::asset('/assets/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script>
	<script src="{{URL::asset('/assets/plugins/ion-rangeslider/js/custom-rangeslider.js')}}"></script>
	<script src="{{URL::asset('/assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
@endif


<!-- Custom JS -->
<script src="{{ URL::asset('/assets/js/script.js') }}"></script>