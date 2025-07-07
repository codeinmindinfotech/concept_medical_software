@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Patients', 'url' => route('patients.notes.create', $patient->id)],
            ['label' => 'Edit Note'],
        ];
    @endphp
    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Edit Note',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('patients.notes.create', $patient->id),
        'isListPage' => false
    ])

    <form action="{{ route('patients.notes.update',[$patient->id, $note->id]) }}" method="POST" class="validate-form">
        @csrf
        @method('PUT')
    
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