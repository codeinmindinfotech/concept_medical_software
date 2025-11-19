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
                                    href="{{ route('notification.form') }}">
                                    <i class="fe fe-bell"></i> Send Notification
                                    </a>
                                </li>
                            @elseif ($currentGuard === 'clinic')
                                <li>
                                    <a class="{{ Request::is('clinic.notification*') ? 'active' : '' }}" 
                                    href="{{ route('clinic.notification.form') }}">
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
                                        href="{{ route($item['route']) }}">
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
                            @if(has_role('superadmin'))
                                <li>
                                    <a class="{{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                        <i class="fe fe-user"></i> Users
                                    </a>
                                </li>
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
               
               

{{--                 
                <li class="{{ Request::is('admin/appointment-list') ? 'active' : '' }}">
                    <a href="{{ url('admin/appointment-list') }}"><i class="fe fe-layout"></i>
                        <span>Appointments</span></a>
                </li>
                <li class="{{ Request::is('admin/specialities') ? 'active' : '' }}">
                    <a href="{{ url('admin/specialities') }}"><i class="fe fe-users"></i> <span>Specialities</span></a>
                </li>
                <li class="{{ Request::is('admin/doctor-list') ? 'active' : '' }}">
                    <a href="{{ url('admin/doctor-list') }}"><i class="fe fe-user-plus"></i> <span>Doctors</span></a>
                </li>
                <li class="{{ Request::is('admin/patient-list') ? 'active' : '' }}">
                    <a href="{{ url('admin/patient-list') }}"><i class="fe fe-user"></i> <span>Patients</span></a>
                </li>
                <li class="{{ Request::is('admin/reviews') ? 'active' : '' }}">
                    <a href="{{ url('admin/reviews') }}"><i class="fe fe-star-o"></i> <span>Reviews</span></a>
                </li>
                <li class="{{ Request::is('admin/transactions-list') ? 'active' : '' }}">
                    <a href="{{ url('admin/transactions-list') }}"><i class="fe fe-activity"></i>
                        <span>Transactions</span></a>
                </li>
                <li class="{{ Request::is('admin/settings') ? 'active' : '' }}">
                    <a href="{{ url('admin/settings') }}"><i class="fe fe-vector"></i> <span>Settings</span></a>
                </li>
                <li class="submenu">
                    <a href="javascript:;"><i class="fe fe-document"></i> <span> Reports</span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="{{ Request::is('admin/invoice-report','admin/invoice') ? 'active' : '' }}"
                                href="{{ url('admin/invoice-report') }}">Invoice Reports</a></li>
                    </ul>
                </li>
                <li class="menu-title">
                    <span>Pages</span>
                </li>
                <li class="{{ Request::is('admin/profile') ? 'active' : '' }}">
                    <a href="{{ url('admin/profile') }}"><i class="fe fe-user-plus"></i> <span>Profile</span></a>
                </li>
                <li class="submenu">
                    <a href="javascript:;"><i class="fe fe-document"></i> <span> Authentication </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="{{ Request::is('admin/login') ? 'active' : '' }}"
                                href="{{ url('admin/login') }}"> Login </a></li>
                        <li><a class="{{ Request::is('admin/register') ? 'active' : '' }}"
                                href="{{ url('admin/register') }}"> Register </a></li>
                        <li><a class="{{ Request::is('admin/forgot-password') ? 'active' : '' }}"
                                href="{{ url('admin/forgot-password') }}"> Forgot Password </a></li>
                        <li><a class="{{ Request::is('admin/lock-screen') ? 'active' : '' }}"
                                href="{{ url('admin/lock-screen') }}"> Lock Screen </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:;"><i class="fe fe-warning"></i> <span> Error Pages </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="{{ Request::is('admin/error-404') ? 'active' : '' }}"
                                href="{{ url('admin/error-404') }}">404 Error </a></li>
                        <li><a class="{{ Request::is('admin/error-500') ? 'active' : '' }}"
                                href="{{ url('admin/error-500') }}">500 Error </a></li>
                    </ul>
                </li>
                <li>
                    <a class="{{ Request::is('admin/blank-page') ? 'active' : '' }}" href="blank-page"><i
                            class="fe fe-file"></i> <span>Blank Page</span></a>
                </li>
                <li class="menu-title">
                    <span>UI Interface</span>
                </li>
                <li class="{{ Request::is('admin/components') ? 'active' : '' }}">
                    <a class="{{ Request::is('admin/components') ? 'active' : '' }}" href="components"><i
                            class="fe fe-vector"></i> <span>Components</span></a>
                </li>
                <li class="submenu">
                    <a href="javascript:;"><i class="fe fe-layout"></i> <span> Forms </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="{{ Request::is('admin/form-basic-inputs') ? 'active' : '' }}"
                                href="{{ url('admin/form-basic-inputs') }}">Basic Inputs </a></li>
                        <li><a class="{{ Request::is('admin/form-input-groups') ? 'active' : '' }}"
                                href="{{ url('admin/form-input-groups') }}">Input Groups </a></li>
                        <li><a class="{{ Request::is('admin/form-horizontal') ? 'active' : '' }}"
                                href="{{ url('admin/form-horizontal') }}">Horizontal Form</a></li>
                        <li><a class="{{ Request::is('admin/form-vertical') ? 'active' : '' }}"
                                href="{{ url('admin/form-vertical') }}"> Vertical Form </a></li>
                        <li><a class="{{ Request::is('admin/form-mask') ? 'active' : '' }}"
                                href="{{ url('admin/form-mask') }}"> Form Mask </a></li>
                        <li><a class="{{ Request::is('admin/form-validation') ? 'active' : '' }}"
                                href="{{ url('admin/form-validation') }}"> Form Validation </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:;"><i class="fe fe-table"></i> <span> Tables </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a class="{{ Request::is('admin/tables-basic') ? 'active' : '' }}"
                                href="{{ url('admin/tables-basic') }}">Basic Tables </a></li>
                        <li><a class="{{ Request::is('admin/data-tables') ? 'active' : '' }}"
                                href="{{ url('admin/data-tables') }}">Data Table </a></li>
                    </ul>
                </li>
                <li class="submenu"> 
                    <a href="javascript:void(0);"><i class="fe fe-code"></i> <span>Utilities</span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="submenu">
                            <a href="javascript:void(0);"> <span>Level 1</span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="javascript:void(0);"><span>Level 2</span></a></li>
                                <li class="submenu">
                                    <a href="javascript:void(0);"> <span> Level 2</span> <span
                                            class="menu-arrow"></span></a>
                                    <ul style="display: none;">
                                        <li><a href="javascript:void(0);">Level 3</a></li>
                                        <li><a href="javascript:void(0);">Level 3</a></li>
                                    </ul>
                                </li>
                                <li><a href="javascript:void(0);"> <span>Level 2</span></a></li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('users') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}"><i class="fe fe-users"></i> <span>Users</span></a>
                        </li>
                        <li class="{{ Request::is('roles') ? 'active' : '' }}">
                            <a href="{{ route('roles.index') }}"><i class="fe fe-user"></i> <span>Roles</span></a>
                        </li>
                        <li class="{{ Request::is('dropdowns') ? 'active' : '' }}">
                            <a href="{{ route('dropdowns.index') }}"><i class="fe fe-list"></i> <span>Dropdowns</span></a>
                        </li>
                        <li class="{{ Request::is('configurations') ? 'active' : '' }}">
                            <a href="{{ route('configurations.index') }}"><i class="fe fe-cogs"></i> <span>cogs</span></a>
                        </li>
                    </ul>
                </li>--}}
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
