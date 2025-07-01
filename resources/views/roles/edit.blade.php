@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Role</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm mb-2" href="{{ route('roles.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
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

<form method="POST" action="{{ route('roles.update', $role->id) }}">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" placeholder="Name" class="form-control" value="{{ $role->name }}">
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
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
        </div>
    </div>
</form>
@endsection
@push('scripts')
    <script src="{{ asset('theme/custom.js') }}"></script>
@endpush