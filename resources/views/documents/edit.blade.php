<?php $page = 'Edit Document'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-4">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Documents', 'url' =>guard_route('documents.index')],
        ['label' => 'Edit Document'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Edit Document',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('documents.index'),
        'isListPage' => false
        ])
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{guard_route('documents.update', $template->id) }}" data-ajax class="needs-validation" novalidate method="POST">
                            @csrf
                            @method('PUT')
                            @include('documents.form', [
                            'template' => $template
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