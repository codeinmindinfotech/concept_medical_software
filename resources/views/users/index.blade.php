@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Users Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
        <li class="breadcrumb-item active">Users</li>
    </ol>
    <div class="row">
        <div class="pull-right">
            <a class="btn btn-success mb-2" href="{{ route('users.create') }}" title="Create New User"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    
    
    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Users Management
        </div>
        <div class="card-body">
            <div id="users-list" data-pagination-container>
                @include('users.list', ['data' => $data])
            </div>
        </div> 
    </div>
</div>
@endsection