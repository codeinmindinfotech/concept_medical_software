@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Charge Codes', 'url' => route('chargecodes.index')],
    ['label' => 'Edit Charge Code'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Edit Charge Code',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('chargecodes.index'),
    'isListPage' => false
    ])

    <form action="{{ route('chargecodes.update', $chargecode->id) }}" class="validate-form" method="POST">
        @csrf
        @method('PUT')

        @include('chargecodes.form', [
            'chargecode' => $chargecode,
            'insurances' => $insurances,
            'groupTypes' => $groupTypes
        ])
    </form>

</div>
@endsection
@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush
