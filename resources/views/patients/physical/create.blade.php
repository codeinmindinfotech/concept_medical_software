@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
@php
$breadcrumbs = [
['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
['label' => 'Patients', 'url' =>guard_route('patients.physical.index', $patient->id)],
['label' => 'Create Patient Physical Exam'],
];
@endphp

@include('layout.partials.breadcrumb', [
'pageTitle' => 'Create Patient Physical Exam',
'breadcrumbs' => $breadcrumbs,
'backUrl' =>guard_route('patients.physical.index', $patient->id),
'isListPage' => false
])
<form action="{{guard_route('patients.physical.store', $patient->id) }}" method="POST" data-ajax class="needs-validation" novalidate>
    @csrf
    @include('patients.physical.form', [
    'patient' => $patient,
    'physical'=> $physical
    ])

</form>

@endsection