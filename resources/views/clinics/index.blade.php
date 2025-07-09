@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Clinics List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Clinics List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('clinics.create'),
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
            Clinics Management
        </div>
        <div class="card-body">
            <div id="clinics-list" data-pagination-container>
                @include('clinics.list', ['clinics' => $clinics])
            </div>
        </div> 
    </div>
</div>
@endsection