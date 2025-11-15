<?php $page = 'configurations-create'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-4">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Configurations', 'url' =>guard_route('configurations.index')],
        ['label' => 'Create Configurations'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Create Configuration',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('configurations.index'),
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

        <form action="{{guard_route('configurations.store') }}" method="POST" data-ajax class="needs-validation" novalidate>
            @csrf
            @include('configurations.form')
        </form>
    </div>
</div>
</div>
@endsection