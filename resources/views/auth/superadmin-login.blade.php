{{-- @extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header"><h3 class="text-center font-weight-light my-4"><img src="{{ asset('theme/assets/img/logo.png') }}" alt="Company Logo" width="150" style="display: block; margin: 0 auto;">
                
                Superadmin Login</h3></div>
            <div class="card-body">
                <form method="POST" action="{{guard_route('superadmin.login.submit') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autofocus>
                        <label for="email">Email address</label>
                        @error('email')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" required>
                        <label for="password">Password</label>
                        @error('password')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button type="submit" class="btn btn-primary">Login</button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{guard_route('password.request') }}">
                                Forgot Your Password?
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection --}}

<?php $page = 'login'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <div class="loginbox">
                    <div class="login-left">
                        <img class="img-fluid" src="{{ asset('theme/assets/img/logor.png') }}" alt="Logo">
                    </div>
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Login</h1>
                            <p class="account-subtitle">Access to our dashboard</p>

                            <!-- Form -->
                            <form method="post" action="{{guard_route('superadmin.login.submit') }}">
                                @csrf
                                <div class="mb-3">
                                    <input class="form-control" type="text" placeholder="Email" value="{{ old('email') }}"
                                        name="email" id="email">
                                    <div class="text-danger pt-2">
                                        @error('0')
                                            {{ $message }}
                                        @enderror
                                        @error('email')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="pass-group">
                                        <input class="form-control pass-input" type="password" placeholder="Password"
                                             name="password" id="password">
                                        <span class="feather-eye-off toggle-password"></span>
                                        <div class="text-danger pt-2">
                                            @error('0')
                                                {{ $message }}
                                            @enderror
                                            @error('password')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="pass-group">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">Remember Me</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button class="btn btn-primary w-100" type="submit">Login</button>
                                </div>
                            </form>
                            <!-- /Form -->
                            @if (Route::has('password.request'))
                                <div class="text-center forgotpass">
                                    <a href="{{guard_route('password.request') }}">
                                            Forgot Your Password?
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->
@endsection
