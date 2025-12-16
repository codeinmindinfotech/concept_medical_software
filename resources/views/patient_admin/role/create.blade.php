@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container">

        <div class="row">

            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Role Management
                    </h5>
                    @if(has_permission('role-list'))
                    <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('roles.index') }}">
                        <i class="fas fa-plus-circle me-1"></i> List Role
                    </a>
                    @endif
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

            <div class="card-body">
                <form method="POST" action="{{guard_route('roles.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                <input type="text" name="name" placeholder="Name" class="form-control">
                            </div>
                        </div>
                        @php
                        // Group permissions by module and action
                        $groupedPermissions = [];
        
                        foreach ($permission as $perm) {
                        if (strpos($perm->name, '-') !== false) {
                        [$module, $action] = explode('-', $perm->name);
                        $groupedPermissions[$module][$action] = $perm;
                        }
                        }
        
                        $actions = ['list', 'create', 'edit', 'delete']; // Define expected actions
                        @endphp
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Permission:</strong>
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
                                                    <input type="checkbox" name="permission[{{ $perm->id }}]" value="{{ $perm->id }}" class="perm-checkbox {{ $module }}-perm">
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
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->  
@endsection

