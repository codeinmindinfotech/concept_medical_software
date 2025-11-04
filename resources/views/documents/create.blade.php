@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Documents', 'url' =>guard_route('documents.index')],
        ['label' => 'Create Document'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Create Document',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('documents.index'),
        'isListPage' => false
    ])

    <form action="{{guard_route('documents.store') }}" method="POST" class="validate-form">
        @csrf
        @include('documents.form')
    </form>
</div>
@endsection
@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush