@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'Doctors', 'url' =>guard_route('doctors.index')],
    ['label' => 'Create Doctor'],
    ];
    @endphp

    @include('layout.partials.breadcrumb', [
    'pageTitle' => 'Create Doctor',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('doctors.index'),
    'isListPage' => false
    ])

    <form action="{{guard_route('doctors.store') }}" method="POST" class="validate-form">
        @csrf
        @include('doctors.form', [
        'contactTypes' => $contactTypes,
        'paymentMethods' => $paymentMethods
        ])
    </form>
</div>
@endsection
@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush