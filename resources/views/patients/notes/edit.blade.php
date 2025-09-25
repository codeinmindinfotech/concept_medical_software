@extends('backend.theme.tabbed')

@section('tab-navigation')
    @include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
    <div class="container-fluid px-4">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
                ['label' => 'Patients', 'url' =>guard_route('patients.notes.index', $patient->id)],
                ['label' => 'Edit Note'],
            ];
        @endphp
        @include('backend.theme.breadcrumb', [
            'pageTitle' => 'Edit Note',
            'breadcrumbs' => $breadcrumbs,
            'backUrl' =>guard_route('patients.notes.index', $patient->id),
            'isListPage' => false
        ])
        <form action="{{guard_route('patients.notes.update',[$patient->id, $note->id]) }}" method="POST" class="validate-form">
            @csrf
            @method('PUT')
        
            @include('patients.notes.form', [
                        'patient' => $patient,
                        'note'=> $note
                        ])

        </form>        
    </div>
@endsection