@extends('backend.theme.tabbed')

@section('tab-navigation')
    @include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patient-documents.index', $patient->id)],
            ['label' => 'Create Patient Document'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Create Patient Document',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patient-documents.index', $patient->id),
        'isListPage' => false
    ])

    <form action="{{guard_route('patient-documents.store', $patient->id) }}" method="POST" class="validate-form">
        @csrf
        @include('patients.documents.form', [
                    'patient' => $patient,
                    'templates'=> $templates
                    ])

    </form>
@endsection
