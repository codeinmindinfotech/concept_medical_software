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
                            <h1>Password Recovery</h1>
                            <p class="account-subtitle">Enter your email address and we will send you a link to reset your password.</p>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <!-- Form -->
                            <form method="POST" action="{{guard_route('custom.password.email') }}">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input id="company" type="text" name="company" class="form-control @error('company') is-invalid @enderror"
                                           value="{{ old('company') }}" required autofocus>
                                    <label for="company">Company Name</label>
                                    @error('company')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="email">Email address</label>
                                </div>
                                <div class="mb-0">
                                    <button class="btn btn-primary w-100" type="submit">Reset Password</button>
                                </div>
                            </form>
                            <!-- /Form -->
                            @if (Route::has('login'))
                            <div class="text-center dont-have">Remember your password? <a
                                href="{{guard_route('login') }}">Login</a></div>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->
@endsection