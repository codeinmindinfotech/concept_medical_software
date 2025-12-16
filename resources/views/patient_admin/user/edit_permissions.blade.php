@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container">

        <div class="row">

            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> User Management
                    </h5>
                    <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('users.index') }}">
                        <i class="fas fa-plus-circle me-1"></i> List User
                    </a>
                </div>

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
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" {{ in_array($perm->id, $userPermissions) ? 'checked' : '' }}>
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
