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
    <!-- Favicon -->
    <link rel="icon" href="{{ URL::asset('/assets/img/favicon.png') }}" type="image/x-icon">
    @include('layout.partials.head')
</head>
@stack('styles')

@if (Route::is(['forgot-password2', 'patient-signup', 'reset-password', 'signup-success', 'signup', 'login-phone']))
<body class="login-body">
@endif
<!-- Main Wrapper -->
<div class="main-wrapper">
    <x-set-calendar-days-modal :clinics="$clinics" />
    @include('layout.partials.header')

    @yield('content')

    @include('layout.partials.footer')
    @component('components.admin.cursor')
    @endcomponent
    </div>

    @stack('modals')
    <!-- Modal -->
</div>
@component('components.admin.loader')
@endcomponent
@include('layout.partials.footer-scripts')

</body>

</html>

