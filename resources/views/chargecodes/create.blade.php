<?php $page = 'chargecodes.create'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'ChargeCodees', 'url' =>guard_route('chargecodes.index')],
    ['label' => 'Create ChargeCodee'],
    ];
    @endphp

    @include('layout.partials.breadcrumb', [
    'pageTitle' => 'Create ChargeCodee',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('chargecodes.index'),
    'isListPage' => false
    ])
<div class="row">
    <div class="col-12">
        <!-- General -->
        <div class="card">
            <div class="card-body">
                <form action="{{guard_route('chargecodes.store') }}" method="POST" class="validate-form">
                    @csrf
                    @include('chargecodes.form', [
                        'insurances' => $insurances,
                        'groupTypes' => $groupTypes
                    ])
                </form>
            </div>
        </div>
        <!-- /General -->

    </div>
</div>

</div>
</div>
<!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->

@endsection
@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush