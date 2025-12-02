<!-- Header -->
<header class="header header-custom header-fixed inner-header relative">
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
                    <img src="{{URL::asset('assets/img/logo.png')}}" class="img-fluid" alt="Logo">
                </a>
            </div>
            <div class="main-menu-wrapper">
                @php
                $dashboardRoutes = ['patient.dashboard', 'doctor.dashboard', 'clinic.dashboard'];
                $calendarRoutes = ['patient.calendar', 'doctor.calendar', 'clinic.calendar'];
                @endphp
                <ul class="main-nav">
                    <li class="megamenu {{ in_array(Route::currentRouteName(), $dashboardRoutes) ? 'active' : '' }}">
                        <a href="{{ guard_route('dashboard.index') }}">Dashboard</a> 
                    </li>

                    <li class="megamenu {{ is_guard_route('planner') ? 'active' : '' }}">
                        <a href="{{ guard_route('planner.index') }}">Planner</a>
                    </li>

                    <li class="megamenu {{ in_array(Route::currentRouteName(), $calendarRoutes) ? 'active' : '' }}">
                        <a href="{{ guard_route('calendar') }}">Diary</a> 
                    </li>

                    <li class="megamenu {{ is_guard_route('patients') ? 'active' : '' }}">
                        <a href="{{ guard_route('patients.index') }}">Patients </a>
                    </li>

                </ul>
            </div>

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
                    <a href="#" id="notification-icon" class="dropdown-toggle nav-link p-0" data-bs-toggle="dropdown">
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

