@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patients.physical.index', $patient->id)],
            ['label' => 'Create Patient Physical Exam'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Create Patient Physical Exam',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.physical.index', $patient->id),
        'isListPage' => false
    ])

   <form action="{{guard_route('patients.physical.store', $patient->id) }}" method="POST" class="validate-form">
        @csrf
        @include('patients.physical.form', [
                    'patient' => $patient,
                    'physical'=> $physical
                    ])

    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush
