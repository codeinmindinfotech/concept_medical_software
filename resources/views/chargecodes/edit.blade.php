@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => guard_route('dashboard.index')],
    ['label' => 'Charge Codes', 'url' => guard_route('chargecodes.index')],
    ['label' => 'Edit Charge Code'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Edit Charge Code',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => guard_route('chargecodes.index'),
    'isListPage' => false
    ])

    <form action="{{ guard_route('chargecodes.update', $chargecode->id) }}" class="validate-form" method="POST">
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
