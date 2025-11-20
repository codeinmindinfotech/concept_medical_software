<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ url('/assets/css/bootstrap.min.css') }}">

<!-- Fontawesome CSS -->
<link rel="stylesheet" href="{{ url('/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ url('/assets/plugins/fontawesome/css/all.min.css') }}">
@if (Route::is(['index']))
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
@endif
<!-- Feathericon CSS -->
<link rel="stylesheet" href="{{ url('/assets/css/feather.css') }}">

<!-- Apple Touch Icon -->
<link rel="apple-touch-icon" sizes="180x180" href="{{url('assets/img/apple-touch-icon.png')}}">

@if(!Route::is([ 'error-404',
'error-500',
'forgot-password2',
'login-email',
'login-email-otp',
'login-phone',
'login-phone-otp',
'maintenance',
'mobile-otp',
'patient-signup',
'reset-password',
'signup',
'signup-success',
'register',
'booking-popup'
]))
<!-- Theme Settings Js -->
<script src="{{URL::asset('assets/js/theme-script.js')}}"></script>
@endif


<!-- Iconsax CSS-->
<link rel="stylesheet" href="{{url('assets/css/iconsax.css')}}">

@if (Route::is([
        'accounts',
        'add-dependent',
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
        'patient-Personalize',
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
        'index-12',
        'index-13',
        'dependent',
        'doctor-dashboard',
        'my-patients',
        'social-media',
        'doctor-appointment-start',
        'doctor-upcoming-appointment',
        'doctor-appointments-grid',
        'invoices',
        'doctor-specialities',
        'doctor-payment',
        'appointments',
        'reviews',
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
        'doctor-insurance-settings',
        'doctor-education-settings',
        'paitent-details',
        'doctor-cancelled-appointment-2',
        'doctor-request',
        'index-14',
        'hospitals', 
        'clinic',
        'booking-popup',
        'booking',
        'map-list-availability',
        'map-list',
        'map-grid',
        'index-4',
        ]))
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ url('/assets/plugins/select2/css/select2.min.css') }}">
@endif

@if (Route::is([
        'add-dependent',
        'calendar',
        'edit-dependent',
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
        'patient-Personalize',
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
        'available-timings',
        'medical-records',
        'medical-details',
        'index-14',
        'doctor-awards-settings',
        'doctor-business-settings',
        'doctor-education-settings',
        'doctor-cancelled-appointment-2',
        'booking-popup',
        'booking',
        'doctor-grid',
        'map-list-availability',
        'map-list',
        'map-grid',
        'doctor-clinics-settings',
        'doctor-insurance-settings',
        'doctor-specialities',
        'index-3',
        'index-4',
    ]))
    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{ url('/assets/css/bootstrap-datetimepicker.min.css') }}">
@endif

@if (Route::is([
        'add-dependent',
        'booking',
        'contact-us',
        'faq',
        'booking-2',
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
    <!-- Daterangepikcer CSS -->
    <link rel="stylesheet" href="{{ url('/assets/plugins/daterangepicker/daterangepicker.css') }}">
@endif

@if (Route::is(['calendar']))
    <!-- Full Calander CSS -->
    <link rel="stylesheet" href="{{ url('/assets/css/fullcalendar.min.css') }}">
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
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="{{ url('/assets/plugins/fancybox/jquery.fancybox.min.css') }}">
@endif

@if (Route::is(['doctor-search-grid', 'search-2']))
    <!-- Jquery CSS-->
    <link rel="stylesheet" href="{{ url('/assets/plugins/jquery-ui/jquery-ui.css') }}">
@endif

@if (Route::is(['medical-details', 'dependent']))
    <!-- Jquery UI-->
    <link rel="stylesheet" href="{{ url('/assets/plugins/jquery-ui/jquery-ui.min.css') }}">
@endif

@if (Route::is(['doctor-profile-settings', 'doctor-appointment-start']))
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ url('/assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}">

    <link rel="stylesheet" href="{{ url('/assets/plugins/dropzone/dropzone.min.css') }}">
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
    <!-- Mobile CSS-->
    <link rel="stylesheet" href="{{ url('/assets/plugins/intltelinput/css/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ url('/assets/plugins/intltelinput/css/demo.css') }}">
@endif

@if (Route::is([
        'index',
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
        'pharmacy-index',
        'index-14'
    ]))
    <!-- Animation CSS -->
    <link rel="stylesheet" href="{{ url('/assets/css/aos.css') }}">
@endif

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
        'index-12',
        'index-13',
        'index',
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
        'patient-Personalize',
        'pharmacy-index',
        'patient-dashboard',
        'patient-appointment-details',
        'patient-cancelled-appointment',
        'patient-completed-appointment',
        'patient-upcoming-appointment',
        'index-14',
        'doctor-profile'
    ]))
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="{{ url('/assets/css/owl.carousel.min.css') }}">
@endif

@if (Route::is(['index-2','index-10','index-11', 'index-12', 'index-13','index-14','booking-2','index-5']))
    <!-- Slick slider CSS -->
    <link rel="stylesheet" href="{{ url('/assets/css/slick.css') }}">
@endif

@if (Route::is(['index-12', 'index-13','chat']))
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="{{ url('/assets/plugins/swiper/css/swiper-bundle.min.css') }}">
@endif

@if (Route::is(['index-10', 'index-11', 'index-12', 'index-13']))
    <link rel="stylesheet" href="{{ url('/assets/css/animate.css') }}">
@endif

@if (Route::is(['patient-dashboard','patient-appointment-details','patient-cancelled-appointment','patient-completed-appointment', 'patient-upcoming-appointment']))
    <!-- Apex Css -->
    <link rel="stylesheet" href="{{ url('/assets/plugins/apex/apexcharts.css') }}">
@endif

@if (Route::is(['chat-doctor','chat']))
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="{{url('assets/plugins/swiper/swiper.min.css')}}">
@endif

@if (Route::is(['doctor-grid']))
    <!-- Rangeslider CSS -->
    <link rel="stylesheet" href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.css">
    <link rel="stylesheet" href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.min.css">
@endif


<!-- Main CSS -->
<link rel="stylesheet" href="{{ url('/assets/css/custom.css') }}">
