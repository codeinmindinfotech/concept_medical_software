<!-- Header -->
@if(!Route::is(['index-2','index-3','index-5','index-4','index-6','index-7','index-8','index-9','index-10','index-11','index-12','pharmacy-index','index-13','index-14']))
<header class="header header-custom header-fixed inner-header relative">
    @endif
    <div class="container">

        <nav class="navbar navbar-expand-lg header-nav">
            <div class="navbar-header">
                <a id="mobile_btn" href="javascript:void(0);">
                    <span class="bar-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </a>
                <a href="{{url('index')}}" class="navbar-brand logo">

                    @if(!Route::is(['index-2','index-6','index-7','index-11']))
                    <img src="{{URL::asset('assets/img/logo.svg')}}" class="img-fluid" alt="Logo">
                    @endif
                    @if(Route::is(['index-2']))
                    <img src="{{URL::asset('assets/img/logo.png')}}" class="img-fluid" alt="Logo">
                    @endif
                    @if(Route::is(['index-6']))
                    <img src="{{URL::asset('assets/img/footer-logo.png')}}" class="img-fluid" alt="Logo">
                    @endif
                    @if(Route::is(['index-7']))
                    <img src="{{URL::asset('assets/img/veterinary-home-logo.svg')}}" class="img-fluid" alt="Logo">
                    @endif
                    @if(Route::is(['index-11']))
                    <img src="{{URL::asset('assets/img/logo-15.png')}}" class="img-fluid" alt="Logo">
                    @endif
                </a>
            </div>
           
            @if(!Route::is(['index-2','index-3','index-5','index-4','index-6','index-7','index-8','index-9','index-10','index-11','index-12','pharmacy-index','index-13','index-14']))
            <div class="header-menu">
                @endif


                <div class="main-menu-wrapper">
                    
                    <ul class="main-nav">
                        <li class="has-submenu megamenu {{ Request::is('/', 'index') ? 'active' : '' }}">
                            <a href="javascript:void(0);">Dashboard</a>
                        </li>
                        <li class="has-submenu megamenu {{ Request::is('/', 'index') ? 'active' : '' }}">
                            <a href="javascript:void(0);">Planner</a>
                        </li>
                        <li class="has-submenu megamenu {{ Request::is('calendar') ? 'active' : '' }}">
                            <a href="{{guard_route('calendar')}}">Diary</a>
                        </li>
                        <li class="has-submenu {{ Request::is('map-grid', 'map-list', 'search', 'search-2', 'doctor-profile', 'doctor-profile-2','booking', 'booking-2', 'checkout', 'booking-success', 'patient-dashboard', 'favourites', 'chat', 'profile-settings', 'change-password', 'add-dependent', 'dependent', 'edit-dependent', 'patient-upcoming-appointment') ? 'active' : '' }}">
                            <a href="javascript:void(0);">Patients <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li class="{{ Request::is('patient-dashboard') ? 'active' : '' }}"><a href="{{url('patient-dashboard')}}">Patient Dashboard</a></li>
                                <li class="has-submenu {{ Request::is('map-grid', 'map-list','map-list-availability') ? 'active' : '' }}">
                                    <a href="javascript:void(0);">Doctors</a>
                                    <ul class="submenu inner-submenu">
                                        <li class="{{ Request::is('map-grid') ? 'active' : '' }}"><a href="{{url('map-grid')}}">Map Grid</a></li>
                                        <li class="{{ Request::is('map-list') ? 'active' : '' }}"><a href="{{url('map-list')}}">Map List</a></li>
                                        <li class="{{ Request::is('map-list-availability') ? 'active' : '' }}"><a href="{{url('map-list-availability')}}">Map with Availability</a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu {{ Request::is('search', 'search-2') ? 'active' : '' }}">
                                    <a href="javascript:void(0);">Search Doctor</a>
                                    <ul class="submenu inner-submenu">
                                        <li class="{{ Request::is('search') ? 'active' : '' }}"><a href="{{url('search')}}">Search Doctor 1</a></li>
                                        <li class="{{ Request::is('search-2') ? 'active' : '' }}"><a href="{{url('search-2')}}">Search Doctor 2</a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu {{ Request::is('doctor-profile', 'doctor-profile-2') ? 'active' : '' }}">
                                    <a href="javascript:void(0);">Doctor Profile</a>
                                    <ul class="submenu inner-submenu">
                                        <li class="{{ Request::is('doctor-profile') ? 'active' : '' }}"><a href="{{url('doctor-profile')}}">Doctor Profile 1</a></li>
                                        <li class="{{ Request::is('doctor-profile-2') ? 'active' : '' }}"><a href="{{url('doctor-profile-2')}}">Doctor Profile 2</a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu {{ Request::is('booking','booking-1', 'booking-2','booking-popup') ? 'active' : '' }}">
                                    <a href="javascript:void(0);">Booking</a>
                                    <ul class="submenu inner-submenu">
                                        <li class="{{ Request::is('booking') ? 'active' : '' }}"><a href="{{url('booking')}}">Booking</a></li>
                                        <li class="{{ Request::is('booking-1') ? 'active' : '' }}"><a href="{{url('booking-1')}}">Booking 1</a></li>
                                        <li class="{{ Request::is('booking-2') ? 'active' : '' }}"><a href="{{url('booking-2')}}">Booking 2</a></li>
                                        <li class="{{ Request::is('booking-popup') ? 'active' : '' }}"><a href="{{url('booking-popup')}}">Booking Popup</a></li>
                                    </ul>
                                </li>
                                <li class="{{ Request::is('checkout') ? 'active' : '' }}"><a href="{{url('checkout')}}">Checkout</a></li>
                                <li class="{{ Request::is('booking-success') ? 'active' : '' }}"><a href="{{url('booking-success')}}">Booking Success</a></li>
                                <li class="{{ Request::is('favourites') ? 'active' : '' }}"><a href="{{url('favourites')}}">Favourites</a></li>
                                <li class="{{ Request::is('chat') ? 'active' : '' }}"><a href="{{url('chat')}}">Chat</a></li>
                                <li class="{{ Request::is('profile-settings') ? 'active' : '' }}"><a href="{{url('profile-settings')}}">Profile Settings</a></li>
                                <li class="{{ Request::is('change-password') ? 'active' : '' }}"><a href="{{url('change-password')}}">Change Password</a></li>
                            </ul>
                        </li>
                       
                    </ul>
                </div>

                @if(Route::is(['doctor-dashboard',
                'appointments',
                'available-timings',
                'my-patients',
                'patient-profile',
                'chat-doctor',
                'invoices',
                'doctor-profile-settings',
                'reviews',
                'doctor-blog',

                'doctor-add-blog',
                'accounts',
                'doctor-appointment-details',
                'doctor-appointments-grid',
                'doctor-appointment-start',
                'doctor-awards-settings',
                'doctor-business-settings',
                'doctor-cancelled-appointment',
                'doctor-cancelled-appointment-2',
                'doctor-change-password',
                'doctor-clinics-settings',
                'doctor-completed-appointment',
                'doctor-education-settings',
                'doctor-experience-settings',
                'doctor-insurance-settings',
                'doctor-payment',
                'doctor-pending-blog',
                'doctor-request',
                'doctor-specialities',
                'doctor-upcoming-appointment',
                'edit-blog',
                'social-media'

                ]))
                <ul class="nav header-navbar-rht">
                    <li class="searchbar">
                        <a href="javascript:void(0);"><i class="feather-search"></i></a>
                        <div class="togglesearch">
                            <form action="{{url('search')}}">
                                <div class="input-group">
                                    <input type="text" class="form-control">
                                    <button type="submit" class="btn">Search</button>
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="header-theme noti-nav">
                        <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                            <i class="isax isax-sun-1"></i>
                        </a>
                        <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                            <i class="isax isax-moon"></i>
                        </a>
                    </li>

                    <!-- Notifications -->
                    <li class="nav-item dropdown noti-nav me-3 pe-0">
                        <a href="#" class="dropdown-toggle active-dot active-dot-danger nav-link p-0" data-bs-toggle="dropdown">
                            <i class="isax isax-notification-bing"></i>
                        </a>
                        <div class="dropdown-menu notifications dropdown-menu-end ">
                            <div class="topnav-dropdown-header">
                                <span class="notification-title">Notifications</span>
                            </div>
                            <div class="noti-content">
                                <ul class="notification-list">
                                    <li class="notification-message">
                                        <a href="#">
                                            <div class="notify-block d-flex">
                                                <span class="avatar">
                                                    <img class="avatar-img" alt="Ruby perin" src="{{URL::asset('assets/img/clients/client-01.jpg')}}">
                                                </span>
                                                <div class="media-body">
                                                    <h6>Travis Tremble <span class="notification-time">18.30 PM</span></h6>
                                                    <p class="noti-details">Sent a amount of $210 for his Appointment <span class="noti-title">Dr.Ruby perin </span></p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="notification-message">
                                        <a href="#">
                                            <div class="notify-block d-flex">
                                                <span class="avatar">
                                                    <img class="avatar-img" alt="Hendry Watt" src="{{URL::asset('assets/img/clients/client-02.jpg')}}">
                                                </span>
                                                <div class="media-body">
                                                    <h6>Travis Tremble <span class="notification-time">12 Min Ago</span></h6>
                                                    <p class="noti-details"> has booked her appointment to <span class="noti-title">Dr. Hendry Watt</span></p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="notification-message">
                                        <a href="#">
                                            <div class="notify-block d-flex">
                                                <div class="avatar">
                                                    <img class="avatar-img" alt="Maria Dyen" src="{{URL::asset('assets/img/clients/client-03.jpg')}}">
                                                </div>
                                                <div class="media-body">
                                                    <h6>Travis Tremble <span class="notification-time">6 Min Ago</span></h6>
                                                    <p class="noti-details"> Sent a amount $210 for his Appointment <span class="noti-title">Dr.Maria Dyen</span></p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="notification-message">
                                        <a href="#">
                                            <div class="notify-block d-flex">
                                                <div class="avatar avatar-sm">
                                                    <img class="avatar-img" alt="client-image" src="{{URL::asset('assets/img/clients/client-04.jpg')}}">
                                                </div>
                                                <div class="media-body">
                                                    <h6>Travis Tremble <span class="notification-time">8.30 AM</span></h6>
                                                    <p class="noti-details"> Send a message to his doctor</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <!-- /Notifications -->

                    <!-- Messages -->
                    <li class="nav-item noti-nav me-3 pe-0">
                        <a href="{{url('chat-doctor')}}" class="dropdown-toggle nav-link active-dot active-dot-success p-0">
                            <i class="isax isax-message-2"></i>
                        </a>
                    </li>
                    <!-- /Messages -->

                    <!-- Cart -->
                    <li class="nav-item dropdown noti-nav view-cart-header me-3 pe-0">
                        <a href="#" class="dropdown-toggle nav-link active-dot active-dot-purple p-0 position-relative" data-bs-toggle="dropdown">
                            <i class="isax isax-shopping-cart"></i>
                        </a>
                        <div class="dropdown-menu notifications dropdown-menu-end">
                            <div class="shopping-cart">
                                <ul class="shopping-cart-items list-unstyled">
                                    <li class="clearfix">
                                        <div class="close-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                        <a href="{{url('product-description')}}"><img class="avatar-img rounded" src="{{URL::asset('assets/img/products/product.jpg')}}" alt="User Image"></a>
                                        <a href="{{url('product-description')}}" class="item-name">Benzaxapine Croplex</a>
                                        <span class="item-price">$849.99</span>
                                        <span class="item-quantity">Quantity: 01</span>
                                    </li>

                                    <li class="clearfix">
                                        <div class="close-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                        <a href="{{url('product-description')}}"><img class="avatar-img rounded" src="{{URL::asset('assets/img/products/product1.jpg')}}" alt="User Image"></a>
                                        <a href="{{url('product-description')}}" class="item-name">Ombinazol Bonibamol</a>
                                        <span class="item-price">$1,249.99</span>
                                        <span class="item-quantity">Quantity: 01</span>
                                    </li>

                                    <li class="clearfix">
                                        <div class="close-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                        <a href="{{url('product-description')}}"><img class="avatar-img rounded" src="{{URL::asset('assets/img/products/product2.jpg')}}" alt="User Image"></a>
                                        <a href="{{url('product-description')}}" class="item-name">Dantotate Dantodazole</a>
                                        <span class="item-price">$129.99</span>
                                        <span class="item-quantity">Quantity: 01</span>
                                    </li>
                                </ul>
                                <div class="booking-summary pt-3">
                                    <div class="booking-item-wrap">
                                        <ul class="booking-date">
                                            <li>Subtotal <span>$5,877.00</span></li>
                                            <li>Shipping <span>$25.00</span></li>
                                            <li>Tax <span>$0.00</span></li>
                                            <li>Total <span>$5.2555</span></li>
                                        </ul>
                                        <div class="booking-total">
                                            <ul class="booking-total-list text-align">
                                                <li>
                                                    <div class="clinic-booking pt-3">
                                                        <a class="apt-btn" href="{{url('cart')}}">View Cart</a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="clinic-booking pt-3">
                                                        <a class="apt-btn" href="{{url('product-checkout')}}">Checkout</a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- /Cart -->

                    <!-- User Menu -->
                    <li class="nav-item dropdown has-arrow logged-item">
                        <a href="#" class="nav-link ps-0" data-bs-toggle="dropdown">
                            <span class="user-img">
                                <img class="rounded-circle" src="{{URL::asset('assets/img/doctors-dashboard/doctor-profile-img.jpg')}}" width="31" alt="Darren Elder">
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="user-header">
                                <div class="avatar avatar-sm">
                                    <img src="{{URL::asset('assets/img/doctors-dashboard/doctor-profile-img.jpg')}}" alt="User Image" class="avatar-img rounded-circle">
                                </div>
                                <div class="user-text">
                                    <h6>Dr Edalin Hendry</h6>
                                    <p class="text-muted mb-0">Doctor</p>
                                </div>
                            </div>
                            <a class="dropdown-item" href="{{url('doctor-dashboard')}}">Dashboard</a>
                            <a class="dropdown-item" href="{{url('doctor-profile-settings')}}">Profile Settings</a>
                            <a class="dropdown-item" href="{{url('login')}}">Logout</a>
                        </div>
                    </li>
                    <!-- /User Menu -->
                </ul>
                @endif
                {{-- @php
$currentRoute = Route::currentRouteName();
dd($currentRoute);
@endphp --}}



                @if(Route::is(['patient.dashboard.index','patient.*']))

                <ul class="nav header-navbar-rht">
                    <li class="searchbar">
                        <a href="javascript:void(0);"><i class="feather-search"></i></a>
                        <div class="togglesearch">
                            <form action="{{url('search')}}">
                                <div class="input-group">
                                    <input type="text" class="form-control">
                                    <button type="submit" class="btn">Search</button>
                                </div>
                            </form>
                        </div>
                    </li>

                    <!-- Patient Notifications -->
                        <li class="nav-item dropdown noti-nav me-3 pe-0">
                            <a href="#" id="notification-icon"
                                class="dropdown-toggle nav-link p-0"
                                data-bs-toggle="dropdown">
                                    <i class="isax isax-notification-bing"></i>
                            </a>                       
                        <div class="dropdown-menu notifications dropdown-menu-end ">
                            <div class="topnav-dropdown-header">
                                <span class="notification-title">Notifications</span>
                            </div>
                            <div class="noti-content">
                                <ul class="notification-list" id="front_notification">
                                </ul>
                            </div>
                        </div>
                    </li>
                    <!-- /Notifications -->

                    <!-- Cart -->
                    <li class="nav-item dropdown noti-nav view-cart-header me-3 pe-0">
                        <a href="#" class="dropdown-toggle nav-link active-dot active-dot-purple p-0 position-relative" data-bs-toggle="dropdown">
                            <i class="isax isax-shopping-cart"></i>
                        </a>
                        <div class="dropdown-menu notifications dropdown-menu-end">
                            <div class="shopping-cart">
                                <ul class="shopping-cart-items list-unstyled">
                                    <li class="clearfix">
                                        <div class="close-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                        <a href="{{url('product-description')}}"><img class="avatar-img rounded" src="{{URL::asset('assets/img/products/product.jpg')}}" alt="User Image"></a>
                                        <a href="{{url('product-description')}}" class="item-name">Benzaxapine Croplex</a>
                                        <span class="item-price">$849.99</span>
                                        <span class="item-quantity">Quantity: 01</span>
                                    </li>

                                    <li class="clearfix">
                                        <div class="close-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                        <a href="{{url('product-description')}}"><img class="avatar-img rounded" src="{{URL::asset('assets/img/products/product1.jpg')}}" alt="User Image"></a>
                                        <a href="{{url('product-description')}}" class="item-name">Ombinazol Bonibamol</a>
                                        <span class="item-price">$1,249.99</span>
                                        <span class="item-quantity">Quantity: 01</span>
                                    </li>

                                    <li class="clearfix">
                                        <div class="close-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                        <a href="{{url('product-description')}}"><img class="avatar-img rounded" src="{{URL::asset('assets/img/products/product2.jpg')}}" alt="User Image"></a>
                                        <a href="{{url('product-description')}}" class="item-name">Dantotate Dantodazole</a>
                                        <span class="item-price">$129.99</span>
                                        <span class="item-quantity">Quantity: 01</span>
                                    </li>
                                </ul>
                                <div class="booking-summary pt-3">
                                    <div class="booking-item-wrap">
                                        <ul class="booking-date">
                                            <li>Subtotal <span>$5,877.00</span></li>
                                            <li>Shipping <span>$25.00</span></li>
                                            <li>Tax <span>$0.00</span></li>
                                            <li>Total <span>$5.2555</span></li>
                                        </ul>
                                        <div class="booking-total">
                                            <ul class="booking-total-list text-align">
                                                <li>
                                                    <div class="clinic-booking pt-3">
                                                        <a class="apt-btn" href="{{url('cart')}}">View Cart</a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="clinic-booking pt-3">
                                                        <a class="apt-btn" href="{{url('product-checkout')}}">Checkout</a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- /Cart -->

                    <!-- User Menu -->
                    <li class="nav-item dropdown has-arrow logged-item">
                        <a href="#" class="nav-link ps-0" data-bs-toggle="dropdown">
                            <span class="user-img">
                                <img class="rounded-circle" src="{{URL::asset('assets/img/doctors-dashboard/profile-06.jpg')}}" width="31" alt="Darren Elder">
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="user-header">
                                <div class="avatar avatar-sm">
                                    <img src="{{URL::asset('assets/img/doctors-dashboard/profile-06.jpg')}}" alt="User Image" class="avatar-img rounded-circle">
                                </div>
                                <div class="user-text">
                                    <h6>{{ Auth::user()->name ?? Auth::user()->full_name }}</h6>
                                    <p class="text-muted mb-0">Patient</p>
                                </div>
                            </div>
                            <a class="dropdown-item" href="{{guard_route('patient.dashboard.index')}}">Dashboard</a>
                            <a class="dropdown-item" href="{{url('profile-settings')}}">Profile Settings</a>
                            <a class="dropdown-item" href="{{guard_route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{guard_route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    <!-- /User Menu -->
                </ul>

                @endif
               

        </nav>
    </div>
</header>
<!-- /Header -->

