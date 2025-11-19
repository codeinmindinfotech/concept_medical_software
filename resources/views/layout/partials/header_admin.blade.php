 <!-- Main Wrapper -->
 @if (!Route::is(['error-404', 'error-500', 'forgot-password', 'lock-screen', 'login', 'register']))
     <div class="main-wrapper">
 @endif
 <!-- Header -->
 <div class="header">
			
    <!-- Logo -->
    <div class="header-left">
        <a href="{{url('admin/index_admin')}}" class="logo">
            <img src="{{ URL::asset('/assets_admin/img/logo.png')}}" alt="Logo">
        </a>
        <a href="{{url('admin/index_admin')}}" class="logo logo-small">
            <img src="{{ URL::asset('/assets_admin/img/logo-small.png')}}" alt="Logo" width="30" height="30">
        </a>
    </div>
    <!-- /Logo -->
    
    <a href="javascript:void(0);" id="toggle_btn">
        <i class="fe fe-text-align-left"></i>
    </a>
    
    {{-- <div class="top-nav-search">
        <form>
            <input type="text" class="form-control" placeholder="Search here">
            <button class="btn" type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div> --}}
    
    <!-- Mobile Menu Toggle -->
    <a class="mobile_btn" id="mobile_btn">
        <i class="fa fa-bars"></i>
    </a>
    <!-- /Mobile Menu Toggle -->
    
    <!-- Header Right Menu -->
    <ul class="nav user-menu">

        <!-- Notifications -->
        <li class="nav-item dropdown noti-dropdown">
            <a href="javascript:;" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <i class="fe fe-bell"></i> <span class="badge rounded-pill" id="notification-count"></span>
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
                    <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                </div>
                <div class="noti-content">
                    <ul class="notification-list" id="notification-list">
                         <!-- Run time Notification Notifications -->                     
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="{{ guard_route('notifications.index') }}">View all Notifications</a>
                </div>
            </div>
        </li>
        <!-- /Notifications -->
        
        <li class="nav-item dropdown">
            <a href="javascript:;" class="dropdown-toggle nav-link" data-bs-toggle="dropdown" id="recallsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fe fe-calendar"></i> 
                @if(!empty($monthlyRecallCount) && $monthlyRecallCount > 0)
                <span class="badge rounded-pill bg-primary text-dark" id="recall-count">{{ $monthlyRecallCount }}</span>
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
        <li class="nav-item dropdown me-3">
            <a class="nav-link dropdown-toggle position-relative" href="#" id="tasksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-tasks"></i>
                @if(!empty($taskCount) && $taskCount > 0)
                <span class="badge rounded-pill bg-warning text-dark" id="recall-count">{{ $taskCount }}</span>
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
                    <a class="dropdown-item" href="{{guard_route('tasks.edit', ['patient' => $task->patient_id, 'task' => $task->id]) }}">
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
        <!-- User Menu -->
        <li class="nav-item dropdown has-arrow">
            <a href="javascript:;" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <span class="user-img"><img class="rounded-circle" src="{{ URL::asset('/assets_admin/img/profiles/avatar-01.jpg')}}" width="31" alt="Ryan Taylor"></span>
            </a>
            <div class="dropdown-menu">
                <div class="user-header">
                    <div class="avatar avatar-sm">
                        <img src="{{ URL::asset('/assets_admin/img/profiles/avatar-01.jpg')}}" alt="User Image" class="avatar-img rounded-circle">
                    </div>
                    <div class="user-text">
                        <h6>{{ Auth::user()->name ?? Auth::user()->full_name }}</h6>
                        <p class="text-muted mb-0">Administrator</p>
                    </div>
                </div>
                <a class="dropdown-item" href="{{ guard_route('password.change') }}">Change Password</a>
                {{-- <a class="dropdown-item" href="{{url('admin/settings')}}">Settings</a> --}}
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
    <!-- /Header Right Menu -->
    
</div>
<!-- /Header -->
