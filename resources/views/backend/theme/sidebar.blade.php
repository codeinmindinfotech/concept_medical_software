<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        @guest
        @else
        <div class="sb-sidenav-menu">
            <div class="nav">
               
                {{-- Dashboard --}}
                <a class="nav-link {{ is_guard_route('dashboard') ? 'active' : '' }}" href="{{ guard_route('dashboard.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                    <span>Dashboard</span>
                </a>
                @if (has_permission('patient-list'))
                    <a class="nav-link {{ is_guard_route('patients') ? 'active' : '' }}" href="{{ guard_route('patients.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-procedures"></i></div>
                        <span>Patients</span>
                    </a>
                @endif
                
                <a class="nav-link {{ is_guard_route('planner*') ? 'active' : '' }}" href="{{ guard_route('planner.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                    <span>Planner</span>
                </a>
            
                <a class="nav-link {{ is_guard_route('appointments*') ? 'active' : '' }}" href="{{ guard_route('appointments.schedule') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                    <span>Diary</span> 
                </a>

                {{-- Utilities Collapsible Section --}}   
                @php
                    $isUtilitiesOpen = is_guard_route('/send-notification*')  || is_guard_route('/configurations*')  || is_guard_route('/users*') || is_guard_route('/roles*') || is_guard_route('/dropdowns*') || is_guard_route('/clinics*') || is_guard_route('/doctors*') || is_guard_route('consultants*') || is_guard_route('insurances*')  || is_guard_route('chargecodes*') ;
               // Check if user has at least one permission for utilities
                    $hasUtilityPermissions = 
                        has_permission('user-list') ||
                        has_permission('role-list') ||
                        has_permission('dropdown-list') ||
                        has_permission('clinic-list') ||
                        has_permission('doctor-list') ||
                        has_permission('consultant-list') ||
                        has_permission('insurance-list') ||
                        has_permission('configuration-list') ||
                        has_permission('chargecode-list');

                    // Only show the section if either condition is true
                    $showUtilities = $isUtilitiesOpen || $hasUtilityPermissions;
               @endphp
        @if ($showUtilities)
                <a class="nav-link d-flex justify-content-between align-items-center {{ $isUtilitiesOpen ? '' : 'collapsed' }}"
                   href="#" data-bs-toggle="collapse" data-bs-target="#collapseUtilities"
                   aria-expanded="{{ $isUtilitiesOpen ? 'true' : 'false' }}" aria-controls="collapseUtilities">
                    <div class="d-flex align-items-center">
                        <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                        <span>Utilities</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </a>
        
                <div id="collapseUtilities" class="collapse {{ $isUtilitiesOpen ? 'show' : '' }}">
                    <div class="collapse-inner py-2 rounded-2 border-start border-3 border-primary ps-3">
                        @if (has_role('superadmin'))
                            <a class="nav-link {{ is_guard_route('users*') ? 'active fw-bold text-primary' : '' }}"
                            href="{{ guard_route('users.index') }}">
                                <i class="fas fa-user me-2"></i> Users
                            </a>
                        
                        <a class="nav-link {{ is_guard_route('roles*') ? 'active fw-bold text-primary' : '' }}"
                           href="{{ guard_route('roles.index') }}">
                            <i class="fas fa-user-shield me-2"></i> Roles
                        </a>
        
                        <a class="nav-link {{ is_guard_route('dropdowns*') ? 'active fw-bold text-primary' : '' }}"
                           href="{{ guard_route('dropdowns.index') }}">
                            <i class="fas fa-list-ul me-2"></i> Dropdowns
                        </a>
                        @endif
                        @if (has_role('superadmin') || (has_role('manager')))
                            <a class="nav-link {{ is_guard_route('send-notification*') ? 'active fw-bold text-primary' : '' }}"
                                href="{{ guard_route('notifications.form') }}">
                                <i class="fas fa-bell me-2"></i> Send Notification
                            </a>
                        @endif
                        @if(getCurrentGuard() == 'doctor')
                            <a class="nav-link {{ is_guard_route('notification*') ? 'active fw-bold text-primary' : '' }}"
                                    href="{{ guard_route('notification.form') }}">
                                <i class="fas fa-bell me-2"></i> Send Notification
                            </a>
                        @endif
                        @if (has_permission('configuration-list'))
                            <a class="nav-link {{ is_guard_route('configurations*') ? 'active' : '' }}" href="{{ guard_route('configurations.index') }}">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-cogs"></i> 
                                </div>
                                <span>Configuration</span>
                            </a>
                        @endif


                        @if (has_permission('doctor-list'))
                            <a class="nav-link {{ is_guard_route('doctors*') ? 'active' : '' }}" href="{{ guard_route('doctors.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-md"></i></div>
                                <span>Doctors</span>
                            </a>
                        @endif
                
                        @if (has_permission('consultant-list'))
                        <a class="nav-link {{ is_guard_route('consultants*') ? 'active' : '' }}" href="{{ guard_route('consultants.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-stethoscope"></i></div>
                            <span>Consultants</span>
                        </a>
                        @endif

                        @if (has_permission('company-list'))
                        <a class="nav-link {{ is_guard_route('companies*') ? 'active' : '' }}" href="{{ guard_route('companies.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                            <span>Company</span>
                        </a>
                        @endif

                        @if (has_permission('insurance-list'))
                        <a class="nav-link {{ is_guard_route('insurances*') ? 'active' : '' }}" href="{{ guard_route('insurances.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-medical"></i></div>
                            <span>Insurance</span>
                        </a>
                        @endif

                        @if (has_permission('chargecode-list'))
                        <a class="nav-link {{ is_guard_route('chargecodes*') ? 'active' : '' }}" href="{{ guard_route('chargecodes.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                            <span>Charge Codes</span>
                        </a>
                        @endif

                        @if (has_permission('clinic-list'))
                        <a class="nav-link {{ is_guard_route('clinics*') ? 'active' : '' }}" href="{{ guard_route('clinics.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-clinic-medical"></i></div>
                            <span>Clinic</span>
                        </a>
                        @endif
                        
        
                    </div>
                </div>
        @endif
            </div>
        </div>
        
        
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
                {{ Auth::user()->name ?? Auth::user()->full_name }}
        </div>
        @endguest
    </nav>
</div>