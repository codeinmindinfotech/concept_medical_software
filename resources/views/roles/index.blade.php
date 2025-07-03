@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Role Management </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/dashbaord">Dashboard</a></li>
        <li class="breadcrumb-item active">Role Management</li>
    </ol>
    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="pull-right">
        <a class="btn btn-success mb-2" href="{{ route('roles.create') }}" title="Create Role"><i class="fa fa-plus"></i></a>
    </div>
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