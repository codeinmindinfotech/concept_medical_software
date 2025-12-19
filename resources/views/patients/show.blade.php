@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
@php
$breadcrumbs = [
['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
['label' => 'Patients', 'url' =>guard_route('patients.index')],
['label' => 'Show Patient'],
];
@endphp

@include('layout.partials.breadcrumb', [
'pageTitle' => 'Show Patient',
'breadcrumbs' => $breadcrumbs,
'backUrl' =>guard_route('patients.index'),
'isListPage' => false
])


@include('patients.inner_show', [
                    'patient' => $patient
                   ])

@endsection