<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        @guest
        @else
        <div class="sb-sidenav-menu">
            <div class="nav">
        
                {{-- Dashboard --}}
                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                    <span>Dashboard</span>
                </a>
                <a class="nav-link {{ Request::is('patients*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-procedures"></i></div>
                    <span>Patients</span>
                </a>
               
                @can('viewAny', App\Models\Doctor::class)
                <a class="nav-link {{ Request::is('doctors*') ? 'active' : '' }}" href="{{ route('doctors.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-md"></i></div>
                    <span>Doctors</span>
                </a>
                @endcan
        
                @can('viewAny', App\Models\Consultant::class)
                <a class="nav-link {{ Request::is('consultants*') ? 'active' : '' }}" href="{{ route('consultants.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-stethoscope"></i></div>
                    <span>Consultants</span>
                </a>
                @endcan
        
                @if(auth()->user()->can('insurance-list'))
                <a class="nav-link {{ Request::is('insurances*') ? 'active' : '' }}" href="{{ route('insurances.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-medical"></i></div>
                    <span>Insurance</span>
                </a>
                @endif
        
                @can('viewAny', App\Models\ChargeCode::class)
                <a class="nav-link {{ Request::is('chargecodes*') ? 'active' : '' }}" href="{{ route('chargecodes.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <span>Charge Codes</span>
                </a>
                @endcan

                @if(auth()->user()->can('clinic-list'))
                <a class="nav-link {{ Request::is('clinics*') ? 'active' : '' }}" href="{{ route('clinics.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-clinic-medical"></i></div>
                    <span>Clinic</span>
                </a>
                @endif

                {{-- <a class="nav-link {{ Request::is('audios*') ? 'active' : '' }}" href="{{ route('audios.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-microphone"></i></div>
                    <span>Audio Recording</span>
                </a> --}}

                {{-- Utilities Collapsible Section --}}
                @php
                    $isUtilitiesOpen = Request::is('users*') || Request::is('roles*') || Request::is('dropdowns*');
                @endphp
        
                <a class="nav-link d-flex justify-content-between align-items-center {{ $isUtilitiesOpen ? '' : 'collapsed' }}"
                   href="#" data-bs-toggle="collapse" data-bs-target="#collapseUtilities"
                   aria-expanded="{{ $isUtilitiesOpen ? 'true' : 'false' }}" aria-controls="collapseUtilities">
                    <div class="d-flex align-items-center">
                        <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                        <span>Utilities</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </a>
        
                <div id="collapseUtilities" class="collapse {{ $isUtilitiesOpen ? 'show' : '' }}">
                    <div class="collapse-inner py-2 rounded-2 border-start border-3 border-primary ps-3">
                       <a class="nav-link {{ Request::is('users*') ? 'active fw-bold text-primary' : '' }}"
                           href="{{ route('users.index') }}">
                            <i class="fas fa-user me-2"></i> Users
                        </a>
        
                        @if(auth()->user()->can('role-list'))
                        <a class="nav-link {{ Request::is('roles*') ? 'active fw-bold text-primary' : '' }}"
                           href="{{ route('roles.index') }}">
                            <i class="fas fa-user-shield me-2"></i> Roles
                        </a>
                        @endif
        
                        @if(auth()->user()->can('dropdown-list'))
                        <a class="nav-link {{ Request::is('dropdowns*') ? 'active fw-bold text-primary' : '' }}"
                           href="{{ route('dropdowns.index') }}">
                            <i class="fas fa-list-ul me-2"></i> Dropdowns
                        </a>
                        @endif
        
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