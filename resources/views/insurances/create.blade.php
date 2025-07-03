@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">{{ $pageTitle }}</h2>
    <ol class="breadcrumb mb-4" >
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">New Insurance</li>
    </ol>

    <div class="pull-right mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('insurances.index') }}">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

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

    <form action="{{ route('insurances.store') }}" method="POST" class="validate-form">
        @csrf

        @include('insurances.form')
        
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush
