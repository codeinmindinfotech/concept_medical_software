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
                
                @php
                    // Routes & permissions for SETTINGS
                    $settingsRoutes = ['/users*', '/roles*', '/dropdowns*', '/configurations*'];
                    $settingsPermissions = ['role-list', 'dropdown-list', 'configuration-list'];
                    $hasSettingsPermission = collect($settingsPermissions)->contains(fn($perm) => has_permission($perm)) || has_role('superadmin');
                    $isSettingsOpen = collect($settingsRoutes)->contains(fn($route) => is_guard_route($route));

                    // Routes & permissions for UTILITIES
                    $utilityRoutes = ['/send-notification*','/send-patient-notification*','/send-clinic-notification*', '/doctors*', '/consultants*', '/companies*', '/insurances*', '/chargecodes*', '/clinics*'];
                    $utilityPermissions = ['doctor-list', 'consultant-list', 'company-list', 'insurance-list', 'chargecode-list', 'clinic-list','notification-list'];
                    $hasUtilitiesPermission = collect($utilityPermissions)->contains(fn($perm) => has_permission($perm)) || has_role('superadmin') || has_role('manager');
                    $isUtilitiesOpen = collect($utilityRoutes)->contains(fn($route) => is_guard_route($route));
                    $currentGuard = getCurrentGuard();
                @endphp

                @if ($hasSettingsPermission)
                    <a class="nav-link d-flex justify-content-between align-items-center {{ $isSettingsOpen ? '' : 'collapsed' }}"
                    href="#" data-bs-toggle="collapse" data-bs-target="#collapseSettings"
                    aria-expanded="{{ $isSettingsOpen ? 'true' : 'false' }}" aria-controls="collapseSettings">
                        <div class="d-flex align-items-center">
                            <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                            <span>Settings</span>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </a>

                    <div id="collapseSettings" class="collapse {{ $isSettingsOpen ? 'show' : '' }}">
                        <div class="collapse-inner py-2 border-start border-3 border-primary ps-3">
                            <x-nav.item icon="fas fa-user" label="Users" route="users.index" role="superadmin" pattern="users*" />
                            <x-nav.item icon="fas fa-user-shield" label="Roles" route="roles.index" role="superadmin" pattern="roles*" />
                            <x-nav.item icon="fas fa-list-ul" label="Dropdowns" route="dropdowns.index" role="superadmin" pattern="dropdowns*" />
                            <x-nav.item icon="fas fa-cogs" label="Configuration" route="configurations.index" permission="configuration-list" pattern="configurations*" />
                        </div>
                    </div>
                @endif


                @if ($hasUtilitiesPermission)
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
                        <div class="collapse-inner py-2 border-start border-3 border-primary ps-3">

                            {{-- Notifications (based on guard/role) --}}
                            @if ($currentGuard === 'doctor')
                                <x-nav.item icon="fas fa-bell" label="Send Notification" route="notification.form" pattern="notification*" />
                            @elseif ($currentGuard === 'clinic')
                                <x-nav.item icon="fas fa-bell" label="Send Notification" route="clinic.notification.form" pattern="clinic.notification*" />
                            @elseif (has_role('superadmin'))
                                <x-nav.item icon="fas fa-bell" label="Send Notification" route="notifications.form" pattern="send-notification*" />
                            @elseif (has_role('manager'))
                                <x-nav.item icon="fas fa-bell" label="Send Notification" route="notifications.managerform" pattern="send-notification*" />
                            @elseif ($currentGuard === 'patient')
                                <x-nav.item icon="fas fa-bell" label="Send Notification" route="patient.notification.form" pattern="send-notification*" />
                            @endif
                            

                            {{-- Utility items --}}
                            <x-nav.item icon="fas fa-user-md" label="Doctors" route="doctors.index" permission="doctor-list" pattern="doctors*" />
                            <x-nav.item icon="fas fa-stethoscope" label="Consultants" route="consultants.index" permission="consultant-list" pattern="consultants*" />
                            <x-nav.item icon="fas fa-building" label="Company" route="companies.index" permission="company-list" pattern="companies*" />
                            <x-nav.item icon="fas fa-file-medical" label="Insurance" route="insurances.index" permission="insurance-list" pattern="insurances*" />
                            <x-nav.item icon="fas fa-file-invoice-dollar" label="Charge Codes" route="chargecodes.index" permission="chargecode-list" pattern="chargecodes*" />
                            <x-nav.item icon="fas fa-clinic-medical" label="Clinic" route="clinics.index" permission="clinic-list" pattern="clinics*" />
                        </div>
                    </div>
                @endif


                {{-- @php
                    $utilityRoutes = [
                        '/send-notification*', '/configurations*', '/users*', '/roles*',
                        '/dropdowns*', '/clinics*', '/doctors*', '/consultants*', '/insurances*', '/chargecodes*'
                    ];

                    $utilityPermissions = [
                        'role-list', 'dropdown-list', 'clinic-list', 'doctor-list',
                        'consultant-list', 'insurance-list', 'configuration-list', 'chargecode-list'
                    ];

                    $isUtilitiesOpen = collect($utilityRoutes)->contains(fn($route) => is_guard_route($route));
                    $hasUtilityPermissions = collect($utilityPermissions)->contains(fn($perm) => has_permission($perm)) 
                                            || has_role('superadmin') || has_role('manager');

                    $showUtilities = $hasUtilityPermissions;
                    $currentGuard = getCurrentGuard();
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

            <x-nav.item icon="fas fa-user" label="Users" route="users.index" role="superadmin" pattern="users*" />
            <x-nav.item icon="fas fa-user-shield" label="Roles" route="roles.index" role="superadmin" pattern="roles*" />
            <x-nav.item icon="fas fa-list-ul" label="Dropdowns" route="dropdowns.index" role="superadmin" pattern="dropdowns*" />
            <x-nav.item icon="fas fa-file-lines" label="Documents" route="documents.index" role="superadmin" pattern="documents*" />

            @if ($currentGuard === 'doctor')
                <x-nav.item icon="fas fa-bell" label="Send Notification" route="notification.form" pattern="notification*" />
            @elseif ($currentGuard === 'clinic')
                <x-nav.item icon="fas fa-bell" label="Send Notification" route="clinic.notification.form" pattern="clinic.notification*" />
            @elseif (has_role('superadmin') || has_role('manager'))
                <x-nav.item icon="fas fa-bell" label="Send Notification" route="notifications.form" pattern="send-notification*" />
            @endif

            <x-nav.item icon="fas fa-cogs" label="Configuration" route="configurations.index" permission="configuration-list" pattern="configurations*" />
            <x-nav.item icon="fas fa-user-md" label="Doctors" route="doctors.index" permission="doctor-list" pattern="doctors*" />
            <x-nav.item icon="fas fa-stethoscope" label="Consultants" route="consultants.index" permission="consultant-list" pattern="consultants*" />
            <x-nav.item icon="fas fa-building" label="Company" route="companies.index" permission="company-list" pattern="companies*" />
            <x-nav.item icon="fas fa-file-medical" label="Insurance" route="insurances.index" permission="insurance-list" pattern="insurances*" />
            <x-nav.item icon="fas fa-file-invoice-dollar" label="Charge Codes" route="chargecodes.index" permission="chargecode-list" pattern="chargecodes*" />
            <x-nav.item icon="fas fa-clinic-medical" label="Clinic" route="clinics.index" permission="clinic-list" pattern="clinics*" />

        </div>
    </div>
@endif --}}
          

            </div>
        </div>
        
        
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
                {{ Auth::user()->name ?? Auth::user()->full_name }}
        </div>
        @endguest
    </nav>
</div>