@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Roles', 'url' => route('roles.index')],
            ['label' => 'Roles List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Roles List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('roles.create'),
        'isListPage' => true
    ])
    
    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-check"></i>
            Role Management
        </div>
        <div class="card-body">
            <div id="role-list" data-pagination-container>
                @include('roles.list', ['roles' => $roles])
            </div>
        </div> 

    </div>
</div>
@endsection