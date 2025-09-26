<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
@php
    $user = current_user(); // or Auth::user(), depending on your setup
    $companyName = 'Concept Medical';

    if ($user && $user->company_id && $user->company) {
        $companyName = $user->company->name;
    }
@endphp
    <a class="navbar-brand ps-3" href="/">
        <img src="{{ asset('theme/assets/img/logor.png') }}" alt="{{ucfirst($companyName)}}" width="150">
    </a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            {{-- <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..."
                aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            --}}
        </div>
    </form>

    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span id="notification-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                    0
                </span>
            </a>
        
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-0" aria-labelledby="notificationDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                <li class="px-3 py-2 border-bottom fw-bold bg-light">Notifications</li>
        
                <ul id="notification-list" class="list-unstyled mb-0">
                    <li class="text-muted text-center py-3">No notifications</li>
                </ul>
        
                <hr class="dropdown-divider my-0">
        
                <li>
                    <a class="dropdown-item text-center text-primary fw-bold" href="{{ guard_route('notifications.index') }}">
                        <i class="fas fa-bell me-2"></i> View All
                    </a>
                </li>
            </ul>
        </li>
        

        <li class="nav-item dropdown me-3">
            <a class="nav-link dropdown-toggle position-relative" href="#" id="recallsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-calendar-check fa-fw"></i>
                @if(!empty($monthlyRecallCount) && $monthlyRecallCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $monthlyRecallCount }}
                    <span class="visually-hidden">recalls this month</span>
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
                        Patient #{{ $recall->patient_id }} – {{ \Carbon\Carbon::parse($recall->recall_date)->format('d M Y') }}
                    </a>
                </li>
                @empty
                <li><span class="dropdown-item-text">No recalls due</span></li>
                @endforelse

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a class="dropdown-item text-center text-primary fw-bold" href="{{guard_route('recalls.notifications') }}">
                        <i class="fas fa-folder-open me-2"></i> View All Recalls
                    </a>
                </li>
            </ul>
        </li>

        <!-- Tasks Notification Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle position-relative" href="#" id="tasksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-tasks fa-fw"></i>
                @if(!empty($taskCount) && $taskCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                    {{ $taskCount }}
                    <span class="visually-hidden">tasks due</span>
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
                    <a class="dropdown-item" href="{{guard_route('tasks.tasks.edit', ['patient' => $task->patient_id, 'task' => $task->id]) }}">
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
                    <a class="dropdown-item text-center text-primary fw-bold" href="{{guard_route('tasks.notifications') }}">
                        <i class="fas fa-folder-open me-2"></i> View All Tasks
                    </a>
                </li>
            </ul>
        </li>





        <!-- Existing User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                    <a class="dropdown-item" href="{{ guard_route('password.change') }}">
                        {{ __('Change Password') }}
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li>
                    <a class="dropdown-item" href="{{guard_route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{guard_route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>