@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'ChargeCodees', 'url' => route('chargecodes.index')],
    ['label' => 'Create ChargeCodee'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Create ChargeCodee',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('chargecodes.index'),
    'isListPage' => false
    ])

    <form action="{{ route('chargecodes.store') }}" method="POST" class="validate-form">
        @csrf
        @include('chargecodes.form', [
            'insurances' => $insurances,
            'groupTypes' => $groupTypes
        ])
    </form>
</div>
@endsection
@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush