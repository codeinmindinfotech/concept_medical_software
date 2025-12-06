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
                $currentGuard = getCurrentGuard();
                $dashboardRoutes = ['patient.dashboard', 'doctor.dashboard', 'clinic.dashboard'];
                $calendarRoutes = ['patient.calendar', 'doctor.calendar', 'clinic.calendar'];
                @endphp
                <ul class="main-nav">
                    @if(has_role('patient'))
                    <li class="megamenu {{ in_array(Route::currentRouteName(), $dashboardRoutes) ? 'active' : '' }}">
                        <a href="{{ guard_route('dashboard.index') }}">Dashboard</a>
                    </li>
                    @endif
                    <li class="megamenu {{ in_array(Route::currentRouteName(), $calendarRoutes) ? 'active' : '' }}">
                        <a href="{{ guard_route('calendar') }}">Planner</a>
                    </li>
                    <li class="megamenu {{ is_guard_route('appointments.new_schedule') ? 'active' : '' }}">
                        <a href="{{ guard_route('patients.appointments.new_schedule') }}">Diary</a>
                    </li>

                    <li class="megamenu {{ is_guard_route('patients') ? 'active' : '' }}">
                        <a href="{{ guard_route('patients.index') }}">Patients </a>
                    </li>
                    {{-- Notifications based on guard/role --}}
                    @if ($currentGuard === 'doctor')
                    <li class="megamenu {{ is_guard_route('notification') ? 'active' : '' }}">
                        <a href="{{ guard_route('doctor.notification.form') }}">Send Notification </a>
                    </li>
                    @elseif ($currentGuard === 'clinic')
                    <li class="megamenu {{ is_guard_route('clinic.notification*') ? 'active' : '' }}">
                        <a href="{{ guard_route('clinic.notification.form') }}">Send Notification </a>
                    </li>
                    @elseif ($currentGuard === 'patient')
                    <li class="megamenu {{ Request::is('send-notification*') ? 'active' : '' }}">
                        <a href="{{ guard_route('patient.notification.form') }}">Send Notification </a>
                    </li>
                    @endif
                </ul>
            </div>

            {{-- @php
            $currentRoute = Route::currentRouteName();
            dd($currentRoute);
            @endphp --}}



            <ul class="nav header-navbar-rht">
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
                            <ul class="notification-list">
                            <li>
                                <a class="dropdown-item text-center bg-primary-light fw-bold"
                                    href="{{guard_route('notifications.index') }}">
                                    <i class="isax isax-notification-bing"></i> View All Notification
                                </a>
                            </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <!-- /Notifications -->

                <li class="nav-item dropdown me-3 pe-0">
                    <a href="javascript:;"
                    class="dropdown-toggle nav-link active-dot active-dot-purple p-0 position-relative"
                    id="recallsDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                 
                     <i class="isax isax-calendar"></i>
                 
                     @if(!empty($monthlyRecallCount) && $monthlyRecallCount > 0)
                         <span class="badge rounded-pill bg-primary text-white
                                      position-absolute top-0 start-100 translate-middle"
                               id="recall-count">
                             {{ $monthlyRecallCount }}
                         </span>
                     @endif
                 </a>
                 
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="recallsDropdown">
                        <li>
                            <h6 class="dropdown-header">
                                <i class="fas fa-calendar-alt me-2"></i> Recalls This Month
                            </h6>
                        </li>

                        @forelse($recallsThisMonth as $recall)
                        <li>
                            <a class="dropdown-item" href="{{guard_route('recalls.notifications') }}">
                                <i class="fas fa-user me-2"></i>
                                Patient #{{ $recall->patient_id }} – {{
                                \Carbon\Carbon::parse($recall->recall_date)->format('d M Y') }}
                            </a>
                        </li>
                        @empty
                        <li><span class="dropdown-item-text">No recalls due</span></li>
                        @endforelse

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item text-center text-primary fw-bold"
                                href="{{guard_route('recalls.notifications') }}">
                                <i class="fas fa-folder-open me-2"></i> View All Recalls
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Tasks Notification Dropdown -->
                <li class="nav-item dropdown me-3 pe-0">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="tasksDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">

                        <i class="fas fa-tasks"></i>
                        @if(!empty($taskCount) && $taskCount > 0)

                        <span
                            class="badge rounded-pill bg-warning text-dark position-absolute translate-middle"
                            id="recall-count">
                            {{ $taskCount }}
                        </span>
                        @endif

                    </a>


                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="tasksDropdown">
                        <li>
                            <h6 class="dropdown-header">
                                <i class="fas fa-clipboard-list me-2"></i> Upcoming Tasks
                            </h6>
                        </li>

                        @forelse($upcomingTasks as $task)
                        <li>
                            <a class="dropdown-item"
                                href="{{guard_route('tasks.edit', ['patient' => $task->patient_id, 'task' => $task->id]) }}">
                                <i class="fas fa-file-alt me-2"></i>
                                {{ $task->subject }} – {{ \Carbon\Carbon::parse($task->end_date)->format('d M Y') }}
                            </a>
                        </li>
                        @empty
                        <li><span class="dropdown-item-text">No upcoming tasks</span></li>
                        @endforelse

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item text-center text-primary fw-bold"
                                href="{{guard_route('tasks.notifications') }}">
                                <i class="fas fa-folder-open me-2"></i> View All Tasks
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- User Menu -->
                <li class="nav-item dropdown has-arrow logged-item">
                    <a href="#" class="nav-link ps-0" data-bs-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle"
                                src="{{ setProfileImage() }}" width="31"
                                alt="Darren Elder">
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="{{ setProfileImage() }}"
                                    alt="User Image" class="avatar-img rounded-circle">
                            </div>
                            <div class="user-text">
                                <h6>{{ Auth::user()->name ?? Auth::user()->full_name }}</h6>
                                <p class="text-muted mb-0">{{$currentGuard}}</p>
                            </div>
                        </div>
                        <a class="dropdown-item" href="{{guard_route('patient.dashboard.index')}}">Dashboard</a>
                        <a class="dropdown-item" href="{{ guard_route($currentGuard . 's.edit',Auth::user()->id) }}">Change profile</a>
                        <a class="dropdown-item" href="{{ guard_route('password.change') }}">Change Password</a>
                        <a class="dropdown-item" href="{{guard_route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{guard_route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
                <!-- /User Menu -->
            </ul>



        </nav>
    </div>
</header>
<!-- /Header -->