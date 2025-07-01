<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        @guest
        @else
        <div class="sb-sidenav-menu">
           <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="/dashboard">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Interface</div>
                <a class="nav-link" href="{{ route('users.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Manage Users
                </a>
                <a class="nav-link" href="{{ route('roles.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Manage Role
                </a>
                <a class="nav-link" href="{{ route('patients.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Manage Patient
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ Auth::user()->name }}
        </div>
        @endguest
    </nav>
</div>