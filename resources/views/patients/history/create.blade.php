@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patients.history.index', $patient->id)],
            ['label' => 'Create Patient History'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Create Patient History',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.history.index', $patient->id),
        'isListPage' => false
    ])

   <form action="{{guard_route('patients.history.store', $patient->id) }}" method="POST" class="validate-form">
        @csrf
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
