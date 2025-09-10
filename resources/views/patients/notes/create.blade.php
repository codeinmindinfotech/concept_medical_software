@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patients.notes.index', $patient->id)],
            ['label' => 'Create Patient Note'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Create Patient Note',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.notes.index', $patient->id),
        'isListPage' => false
    ])

   <form action="{{guard_route('patients.notes.store', $patient->id) }}" method="POST" class="validate-form">
        @csrf
        @include('patients.notes.form', [
                    'patient' => $patient,
                    'note'=> $note
                    ])

    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush
