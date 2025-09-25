@extends('backend.theme.tabbed')

@section('tab-navigation')
    @include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
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
@endsection
