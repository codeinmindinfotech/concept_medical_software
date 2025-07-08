@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Patients', 'url' => route('patients.history.index', $patient->id)],
            ['label' => 'Edit History'],
        ];
    @endphp
    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Edit History',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('patients.history.index', $patient->id),
        'isListPage' => false
    ])

    <form action="{{ route('patients.history.update',[$patient->id, $history->id]) }}" method="POST" class="validate-form">
        @csrf
        @method('PUT')
    
        @include('patients.history.form', [
                    'patient' => $patient,
                    'history'=> $history
                    ])

    </form>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush