<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="index.html">Medical Management</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
            class="fas fa-bars"></i></button>
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
       <!-- Notification Dropdown -->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" role="button"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>

        @if(!empty($monthlyRecallCount) && $monthlyRecallCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $monthlyRecallCount }}
            <span class="visually-hidden">recalls this month</span>
        </span>
        @endif
    </a>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
        <li>
            <h6 class="dropdown-header">Recalls this month</h6>
        </li>

        @forelse($recallsThisMonth as $recall)
        <li>
            <a class="dropdown-item" href="{{ route('recalls.notifications') }}">
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
            <a class="dropdown-item text-center text-primary fw-bold" href="{{ route('recalls.notifications') }}">
                🔍 Show All Recalls
            </a>
        </li>
    </ul>
</li>


        <!-- Existing User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>