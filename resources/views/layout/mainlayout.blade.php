<!DOCTYPE html> 
<html lang="en">
	<head>
		
        <meta charset="utf-8">
		<title>Doccure Patient</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="The responsive professional Doccure template offers many features, like scheduling appointments with  top doctors, clinics, and hospitals via voice, video call & chat.">
		<meta name="keywords" content="practo clone, doccure, doctor appointment, Practo clone html template, doctor booking template">
		<meta name="author" content="Practo Clone HTML Template - Doctor Booking Template">
		<meta property="og:url" content="https://doccure.dreamstechnologies.com/html/">
		<meta property="og:type" content="website">
		<meta property="og:title" content="Doctors Appointment HTML Website Templates | Doccure">
		<meta property="og:description" content="The responsive professional Doccure template offers many features, like scheduling appointments with  top doctors, clinics, and hospitals via voice, video call & chat.">
		<meta property="og:image" content="assets/img/preview-banner.jpg">
		<meta name="twitter:card" content="summary_large_image">
		<meta property="twitter:domain" content="https://doccure.dreamstechnologies.com/html/">
		<meta property="twitter:url" content="https://doccure.dreamstechnologies.com/html/">
		<meta name="twitter:title" content="Doctors Appointment HTML Website Templates | Doccure">
		<meta name="twitter:description" content="The responsive professional Doccure template offers many features, like scheduling appointments with  top doctors, clinics, and hospitals via voice, video call & chat.">
		<meta name="twitter:image" content="assets/img/preview-banner.jpg">	
        <meta name="csrf-token" content="{{ csrf_token() }}">


    @if (
        !Route::is(
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
            'patient-dependant-details',
            'patient-details',
            'patient-email',
            'patient-family-details',
            'patient-other-details',
            'patient-personalize'))
        <title>Doccure</title>
    @endif
    @if (Route::is('onboarding-availability'))
        <title>Onboarding Availability</title>
    @endif
    @if (Route::is('onboarding-consultation'))
        <title>Onboarding Consultation</title>
    @endif
    @if (Route::is('onboarding-cost'))
        <title>Onboarding Cost</title>
    @endif
    @if (Route::is(
            'onboarding-email-otp',
            'onboarding-email',
            'onboarding-phone-otp',
            'patient-email',
            'patient-family-details',
            'onboarding-phone',
            'onboarding-verification',
            'patient-details',
            'patient-dependant-details',
            'patient-other-details',
            'patient-personalize'))
        <title>Onboarding Verification</title>
    @endif
    @if (Route::is('onboarding-email-step-2-verify'))
        <title>Onboarding Verification Step2</title>
    @endif
    @if (Route::is('onboarding-identity'))
        <title>Onboarding Identity</title>
    @endif
    @if (Route::is('onboarding-lock'))
        <title>Onboarding Lock</title>
    @endif
    @if (Route::is('onboarding-password'))
        <title>Onboarding Password</title>
    @endif
    @if (Route::is('onboarding-payments'))
        <title>Onboarding Payments</title>
    @endif
    @if (Route::is('onboarding-personalize'))
        <title>Onboarding Personalize</title>
    @endif
    @if (Route::is('onboarding-phone-otp'))
        <title>Onboarding Personalize</title>
    @endif
    @if (Route::is('onboarding-preferences'))
        <title>Onboarding Preferences</title>
    @endif


    <!-- Favicon -->
    <link rel="icon" href="{{ URL::asset('/assets/img/favicon.png') }}" type="image/x-icon">
    @include('layout.partials.head')
</head>
@stack('styles')
@if (
    !Route::is([
        'index-12',
        'index-6',
        'map-list',
        'patient-register-step1',
        'patient-register-step2',
        'patient-register-step3',
        'patient-register-step4',
        'patient-register-step5',
        'patient-register',
        'pharmacy-register-step1',
        'pharmacy-register-step2',
        'pharmacy-register-step3',
        'pharmacy-register',
        'register',
        'reset-password',
        'signup-success',
        'signup',
        'video-call',
        'voice-call',
        'login-phone',
        'map-grid',
        'onboarding-verification',
        'chat-doctor',
        'chat',
        'doctor-register-step1',
        'doctor-register-step2',
        'doctor-register-step3',
        'patient-register-step1',
        'patient-register-step2',
        'patient-register-step3',
        'patient-register-step4',
        'patient-register-step5',
        'pharmacy-index',
    ]))
    <body>
@endif
@if (Route::is(['onboarding-verification']))
    <body class="onboard-page">
@endif
@if (Route::is(['map-grid']))
    <body class="map-page">
@endif
@if (Route::is([
        'patient-register-step1',
        'patient-register-step2',
        'patient-register-step3',
        'patient-register-step4',
        'patient-register-step5',
        'pharmacy-register-step1',
        'pharmacy-register-step2',
        'pharmacy-register-step3',
        'pharmacy-register',
        'register',
        'forgot-password',
        'login-email',
        'doctor-register',
        'doctor-register-step1',
        'doctor-register-step2',
        'doctor-register-step3',
        'login'
    ]))

    <body class="account-page">
@endif
@if (Route::is(['forgot-password2', 'patient-signup', 'reset-password', 'signup-success', 'signup', 'login-phone']))

    <body class="login-body">
