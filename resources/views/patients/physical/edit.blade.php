@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="container-fluid px-1">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patients.physical.index', $patient->id)],
            ['label' => 'Edit Physical Exam'],
        ];
    @endphp
    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Edit Physical Exam',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.physical.index', $patient->id),
        'isListPage' => false
    ])
    
    <form action="{{guard_route('patients.physical.update',[$patient->id, $physical->id]) }}" method="POST" data-ajax class="needs-validation" novalidate>
        @csrf
        @method('PUT')
    
        @include('patients.physical.form', [
                    'patient' => $patient,
                    'physical'=> $physical
                    ])

    </form>
</div>
@endsection