<?php $page = 'user-edit'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-1">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
                ['label' => 'Users', 'url' =>guard_route('users.index')],
                ['label' => 'Create User'],
            ];
        @endphp

        @include('layout.partials.breadcrumb', [
            'pageTitle' => 'Users List',
            'breadcrumbs' => $breadcrumbs,
            'backUrl' =>guard_route('users.index'),
            'isListPage' => false
        ])

        @if (count($errors) > 0)
            <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            </div>
        @endif
        <div class="card mb-4">
            <div class="card-header mb-1 p-2">
            <h5 class="mb-0">Change Permissions for: {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ guard_route('users.update_permissions', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th><input type="checkbox" id="selectAll"> All</th>

                                <th>List</th>
                                <th>Create</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $actions = ['list', 'create', 'edit', 'delete'];
                                $groupedPermissions = [];
                                foreach ($allPermissions as $perm) {
                                    [$module, $action] = explode('-', $perm->name);
                                    $groupedPermissions[$module][$action] = $perm;
                                }
                            @endphp

                            @foreach($groupedPermissions as $module => $perms)
                                <tr>
                                    <td>{{ ucfirst($module) }}</td>
                                    <td>
                                        <input type="checkbox" class="select-module" data-module="{{ $module }}">
                                    </td>
                                    @foreach($actions as $action)
                                        @php $perm = $perms[$action] ?? null; @endphp
                                        <td>
                                            @if($perm)
                                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                                    {{ in_array($perm->id, $userPermissions) ? 'checked' : '' }}>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary">Save Permissions</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->          
@endsection