@endif
@if (Route::is(['video-call', 'voice-call']))

    <body class="call-page">
@endif
@if (Route::is(['index-6']))

    <body class="body-eleven">
@endif
@if (Route::is(['index-12']))

    <body class="home-pg-twelve">
@endif
@if (Route::is(['chat-doctor', 'chat']))

<body class="main-chat-blk">
@endif
@if (Route::is(['pharmacy-index']))

    <body class="pharmacy-body">
@endif
@if (Route::is(['map-list']))

    <body class="map-page">

        {{-- <!-- Loader -->
        <div id="loader">
            <div class="loader">
                <span></span>
                <span></span>
            </div>
        </div>
        <!-- /Loader  --> --}}
@endif
<!-- Main Wrapper -->
@if (
    !Route::is([
        'index-3',
        'index-4',
        'index-5',
        'index-7',
        'index-8',
        'index-9',
        'index-10',
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
        'onboarding-phone',
        'onboarding-phone-otp',
        'onboarding-preferences',
        'onboarding-verification',
        'onboarding-verify-account',
        'patient-dependant-details',
        'patient-email',
        'patient-family-details',
        'patient-other-details',
        'patient-personalize',
        'patient-details',
        'chat',
        'chat-doctor',
    ]))
    <div class="main-wrapper">
@endif
@if (Route::is([
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
        'onboarding-phone',
        'onboarding-phone-otp',
        'onboarding-preferences',
        'onboarding-verification',
        'onboarding-verify-account',
        'patient-dependant-details',
        'patient-email',
        'patient-family-details',
        'patient-other-details',
        'patient-personalize',
        'patient-details',
    ]))
    <!-- Onboarding Availability -->
    <div class="onboard-wrapper">
@endif
@if (Route::is(['index-3']))
<div class="main-wrapper home-three">
@endif
@if (Route::is(['index-4']))
    <div class="main-wrapper multi-optn">
@endif
@if (Route::is(['index-5']))
    <div class="main-wrapper home-ten">
@endif
@if (Route::is(['index-7']))
    <div class="main-wrapper home-twelve">
@endif
@if (Route::is(['index-8']))
    <div class="main-wrapper home-twelve home-thirteen ">
@endif
@if (Route::is(['index-9']))
    <div class="main-wrapper home-fourteen">
@endif
@if (Route::is(['index-10']))
    <div class="main-wrapper home-fifteen">
@endif
@if (Route::is(['index-11']))
    <div class="main-wrapper home-sixteen">
@endif
@if (Route::is(['index-13']))
    <div class="main-wrapper home-pg-fourteen">
@endif
@if (Route::is(['index-14']))
<div class="main-wrapper home-dentist">
@endif
@if (Route::is(['chat']))
    <div class="main-wrapper chat-wrapper">
@endif
@if (Route::is(['chat-doctor']))
    <div class="main-wrapper chat-wrapper">
@endif
@if (
    !Route::is([
        'doctor-register-step1',
        'doctor-register-step2',
        'doctor-register-step3',
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
        'patient-register-step1',
        'patient-register-step2',
        'patient-register-step3',
        'patient-register-step4',
        'patient-register-step5',
        'pharmacy-register-step1',
        'pharmacy-register-step2',
        'pharmacy-register-step3',
        'coming-soon',
        'doctor-signup',
        'email-otp',
        'error-404',
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
        'booking-popup'
        
    ]))
    @include('layout.partials.header')
@endif
@yield('content')
@if (
    !Route::is([
        'booking-success-one',
        'paitent-details',
        'consultation',
        'doctor-register-step1',
        'doctor-register-step2',
        'doctor-register-step3',
        'doctor-signup',
        'email-otp',
        'forgot-password2',
        'login-email-otp',
        'login-email',
        'login-phone-otp',
        'login-phone',
        'mobile-otp',
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
        'patient-register-step1',
        'patient-register-step2',
        'patient-register-step3',
        'patient-register-step4',
        'patient-register-step5',
        'payment',
        'pharmacy-register-step1',
        'pharmacy-register-step2',
        'pharmacy-register-step3',
        'reset-password',
        'signup-success',
        'signup',
        'patient-signup',
        'coming-soon',
        'doctor-signup',
        'email-otp',
        'error-404',
        'error-500',
        'maintenance',
        'chat-doctor',
        'booking',
        'booking-popup'
    ]))
    @include('layout.partials.footer')
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
        'index-14',
        'search-2',
        'about-us',
        'doctor-search-grid',
        'doctor-signup',
        'login-phone',
        'patient-signup',
        'forgot-password2',
        'login-phone-otp',
        'login-email-otp',
        'login-email',
        'error-404',
        'error-500',
        'payment',
        'booking-success-one',
        'coming-soon',
        'consultation',
        'maintenance',
        'pricing',
        'privacy-policy',
        'reset-password',
        'terms-condition',
    ]))
    
    
@endif
@component('components.admin.cursor')
@endcomponent
</div>
<div class="load-more-spinner">
    <i class="fa fa-spinner"></i>
</div>
<!-- /Main Wrapper -->
@stack('modals')
{{-- @component('components.admin.modalpopup')
@endcomponent --}}
@include('layout.partials.footer-scripts')

</body>

</html>
