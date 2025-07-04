@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Consultants', 'url' => route('consultants.index')],
            ['label' => 'Consultants List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Consultants List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('consultants.create'),
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
            Consultants Management
        </div>
        <div class="card-body">
            <div id="consultants-list" data-pagination-container>
                @include('consultants.list', ['consultants' => $consultants])
            </div>
        </div> 
    </div>
</div>
@endsection