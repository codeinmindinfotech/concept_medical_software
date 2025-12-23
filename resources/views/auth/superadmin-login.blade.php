<?php $page = 'login'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container pt-3">
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
