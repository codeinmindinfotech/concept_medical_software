<?php $page = 'Create Doctor'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid px-1">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Doctors', 'url' =>guard_route('doctors.index')],
        ['label' => 'Create Doctor'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Create Doctor',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('doctors.index'),
        'isListPage' => false
        ])

        <div class="row">
            <div class="col-12">
                <!-- General -->
                <div class="card">
                    <div class="card-body">
                        <form action="{{guard_route('doctors.store') }}" method="POST"  data-ajax class="needs-validation" novalidate>
                            @csrf
                            @include('doctors.form', [
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
@push('scripts')
<script src="{{ URL::asset('/assets/js/signature.js') }}"></script>
@endpush