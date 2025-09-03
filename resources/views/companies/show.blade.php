@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => guard_route('dashboard.index')],
            ['label' => 'Companies', 'url' => guard_route('companies.index')],
            ['label' => 'Show Company'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Show Company',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => guard_route('companies.index'),
        'isListPage' => false
    ])

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0"><i class="fas fa-building me-2"></i>Company Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">

                <x-show-field label="Name" :value="$company->name" icon="fas fa-building" />
                <x-show-field label="DB Host" :value="$company->db_host" icon="fas fa-server" />
                <x-show-field label="DB Port" :value="$company->db_port" icon="fas fa-network-wired" />
                <x-show-field label="DB Name" :value="$company->db_database" icon="fas fa-database" />
                <x-show-field label="DB Username" :value="$company->db_username" icon="fas fa-user-shield" />
                <x-show-field label="DB Password">
                    <span class="password-mask">{{ $company->db_password }}</span>
                </x-show-field>
                {{-- Timestamps --}}
                <x-show-field label="Created At" :value="$company->created_at?->format('Y-m-d H:i')" icon="fas fa-calendar-plus" />
                <x-show-field label="Updated At" :value="$company->updated_at?->format('Y-m-d H:i')" icon="fas fa-calendar-check" />

            </div>
        </div>
    </div>
</div>
@endsection
