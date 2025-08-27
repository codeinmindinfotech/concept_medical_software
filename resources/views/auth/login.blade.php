@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header">
                <h3 class="text-center font-weight-light my-4">Login</h3>
            </div>
            <div class="card-body">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Login Type Dropdown --}}
                    <div class="form-floating mb-3">
                        <select name="login_type" id="login_type" class="form-select" required>
                            <option value="">-- Select Login Type --</option>
                            <option value="superadmin" {{ old('login_type') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                            <option value="clinic" {{ old('login_type') == 'clinic' ? 'selected' : '' }}>Clinic</option>
                            <option value="doctor" {{ old('login_type') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                            <option value="patient" {{ old('login_type') == 'patient' ? 'selected' : '' }}>Patient</option>
                        </select>
                        <label for="login_type">Login As</label>
                    </div>
                    {{-- Clinic Selection Dropdown (shown only if login_type is 'clinic') --}}
                    <div class="form-floating mb-3" id="clinic_id_container" style="display:none;">
                        <select name="clinic_id" id="clinic_id" class="form-select @error('clinic_id') is-invalid @enderror">
                            <option value="">-- Select Clinic --</option>
                            @foreach ($clinics as $clinic)
                                <option value="{{ $clinic->id }}" {{ old('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                    {{ $clinic->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="clinic_id">Clinic</label>
                    
                        @error('clinic_id')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    


                    {{-- Email --}}
                    <div class="form-floating mb-3">
                        <input id="email" type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <label for="email">Email address</label>
                    </div>

                    {{-- Password --}}
                    <div class="form-floating mb-3">
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <label for="password">Password</label>
                    </div>

                    {{-- Remember Me --}}
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox"
                            name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

                    {{-- Submit and Forgot Password --}}
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>

                        @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            @if (Route::has('register'))
            <div class="card-footer text-center py-3">
                <div class="small"><a href="{{ route('register') }}">Need an account? Sign up!</a></div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection