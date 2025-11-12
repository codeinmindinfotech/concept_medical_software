<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="The responsive professional Doccure template offers many features, like scheduling appointments with  top doctors, clinics, and hospitals via voice, video call & chat.">
		<meta name="keywords" content="practo clone, doccure, doctor appointment, Practo clone html template, doctor booking template">
		<meta name="author" content="Practo Clone HTML Template - Doctor Booking Template">
		<meta property="og:url" content="https://doccure.dreamstechnologies.com/html/">
		<meta property="og:type" content="website">
		<meta property="og:title" content="Doctors Appointment HTML Website Templates | Doccure">
		<meta property="og:description" content="The responsive professional Doccure template offers many features, like scheduling appointments with  top doctors, clinics, and hospitals via voice, video call & chat.">
		<meta property="og:image" content="assets/img/preview-banner.jpg')}}">
		<meta name="twitter:card" content="summary_large_image">
		<meta property="twitter:domain" content="https://doccure.dreamstechnologies.com/html/">
		<meta property="twitter:url" content="https://doccure.dreamstechnologies.com/html/">
		<meta name="twitter:title" content="Doctors Appointment HTML Website Templates | Doccure">
		<meta name="twitter:description" content="The responsive professional Doccure template offers many features, like scheduling appointments with  top doctors, clinics, and hospitals via voice, video call & chat.">
		<meta name="twitter:image" content="assets/img/preview-banner.jpg')}}">	
        <title>Doccure - Pharmacy Dashboard</title>
    @include('layout.partials.head_pharmacy_admin')
</head>

<body>
    @if (Route::is(['forgot-password', 'login', 'register']))
        <!-- Main Wrapper -->
        <div class="main-wrapper login-body">
    @endif
    @if (!Route::is(['forgot-password', 'login', 'register']))
    <div class="main-wrapper">
        @endif
    @if (!Route::is(['login', 'register', 'forgot-password']))
        @include('layout.partials.header_pharmacy_admin')
        @include('layout.partials.nav_pharmacy_admin')
    @endif
    @yield('content')
    @component('components.modalpopup_pharmacy_admin')
    @endcomponent
    @include('layout.partials.footer_pharmacy_admin_scripts')
</body>

</html>
