@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Patient</h1>
    <ol class="breadcrumb mb-4" >
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">New Patient</li>
    </ol>
    
    <div class="pull-right">
        <a class="btn btn-primary btn-sm" href="{{ route('patients.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
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

<form action="{{ route('patients.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" class="form-control" placeholder="Name">
            </div>
        </div>
            
        <!-- Date of Birth -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Date of Birth:</strong>
                <input type="date" name="dob" class="form-control" required>
            </div>
        </div>

        <!-- Gender -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Gender:</strong>
                <select name="gender" class="form-control" required>
                    <option value="">-- Select Gender --</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>

        <!-- Contact Number -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Contact Number:</strong>
                <input type="text" name="phone" class="form-control" placeholder="Enter phone number" required>
            </div>
        </div>

        <!-- Email -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Email:</strong>
                <input type="email" name="email" class="form-control" placeholder="Enter email">
            </div>
        </div>

        <!-- Address -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Address:</strong>
                <textarea name="address" class="form-control" placeholder="Enter address" required></textarea>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Emergency Contact:</strong>
                <input type="text" name="emergency_contact" class="form-control" placeholder="Name & phone number">
            </div>
        </div>

        <!-- Medical History / Notes -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Medical History / Notes:</strong>
                <textarea name="medical_history" class="form-control" placeholder="Enter medical history or notes"></textarea>
            </div>
        </div>

        <!-- Insurance Information (optional) -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Insurance Info:</strong>
                <input type="text" name="insurance" class="form-control" placeholder="Enter insurance details (optional)">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
        </div>
    </div>
</form>
</div>
@endsection