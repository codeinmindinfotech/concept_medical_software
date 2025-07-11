@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Maintain Prices'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Maintain Prices',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('chargecodes.create'),
    'isListPage' => true
    ])

    @session('success')
    <div class="alert alert-success" role="alert">
        {{ $value }}
    </div>
    @endsession

    @php
    $hasFilters = request()->hasAny(['search']);
    @endphp
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i> Maintain Prices
            </div>
        </div>

        <div class="card-body ">
            <form method="GET" action="{{ route('chargecodeprices.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search here.." value="{{ request('search') }}">
                    </div>
                

                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>

                    <div class="col-auto">
                        <a href="{{ route('chargecodeprices.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync-alt me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body ">
            <div id="chargecodeprices-list" data-pagination-container>
                @include('chargecodes.chargecodeprices.list', ['insurances' => $insurances])
            </div>
        </div>
    </div>
</div>
@endsection
