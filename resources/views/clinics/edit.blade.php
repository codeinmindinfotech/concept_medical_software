@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Clinics', 'url' => route('clinics.index')],
        ['label' => 'Edit Clinic'],
    ];
@endphp

@include('backend.theme.breadcrumb', [
    'pageTitle' => 'Edit Clinic',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('clinics.index'),
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

    <form action="{{ route('clinics.update', $clinic->id) }}" method="POST" class="validate-form">
        @csrf
        @method('PUT')
    
        @include('clinics.form', [
            'clinic' => $clinic
            ])

    </form>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush