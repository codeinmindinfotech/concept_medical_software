@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Doctors', 'url' => route('doctors.index')],
    ['label' => 'Create Doctor'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Create Doctor',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('doctors.index'),
    'isListPage' => false
    ])

    <form action="{{ route('doctors.store') }}" method="POST" class="validate-form">
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