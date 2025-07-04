@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Users', 'url' => route('users.index')],
            ['label' => 'Users List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Users List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('users.create'),
        'isListPage' => true
    ])
    
    
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