@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patients.index')],
            ['label' => 'Edit Patient'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Edit Patient',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.index'),
        'isListPage' => false
    ])

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="{{guard_route('patients.update', $patient->id) }}" method="POST" class="validate-form">
        @csrf
        @method('PUT')
    
        @include('patients.form', [
            'patient' => $patient,
            'insurances' => $insurances,
            'preferredContact' => $preferredContact,
            'doctors' => $doctors,
            'titles' => $titles
            ])

    </form>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush