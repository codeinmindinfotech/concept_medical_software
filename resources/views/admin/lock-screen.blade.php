<?php $page = 'lock-screen'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <div class="loginbox">
                    <div class="login-left">
                        <img class="img-fluid" src="{{ URL::asset('/assets_admin/img/logo.png') }}" alt="Logo">
                    </div>
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <div class="lock-user">
                                <img class="rounded-circle"
                                    src="{{ URL::asset('/assets_admin/img/profiles/avatar-02.jpg') }}" alt="User Image">
                                <h4>John Doe</h4>
                            </div>

                            <!-- Form -->
                            <form action="{{ url('admin/index_admin') }}">
                                <div class="mb-3">
                                    <input class="form-control" type="password" placeholder="Password">
                                </div>
                                <div class="mb-0">
                                    <button class="btn btn-primary w-100" type="submit">Enter</button>
                                </div>
                            </form>
                            <!-- /Form -->

                            <div class="text-center dont-have">Sign in as a different user? <a
                                    href="{{ url('admin/login') }}">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->
@endsection
