@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">{{ $pageTitle }}</h2>
    <ol class="breadcrumb mb-4" >
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Add DropdownValue for {{$dropdown->name ?? ''}}</li>
    </ol>
    
    <div class="pull-right">
        <a class="btn btn-primary btn-sm" href="{{ route('dropdownvalues.index', $dropdown->id) }}"><i class="fa fa-arrow-left"></i> Back</a>
    </div>

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

<form action="{{ route('dropdownvalues.store', $dropdown->id) }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <strong>Value:</strong>
                <input type="text" name="value" class="form-control"  value="{{ old('value') }}" placeholder="value">
            </div>
        </div>
            
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
        </div>
    </div>
</form>
</div>
@endsection