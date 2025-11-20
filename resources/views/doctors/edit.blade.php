<?php $page = 'Edit Doctor'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Doctors', 'url' =>guard_route('doctors.index')],
        ['label' => 'Edit Doctor'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Edit Doctor',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('doctors.index'),
        'isListPage' => false
        ])
        <div class="row">
            <div class="col-12">
                <!-- General -->
                <div class="card">
                    <div class="card-body">
                        <form action="{{guard_route('doctors.update', $doctor->id) }}" method="POST" class="needs-validation" novalidate data-ajax>
                            @csrf
                            @method('PUT')

                            @include('doctors.form', [
                            'doctor' => $doctor,
                            'contactTypes' => $contactTypes,
                            'paymentMethods' => $paymentMethods
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
{{-- @push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush --}}
