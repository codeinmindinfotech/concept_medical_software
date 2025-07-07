@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Doctors', 'url' => route('doctors.index')],
    ['label' => 'Edit Doctor'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Edit Doctor',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('doctors.index'),
    'isListPage' => false
    ])

    <form action="{{ route('doctors.update', $doctor->id) }}" class="validate-form" method="POST">
        @csrf
        @method('PUT')

        @include('doctors.form', [
        'doctor' => $doctor,
        'contactTypes' => $contactTypes,
        'paymentMethods' => $paymentMethods
        ])
    </form>

</div>
@endsection
@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush
