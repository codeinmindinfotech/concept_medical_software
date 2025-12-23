<?php $page = 'user-create'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-1">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
                ['label' => 'Users', 'url' =>guard_route('users.index')],
                ['label' => 'Create User'],
            ];
        @endphp

        @include('layout.partials.breadcrumb', [
            'pageTitle' => 'Users List',
            'breadcrumbs' => $breadcrumbs,
            'backUrl' =>guard_route('users.index'),
            'isListPage' => false
        ])

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
        <div class="card mb-4">
            <div class="card-header mb-1 p-2">
                <h5 class="mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{guard_route('users.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Name:</strong>
                                <input type="text" name="name" placeholder="Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Email:</strong>
                                <input type="email" name="email" placeholder="Email" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Password:</strong>
                                <input type="password" name="password" placeholder="Password" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Confirm Password:</strong>
                                <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Role:</strong>
                                <select name="roles" class="form-control select2" >
                                    @foreach ($roles as $value => $label)
                                    <option value="{{ $value }}">
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
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
<!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->  
@endsection

