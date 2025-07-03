<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        @guest
        @else
        <div class="sb-sidenav-menu">
           <div class="nav">
                {{-- <div class="sb-sidenav-menu-heading">Core</div> --}}
                <a class="nav-link" href="/dashboard">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                {{-- <div class="sb-sidenav-menu-heading">Interface</div> --}}
                @php
                    $isUtilitiesOpen = Request::is('users*') || Request::is('roles*') || Request::is('dropdowns*');
                @endphp
                <a class="nav-link {{ Request::is('patients*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                    Manage Patient
                </a>
                <a class="nav-link {{ Request::is('doctors*') ? 'active' : '' }}" href="{{ route('doctors.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-md"></i></div>
                    Manage Doctor
                </a>
                <a class="nav-link {{ Request::is('insurances*') ? 'active' : '' }}" href="{{ route('insurances.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-md"></i></div>
                    Manage Insurance
                </a>

               

            <a class="nav-link {{ $isUtilitiesOpen ? '' : 'collapsed' }}" 
                href="#" 
                data-bs-toggle="collapse" 
                data-bs-target="#collapseUtilities"
                aria-expanded="{{ $isUtilitiesOpen ? 'true' : 'false' }}" 
                aria-controls="collapseUtilities">
                    <div class="sb-nav-link-icon"><i class="fas fa-fw fa-wrench"></i></div>
                    Utilities
                    <i class="fas fa-angle-down float-end ms-auto"></i>
            </a>

                <div id="collapseUtilities" 
                    class="collapse {{ $isUtilitiesOpen ? 'show' : '' }}" 
                    aria-labelledby="headingUtilities" 
                    data-bs-parent="#accordionSidebar">
                    <div class="bg-secondary py-2 collapse-inner">
                        <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <i class="fas fa-fw fa-user"></i>
                            <span>Manage Users</span>
                        </a>
                        <a class="nav-link {{ Request::is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                            <i class="fas fa-user-check"></i>
                            <span>Manage Role</span>
                        </a>
                        <a class="nav-link {{ Request::is('dropdowns*') ? 'active' : '' }}" href="{{ route('dropdowns.index') }}">
                            <i class="fas fa-list"></i>
                            <span>Manage Dropdown</span>
                        </a>
                    </div>
                </div>             
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ Auth::user()->name }}
        </div>
        @endguest
    </nav>
</div>