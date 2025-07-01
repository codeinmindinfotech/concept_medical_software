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
                {{-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Custom Components:</h6>
                    <a class="collapse-item" href="#">Buttons</a>
                    <a class="collapse-item" href="#">Cards</a>
                    </div>
                </div> --}}
                <a class="nav-link" href="{{ route('patients.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                    Manage Patient
                </a>
                <a class="nav-link" href="{{ route('doctors.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-md"></i></div>
                    Manage Doctor
                </a>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <div class="sb-nav-link-icon"><i class="fas fa-fw fa-wrench"></i></div>
                    Utilities
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-bs-parent="#accordionSidebar">
                    <div class="bg-secondary  py-2 collapse-inner rounded">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="fas fa-fw fa-user"></i>
                            <span>Manage Users</span>
                            {{-- <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div> --}}
                            
                        </a>
                        <a class="nav-link" href="{{ route('roles.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-check"></i></div>
                            Manage Role
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