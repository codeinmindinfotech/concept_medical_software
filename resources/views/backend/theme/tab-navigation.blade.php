<div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
    <a href="{{ route('tasks.tasks.index', ['patient' => $patient]) }}"
       class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
       <i class="fas fa-tasks me-2"></i>Tasks
    </a>

    <a href="{{ route('recalls.recalls.index', ['patient' => $patient]) }}"
        class="nav-link {{ request()->routeIs('recalls.*') ? 'active' : '' }}">
        <i class="fas fa-bell me-2"></i>Recalls
     </a>

    <a href="{{ route('waiting-lists.index', ['patient' => $patient]) }}"
       class="nav-link {{ request()->routeIs('waiting-lists.*') ? 'active' : '' }}">
       <i class="fas fa-notes-medical me-2"></i>Waiting Lists
    </a>
    
    <a href="{{ route('fee-notes.index', ['patient' => $patient]) }}"
       class="nav-link {{ request()->routeIs('fee-notes.*') ? 'active' : '' }}">
       <i class="fas fa-money-check-alt me-2"></i>Fee Notes
    </a>

    <a 
       class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
       <i class="fas fa-file-alt me-2"></i>Documents
    </a>
</div>