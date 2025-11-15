@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
@php
$breadcrumbs = [
['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
['label' => 'Patients', 'url' =>guard_route('patients.notes.index', $patient->id)],
['label' => 'Edit Note'],
];
@endphp
@include('layout.partials.breadcrumb', [
'pageTitle' => 'Edit Note',
'breadcrumbs' => $breadcrumbs,
'backUrl' =>guard_route('patients.notes.index', $patient->id),
'isListPage' => false
])
<form action="{{guard_route('patients.notes.update',[$patient->id, $note->id]) }}" method="POST" data-ajax class="needs-validation" novalidate>
    @csrf
    @method('PUT')

    @include('patients.notes.form', [
    'patient' => $patient,
    'note'=> $note
    ])

</form>
@endsection