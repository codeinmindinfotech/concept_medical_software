<!-- Sidebar -->
@php
    $currentGuard = getCurrentGuard();

    // SETTINGS
    $settingsRoutes = ['users', 'roles', 'dropdowns', 'configurations'];
    $isSettingsOpen = collect($settingsRoutes)->contains(fn($route) => is_guard_route($route));
    $settingsPermissions = ['role-list', 'dropdown-list', 'configuration-list'];
    $hasSettingsPermission = collect($settingsPermissions)->contains(fn($perm) => has_permission($perm)) || has_role('superadmin');

    // UTILITIES
    $utilities = [
        ['icon' => 'fe fe-user-plus', 'label' => 'Doctors', 'route' => 'doctors.index', 'permission' => 'doctor-list', 'pattern' => 'doctors*'],
        ['icon' => 'fe fe-users', 'label' => 'Consultants', 'route' => 'consultants.index', 'permission' => 'consultant-list', 'pattern' => 'consultants*'],
        ['icon' => 'fe fe-building', 'label' => 'Company', 'route' => 'companies.index', 'permission' => 'company-list', 'pattern' => 'companies*'],
        ['icon' => 'fe fe-file', 'label' => 'Insurance', 'route' => 'insurances.index', 'permission' => 'insurance-list', 'pattern' => 'insurances*'],
        ['icon' => 'fe fe-activity', 'label' => 'Charge Codes', 'route' => 'chargecodes.index', 'permission' => 'chargecode-list', 'pattern' => 'chargecodes*'],
        ['icon' => 'fe fe-home', 'label' => 'Clinic', 'route' => 'clinics.index', 'permission' => 'clinic-list', 'pattern' => 'clinics*'],
        ['icon' => 'fe fe-file', 'label' => 'Document', 'route' => 'documents.index', 'permission' => 'document-list', 'pattern' => 'documents*'],
        ['icon' => 'fe fe-bell', 'label' => 'Notifications', 'route' => match($currentGuard) {
            'doctor' => 'notification.form',
            'clinic' => 'clinic.notification.form',
            'patient' => 'patient.notification.form',
            default => $currentGuard === 'manager' ? 'notifications.managerform' : 'notifications.form'
        }, 'permission' => 'notification-list', 'pattern' => '*notification*'],
    ];

    $hasUtilitiesPermission = collect($utilities)->contains(function($item) use ($currentGuard) {
        return has_permission($item['permission'])
            || has_role('superadmin')
            || ($currentGuard === 'manager' && has_role('manager'));
    });

    $isUtilitiesOpen = collect($utilities)->contains(function($item) {
        return request()->routeIs($item['pattern']);
    });
@endphp

<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>

                <li class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                    <a href="{{ guard_route('dashboard.index') }}">
                        <i class="fe fe-home"></i> <span>Dashboard</span>
                    </a>
                </li>

                {{-- Utilities Menu --}}
                @if($hasUtilitiesPermission)
                    <li class="submenu">
                        <a href="javascript:;">
                            <i class="fe fe-wrench"></i> 
                            <span>Utilities</span> 
                            <span class="menu-arrow"></span>
                        </a>
                        <ul style="display: {{ $isUtilitiesOpen ? 'block' : 'none' }};">
                            @foreach($utilities as $item)
                                @if(has_permission($item['permission']) || has_role('superadmin') || ($currentGuard === 'manager' && has_role('manager')))
                                    <li>
                                        <a class="{{ request()->routeIs($item['pattern']) ? 'active' : '' }}" 
                                           href="{{ guard_route($item['route']) }}">
                                            <i class="{{ $item['icon'] }}"></i> {{ $item['label'] }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif

                {{-- Settings Menu --}}
                @if($hasSettingsPermission)
                    <li class="submenu">
                        <a href="javascript:;">
                            <i class="fe fe-gear"></i>
                            <span>Settings</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul style="display: {{ $isSettingsOpen ? 'block' : 'none' }};">
                            @if(has_role('superadmin') || has_role('manager'))
                                <li>
                                    <a class="{{ request()->routeIs('users*') ? 'active' : '' }}" href="{{ guard_route('users.index') }}">
                                        <i class="fe fe-user"></i> Users
                                    </a>
                                </li>
                            
                                <li>
                                    <a class="{{ request()->routeIs('roles*') ? 'active' : '' }}" href="{{ guard_route('roles.index') }}">
                                        <i class="fe fe-users"></i> Roles
                                    </a>
                                </li>
                            @endif
                            @if(has_role('superadmin'))                                
                                <li>
                                    <a class="{{ request()->routeIs('dropdowns*') ? 'active' : '' }}" href="{{ route('dropdowns.index') }}">
                                        <i class="fe fe-list-bullet"></i> Dropdowns
                                    </a>
                                </li>
                            @endif
                            @if(has_permission('configuration-list'))
                                <li>
                                    <a class="{{ request()->routeIs('configurations*') ? 'active' : '' }}" href="{{ route('configurations.index') }}">
                                        <i class="fe fe-gear"></i> Configuration
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- Patients --}}
                @if(has_permission('patient-list'))
                    <li class="{{ is_guard_route('patients*') ? 'active' : '' }}">
                        <a href="{{ guard_route('patients.index') }}"><i class="fe fe-user"></i> <span>Patients</span></a>
                    </li>
                @endif

                {{-- Planner --}}
                <li class="{{ is_guard_route('planner') ? 'active' : '' }}">
                    <a href="{{ guard_route('planner.index') }}"><i class="fe fe-calendar"></i> <span>Planner</span></a>
                </li>

                {{-- Diary / Appointments --}}
                <li class="{{ is_guard_route('appointments') ? 'active' : '' }}">
                    <a href="{{ guard_route('appointments.schedule') }}"><i class="fe fe-clock"></i> <span>Diary</span></a>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
