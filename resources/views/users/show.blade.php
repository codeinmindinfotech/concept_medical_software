<?php $page = 'user-show'; ?>
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

        {{-- User Information Card --}}
        <div class="card mb-4">
            <div class="card-header mb-1 p-2">
                <h5 class="mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" readonly class="form-control-plaintext" value="{{ $user->name }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" readonly class="form-control-plaintext" value="{{ $user->email }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Roles</label>
                        <div>
                            @foreach($user->getRoleNames() as $role)
                            <span class="badge bg-success">{{ $role }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->
@endsection

