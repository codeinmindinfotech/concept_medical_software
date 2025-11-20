<?php $page = 'login'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Main Wrapper -->
<div class="main-wrapper login-body">
    <div class="login-wrapper">
        <div class="container">
            <div class="loginbox">
                <div class="login-left">
                    <img class="img-fluid" src="{{ URL::asset('/assets_admin/img/logo-white.png') }}" alt="Logo">
                </div>
                <div class="login-right">
                    <div class="login-right-wrap">
                        <h1>Login</h1>
                        <p class="account-subtitle">Access to our dashboard</p>

                        <!-- Form -->
                        <form method="post" action="{{guard_route('login') }}" class="needs-validation" novalidate>
                            @csrf
                            <div class="mb-3">
                                <input id="company" type="text" class="form-control @error('company') is-invalid @enderror" name="company" value="{{ old('company') }}" placeholder="Company Name" required autofocus>
                                <div class="text-danger pt-2">
                                    @error('0')
                                    {{ $message }}
                                    @enderror
                                    @error('company')
                                    {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
                                    <input id="password" type="password" class="form-control pass-input @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="current-password">
                                    <span class="feather-eye-off toggle-password"></span>
                                    <div class="text-danger pt-2">
                                        @error('password')
                                        {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary w-100" type="submit">Login</button>
                            </div>
                        </form>
                        <!-- /Form -->

                        @if (Route::has('password.request'))
                        <div class="text-center forgotpass">
                            <a href="{{guard_route('password.request') }}">Forgot
                                Password?</a>
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