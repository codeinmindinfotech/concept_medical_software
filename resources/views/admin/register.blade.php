<?php $page = 'register'; ?>
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
                            <h1>Register</h1>
                            <p class="account-subtitle">Access to our dashboard</p>

                            <!-- Form -->
                            <form action="{{ route('register.custom') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <input class="form-control" type="text" placeholder="Name" name="name"
                                        id="name">
                                    <div class="text-danger pt-2">
                                        @error('0')
                                            {{ $message }}
                                        @enderror
                                        @error('name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input class="form-control" type="text" placeholder="Email" name="email"
                                        id="email">
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
                                        <input class="form-control pass-new-input" type="password"
                                            placeholder="Confirm Password" name="confirm_password" id="confirm_password">
                                        <span class="feather-eye-off toggle-password1"></span>
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
                                <div class="mb-3 mb-0">
                                    <button class="btn btn-primary w-100" type="submit">Register</button>
                                </div>
                            </form>
                            <!-- /Form -->

                            <div class="login-or">
                                <span class="or-line"></span>
                                <span class="span-or">or</span>
                            </div>

                            <!-- Social Login -->
								<div class="social-login">
									<span>Login with</span>
									<a href="javascript:;" class="facebook"><i class="fa-brands fa-facebook-f"></i></a><a href="javascript:;" class="google"><i class="fa-brands fa-google"></i></a>
								</div>
								<!-- /Social Login -->

                            <div class="text-center dont-have">Already have an account? <a
                                    href="{{ url('admin/login') }}">Login</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->
@endsection
