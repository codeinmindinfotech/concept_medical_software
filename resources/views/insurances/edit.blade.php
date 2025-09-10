@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Insurances', 'url' =>guard_route('insurances.index')],
        ['label' => 'Edit Insurance'],
    ];
@endphp

@include('backend.theme.breadcrumb', [
    'pageTitle' => 'Edit Insurance',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('insurances.index'),
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

    <form action="{{guard_route('insurances.update', $insurance->id) }}" method="POST" class="validate-form">
        @csrf
        @method('PUT')
    
        @include('insurances.form', [
            'insurance' => $insurance
            ])

    </form>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush