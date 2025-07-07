@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Patients', 'url' => route('patients.index')],
            ['label' => 'Create Patient Note'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Create Patient Note',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('patients.index'),
        'isListPage' => false
    ])

   <form action="{{ route('patients.notes.store', $patient->id) }}" method="POST" class="validate-form">
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
