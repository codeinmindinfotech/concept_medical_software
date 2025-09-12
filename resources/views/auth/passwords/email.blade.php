@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Password Recovery</h3></div>
                <div class="card-body">
                    <div class="small mb-3 text-muted">Enter your email address and we will send you a link to reset your password.</div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{guard_route('password.email') }}">
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
                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            @if (Route::has('login'))
                                <div class="small">
                                    <a href="{{guard_route('login') }}">Have an account? Go to login</a>
                                </div>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </form>
                </div>
                {{-- @if (Route::has('register'))
                    <div class="card-footer text-center py-3">
                        <div class="small"><a href="{{guard_route('register') }}">Need an account? Sign up!</a></div>
                    </div>
                @endif --}}
            </div>
        </div>
    </div>
</div>

@endsection
