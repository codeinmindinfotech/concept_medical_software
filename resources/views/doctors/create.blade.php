@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">{{ $pageTitle }}</h2>
    <ol class="breadcrumb mb-4" >
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">New Doctor</li>
    </ol>
    
    <div class="pull-right">
        <a class="btn btn-primary btn-sm" href="{{ route('doctors.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
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

<form action="{{ route('doctors.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" class="form-control" placeholder="Name">
            </div>
        </div>
            
        <!-- Contact Number -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Contact Number:</strong>
                <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
            </div>
        </div>

        <!-- Email -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Email:</strong>
                <input type="email" name="email" class="form-control" placeholder="Enter email">
            </div>
        </div>

        <!-- Gender -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Gender:</strong>
                <select name="gender" class="form-control">
                    <option value="">-- Select Gender --</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>

        <!-- Address -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Address:</strong>
                <textarea name="address" class="form-control" placeholder="Enter address"></textarea>
            </div>
        </div>

        <!-- postcode -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Postal code:</strong>
                <input type="text" name="postcode" class="form-control" placeholder="postal code">
            </div>
        </div>

        <!-- Notes -->
        <div class="col-md-6">
            <div class="form-group">
                <strong> Notes:</strong>
                <textarea name="note" class="form-control" placeholder="Enter notes"></textarea>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
        </div>
    </div>
</form>
</div>
@endsection