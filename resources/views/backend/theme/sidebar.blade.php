<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        @guest
        @else
        <div class="sb-sidenav-menu">
            <div class="nav">

                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ guard_route('dashboard.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                    <span>Dashboard</span>
                </a>
                @usercan('patient-list')
                    <a class="nav-link {{ Request::is('patients*') ? 'active' : '' }}" href="{{ guard_route('patients.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-procedures"></i></div>
                        <span>Patients</span>
                    </a>
                @endusercan

                @usercan('appointment-list')
                    <a class="nav-link {{ Request::is('planner*') ? 'active' : '' }}" href="{{ guard_route('planner.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                        <span>Planner</span>
                    </a>
                @endusercan
                @usercan('appointment-list')
                    <a class="nav-link {{ Request::is('appointments*') ? 'active' : '' }}" href="{{ guard_route('appointments.schedule') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                        <span>Diary</span>
                    </a>
                @endusercan
                
                @php
                $isUtilitiesOpen = Request::is('users*') || Request::is('roles*') || Request::is('dropdowns*') || Request::is('clinics*') || Request::is('doctors*') || Request::is('consultants*') || Request::is('insurances*') || Request::is('chargecodes*') ;
                @endphp

                <a class="nav-link d-flex justify-content-between align-items-center {{ $isUtilitiesOpen ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUtilities" aria-expanded="{{ $isUtilitiesOpen ? 'true' : 'false' }}" aria-controls="collapseUtilities">
                    <div class="d-flex align-items-center">
                        <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                        <span>Utilities</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </a>

                <div id="collapseUtilities" class="collapse {{ $isUtilitiesOpen ? 'show' : '' }}">
                    <div class="collapse-inner py-2 rounded-2 border-start border-3 border-primary ps-3">
                        @usercan('user-list')
                        <a class="nav-link {{ Request::is('users*') ? 'active fw-bold text-primary' : '' }}" href="{{ guard_route('users.index') }}">
                            <i class="fas fa-user me-2"></i> Users
                        </a>
                        @endusercan

                        @usercan('dropdown-list')
                        <a class="nav-link {{ Request::is('dropdowns*') ? 'active fw-bold text-primary' : '' }}" href="{{ guard_route('dropdowns.index') }}">
                            <i class="fas fa-list-ul me-2"></i> Dropdowns
                        </a>
                        @endusercan

                        @usercan('doctor-list')
                        <a class="nav-link {{ Request::is('doctors*') ? 'active' : '' }}" href="{{ guard_route('doctors.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-md"></i></div>
                            <span>Doctors</span>
                        </a>
                        @endusercan

                        @usercan('consultant-list')
                        <a class="nav-link {{ Request::is('consultants*') ? 'active' : '' }}" href="{{ guard_route('consultants.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-stethoscope"></i></div>
                            <span>Consultants</span>
                        </a>
                        @endusercan

                        @usercan('insurance-list')
                        <a class="nav-link {{ Request::is('insurances*') ? 'active' : '' }}" href="{{ guard_route('insurances.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-medical"></i></div>
                            <span>Insurance</span>
                        </a>
                        @endusercan

                        @usercan('chargecode-list')
                        <a class="nav-link {{ Request::is('chargecodes*') ? 'active' : '' }}" href="{{ guard_route('chargecodes.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                            <span>Charge Codes</span>
                        </a>
                        @endusercan

                        @usercan('clinic-list')
                        <a class="nav-link {{ Request::is('clinics*') ? 'active' : '' }}" href="{{ guard_route('clinics.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-clinic-medical"></i></div>
                            <span>Clinic</span>
                        </a>
                        @endusercan

                        @usercan('company-list', true) 
                            @if (!is_company_user())
                                <a class="nav-link {{ Request::is('companies*') ? 'active' : '' }}" href="{{ guard_route('companies.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                                    <span>Company</span>
                                </a>
                            @endif
                        @endusercan

                        @usercan('role-list')
                        <a class="nav-link {{ Request::is('roles*') ? 'active' : '' }}" href="{{ guard_route('roles.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                            <span>Roles</span>
                        </a>
                        @endusercan

                    </div>
                </div>

            </div>
        </div>


        <div class="sb-sidenav-footer">
@php
    $user = getLoggedInUser();
@endphp


            <div class="small">Logged in as:</div>
            Welcome, {{ user_name() }}
            </div>
        @endguest
    </nav>
</div>
