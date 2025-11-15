<?php $page = 'Edit Consultant'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Consultants', 'url' =>guard_route('consultants.index')],
        ['label' => 'Edit consultant'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Edit consultant',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('consultants.index'),
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
                        <form action="{{guard_route('consultants.update', $consultant->id) }}" method="POST" data-ajax class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            @include('consultants.form', [
                            'consultant' => $consultant
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
{{-- <script src="{{ asset('theme/form-validation.js') }}"></script> --}}
@endpush