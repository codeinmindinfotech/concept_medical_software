@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Patients', 'url' => route('patients.physical.index', $patient->id)],
            ['label' => 'Edit Physical Exam'],
        ];
    @endphp
    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Edit Physical Exam',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('patients.physical.index', $patient->id),
        'isListPage' => false
    ])

    <form action="{{ route('patients.physical.update',[$patient->id, $physical->id]) }}" method="POST" class="validate-form">
        @csrf
        @method('PUT')
    
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