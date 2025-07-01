@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="mt-4">{{ $pageTitle }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('doctors.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $doctor->name }}
            </div>
        </div>
    </div>
</div>
@endsection