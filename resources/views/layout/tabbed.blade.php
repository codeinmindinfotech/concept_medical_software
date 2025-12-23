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
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Doccure - Dashboard</title>
    @include('layout.partials.head_admin')
</head>
<body class="mini-sidebar">
    @if (!Route::is(['superadmin.login', 'register', 'forgot-password', 'lock-screen', 'error-404', 'error-500']))
        @include('layout.partials.header_admin')
        @include('layout.partials.nav_admin')
    @endif
    <div class="page-wrapper">
        <div class="container-fluid px-1">
            <div class="row">
                <div class="col-12 col-md-10">
                    <div class="tab-content" id="tab-content">
                        @yield('tab-content')
                    </div>
                </div>
                {{-- Sidebar Tabs --}}
                <div class="col-12 col-md-2">
                    <div class="nav flex-column nav-pills position-sticky" id="tab-nav"
                        style="z-index: 989;top: 60px;"
                        role="tablist" aria-orientation="vertical">
                        @yield('tab-navigation')
                    </div>
                </div>
            </div>
        </div>
    </div>    
    @stack('modals')    
    @include('layout.partials.footer_admin-scripts')
    <script src="{{ asset('theme/patient-dashboard.js') }}"></script>

</body>
</html>