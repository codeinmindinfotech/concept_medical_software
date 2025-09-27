@props([
    'icon' => '',
    'label',
    'route',
    'permission' => null,
    'role' => null,
    'pattern' => null,
])

@php
    $isActive = $pattern ? is_guard_route($pattern) : is_guard_route($route);
    $hasPermission = $permission ? has_permission($permission) : true;
    $hasRole = $role ? has_role($role) : true;
    $hasAccess = $hasPermission && $hasRole;
@endphp

@if ($hasAccess)
    <a class="nav-link {{ $isActive ? 'active fw-bold text-primary' : '' }}"
       href="{{ guard_route($route) }}">
        <div class="sb-nav-link-icon"><i class="{{ $icon }}"></i></div>
        <span>{{ $label }}</span>
    </a>
@endif
