<?php $page = 'user-show'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-1">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
                ['label' => 'Roles', 'url' =>guard_route('roles.index')],
                ['label' => 'Show Role'],
            ];
        @endphp

        @include('layout.partials.breadcrumb', [
            'pageTitle' => 'Show Role',
            'breadcrumbs' => $breadcrumbs,
            'backUrl' =>guard_route('roles.index'),
            'isListPage' => false
        ])
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    {{ $role->name }}
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label fw-bold">Permissions:</label>
                    <div class="mt-2">
                        @foreach($rolePermissions as $v)
                            <span class="badge bg-primary me-1 mb-1">{{ $v->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
</div>    
@endsection
