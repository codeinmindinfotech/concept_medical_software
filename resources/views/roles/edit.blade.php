<?php $page = 'user-show'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<div class="page-wrapper">
    <div class="container-fluid px-1">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => guard_route('dashboard.index')],
                ['label' => 'Roles', 'url' => guard_route('roles.index')],
                ['label' => 'Edit Role'],
            ];
        @endphp

        @include('layout.partials.breadcrumb', [
            'pageTitle' => 'Edit Role',
            'breadcrumbs' => $breadcrumbs,
            'backUrl' => guard_route('roles.index'),
            'isListPage' => false
        ])

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ guard_route('roles.update', $role->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Role Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" name="name" class="form-control" value="{{ $role->name }}">
                    </div>
                </div>

                <!-- Company Dropdown -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Company:</strong>
                        <select name="company_id" class="form-control">
                            <option value="">-- Select Company --</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ $role->company_id == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Permissions Table -->
                @php
                    $groupedPermissions = [];
                    foreach ($permission as $perm) {
                        if (strpos($perm->name, '-') !== false) {
                            [$module, $action] = explode('-', $perm->name);
                            $groupedPermissions[$module][$action] = $perm;
                        }
                    }
                    $actions = ['list', 'create', 'edit', 'delete'];
                @endphp

                <div class="col-12 mt-3">
                    <div class="form-group">
                        <strong>Permissions:</strong>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th><input type="checkbox" id="selectAll"> All</th>
                                        @foreach($actions as $action)
                                            <th>{{ ucfirst($action) }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupedPermissions as $module => $perms)
                                        <tr>
                                            <td><strong>{{ ucfirst($module) }}</strong></td>
                                            <td>
                                                <input type="checkbox" class="select-module" data-module="{{ $module }}">
                                            </td>
                                            @foreach($actions as $action)
                                                @php
                                                    $perm = $perms[$action] ?? null;
                                                @endphp
                                                <td>
                                                    @if($perm)
                                                        <input type="checkbox"
                                                            name="permission[{{ $perm->id }}]"
                                                            value="{{ $perm->id }}"
                                                            class="perm-checkbox {{ $module }}-perm"
                                                            {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary btn-sm mb-3">
                        <i class="fa-solid fa-floppy-disk"></i> Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
