@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patients.history.index', $patient->id)],
            ['label' => 'Create Patient History'],
        ];
    @endphp

    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Create Patient History',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.history.index', $patient->id),
        'isListPage' => false
    ])

    <form action="{{guard_route('patients.history.store', $patient->id) }}" method="POST" data-ajax class="needs-validation" novalidate>
        @csrf
        @include('patients.history.form', [
                    'patient' => $patient,
                    'history'=> $history
                    ])

    </form>

@endsection
