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
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" readonly class="form-control-plaintext" value="{{ $user->name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" readonly class="form-control-plaintext" value="{{ $user->email }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Roles</label>
                            <div>
                                @foreach($user->getRoleNames() as $role)
                                <span class="badge bg-success">{{ $role }}</span>
                                @endforeach
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
