<?php $page = 'Edit Clinic'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
                ['label' => 'Clinics', 'url' =>guard_route('clinics.index')],
                ['label' => 'Edit Clinic'],
            ];
        @endphp

        @include('layout.partials.breadcrumb', [
            'pageTitle' => 'Edit Clinic',
            'breadcrumbs' => $breadcrumbs,
            'backUrl' =>guard_route('clinics.index'),
            'isListPage' => false
        ])

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <!-- General -->
                <div class="card">
                    <div class="card-body">
                        <form action="{{guard_route('clinics.update', $clinic->id) }}" method="POST" data-ajax class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                        
                            @include('clinics.form', [
                                'clinic' => $clinic
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