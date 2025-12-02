<!-- Sidebar -->
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
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ guard_route('dashboard.index') }}"><i class="fe fe-home"></i> <span>Dashboard</span></a>
                </li>
                @if ($hasUtilitiesPermission)
                    <li class="submenu">
                        <a href="javascript:;">
                            <i class="fe fe-wrench"></i> 
                            <span>Utilities</span> 
                            <span class="menu-arrow"></span>
                        </a>
                        <ul style="display: {{ $isUtilitiesOpen ? 'block' : 'none' }};">
                            
                            {{-- Notifications based on guard/role --}}
                            @if ($currentGuard === 'doctor')
                                <li>
                                    <a class="{{ Request::is('notification*') ? 'active' : '' }}" 
                                    href="{{ guard_route('notification.form') }}">
                                    <i class="fe fe-bell"></i> Send Notification
                                    </a>
                                </li>
                            @elseif ($currentGuard === 'clinic')
                                <li>
                                    <a class="{{ Request::is('clinic.notification*') ? 'active' : '' }}" 
                                    href="{{ guard_route('clinic.notification.form') }}">
                                    <i class="fe fe-bell"></i> Send Notification
                                    </a>
                                </li>
                            @elseif (has_role('superadmin'))
                                <li>
                                    <a class="{{ Request::is('send-notification*') ? 'active' : '' }}" 
                                    href="{{ guard_route('notifications.form') }}">
                                    <i class="fe fe-bell"></i> Send Notification
                                    </a>
                                </li>
                            @elseif (has_role('manager'))
                                <li>
                                    <a class="{{ Request::is('send-notification*') ? 'active' : '' }}" 
                                    href="{{ guard_route('notifications.managerform') }}">
                                    <i class="fe fe-bell"></i> Send Notification
                                    </a>
                                </li>
                            @elseif ($currentGuard === 'patient')
                                <li>
                                    <a class="{{ Request::is('send-notification*') ? 'active' : '' }}" 
                                    href="{{ guard_route('patient.notification.form') }}">
                                    <i class="fe fe-bell"></i> Send Notification
                                    </a>
                                </li>
                            @endif

                            {{-- Utility items --}}
                            @php
                                $utilities = [
                                    ['icon' => 'fe fe-user-plus', 'label' => 'Doctors', 'route' => 'doctors.index', 'permission' => 'doctor-list', 'pattern' => 'doctors*'],
                                    ['icon' => 'fe fe-users', 'label' => 'Consultants', 'route' => 'consultants.index', 'permission' => 'consultant-list', 'pattern' => 'consultants*'],
                                    ['icon' => 'fe fe-building', 'label' => 'Company', 'route' => 'companies.index', 'permission' => 'company-list', 'pattern' => 'companies*'],
                                    ['icon' => 'fe fe-file', 'label' => 'Insurance', 'route' => 'insurances.index', 'permission' => 'insurance-list', 'pattern' => 'insurances*'],
                                    ['icon' => 'fe fe-activity', 'label' => 'Charge Codes', 'route' => 'chargecodes.index', 'permission' => 'chargecode-list', 'pattern' => 'chargecodes*'],
                                    ['icon' => 'fe fe-home', 'label' => 'Clinic', 'route' => 'clinics.index', 'permission' => 'clinic-list', 'pattern' => 'clinics*'],
                                    ['icon' => 'fe fe-file', 'label' => 'Document', 'route' => 'documents.index', 'permission' => 'document-list', 'pattern' => 'documents*'],
                                ];
                            @endphp

                            @foreach ($utilities as $item)
                                @can($item['permission'])
                                    <li>
                                        <a class="{{ Request::is($item['pattern']) ? 'active' : '' }}" 
                                        href="{{ guard_route($item['route']) }}">
                                        <i class="{{ $item['icon'] }}"></i> {{ $item['label'] }}
                                        </a>
                                    </li>
                                @endcan
                            @endforeach

                        </ul>
                    </li>
                @endif
           
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
                                    <a class="{{ Request::is('users*') ? 'active' : '' }}" href="{{ guard_route('users.index') }}">
                                        <i class="fe fe-user"></i> Users
                                    </a>
                                </li>
                            @endif    
                            @if(has_role('superadmin'))    
                                <li>
                                    <a class="{{ Request::is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                                        <i class="fe fe-users"></i> Roles
                                    </a>
                                </li>
                                <li>
                                    <a class="{{ Request::is('dropdowns*') ? 'active' : '' }}" href="{{ route('dropdowns.index') }}">
                                        <i class="fe fe-list-bullet"></i> Dropdowns
                                    </a>
                                </li>
                            @endif
                    
                            @if(has_permission('configuration-list'))
                                <li>
                                    <a class="{{ Request::is('configurations*') ? 'active' : '' }}" href="{{ route('configurations.index') }}">
                                        <i class="fe fe-gear"></i> Configuration
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (has_permission('patient-list'))
                    <li class="{{ is_guard_route('patients') ? 'active' : '' }}">
                        <a href="{{ guard_route('patients.index') }}"><i class="fe fe-user"></i> <span>Patients</span></a>
                    </li>
                @endif

                <li class="{{ is_guard_route('planner') ? 'active' : '' }}">
                    <a href="{{ guard_route('planner.index') }}"><i class="fe fe-calendar"></i> <span>Planner</span></a>
                </li>

                <li class="{{ is_guard_route('appointments') ? 'active' : '' }}">
                    <a href="{{ guard_route('appointments.schedule') }}"><i class="fe fe-clock"></i> <span>Diary</span></a>
                </li>
               
               
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
