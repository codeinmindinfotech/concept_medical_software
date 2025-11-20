<?php $page = 'patients-list'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patients.index')],
            ['label' => 'Create Patient'],
        ];
    @endphp

    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Create Patient',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.index'),
        'isListPage' => false
    ])

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> Please fix the following errors:<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{guard_route('patients.store') }}" method="POST" data-ajax class="needs-validation" novalidate>
        @csrf
        @include('patients.form', [
            'insurances' => $insurances,
            'preferredContact' => $preferredContact,
            'doctors' => $doctors,
            'titles' => $titles
            ])

    </form>
</div>
</div>
<!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->
@endsection

{{-- @push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush --}}
