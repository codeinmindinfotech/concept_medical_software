<?php $page = 'companies-edit'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-1">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Companies', 'url' =>guard_route('companies.index')],
        ['label' => 'Edit Company'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Edit Company',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('companies.index'),
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
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header mb-1 p-2">
                        <h5 class="card-title mb-0">Company Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{guard_route('companies.update', $company->id) }}" method="POST" data-ajax class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            @include('companies.form', [
                            'company' => $company
                            ])

                        </form>
                    </div>
                </div>
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
