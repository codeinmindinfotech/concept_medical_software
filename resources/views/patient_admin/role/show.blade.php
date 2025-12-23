@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container pt-3">

        <div class="row">

            <div class="card mb-4 shadow-sm p-3">
                <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
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
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                {{ $role->name }}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Permissions:</label>
                                <div class="mt-2">
                                    @foreach($rolePermissions as $v)
                                        <span class="badge bg-primary me-1 mb-1">{{ $v->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->
@endsection
