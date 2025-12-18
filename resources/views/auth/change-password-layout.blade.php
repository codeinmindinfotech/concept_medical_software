@extends('layout.mainlayout')

@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Change Password
                    </h5>
                </div>
                <div class="card-body">

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

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
                    <form method="POST" action="{{guard_route('password.user.update') }}" data-ajax class="needs-validation" novalidate >
                        @csrf
                        <div class="row">
                            <div class="mb-3">
                                <label>Name <span class="txt-error">*</span></label>
                                <input type="text" class="form-control" value="{{ user_name() }}" disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label>New Password <span class="txt-error">*</span></label>
                                <input type="password" name="new_password" class="form-control" required>
                                @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label>Confirm New Password <span class="txt-error">*</span></label>
                                <input type="password" name="new_password_confirmation" class="form-control" required >
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection