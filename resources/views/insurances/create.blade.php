<?php $page = 'insurances.create'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Insurances', 'url' =>guard_route('insurances.index')],
        ['label' => 'Create Insurance'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Create Insurance',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('insurances.index'),
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
        <div class="row">
            <div class="col-12">
                <!-- General -->
                <div class="card">
                    <div class="card-body">
                        <form action="{{guard_route('insurances.store') }}" method="POST" data-ajax class="needs-validation" novalidate>
                            @csrf
                            @include('insurances.form')
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
{{-- <script src="{{ asset('theme/form-validation.js') }}"></script> --}}
@endpush

