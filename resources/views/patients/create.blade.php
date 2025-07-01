@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">{{ $pageTitle }}</h2>
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

<form action="{{ route('patients.store') }}" method="POST" class="validate-form">
    @csrf

    <div class="row">
        <!-- Name -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}">
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Date of Birth -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Date of Birth:</strong>
                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
                @error('dob') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Gender -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Gender:</strong>
                <select name="gender" class="form-control">
                    <option value="">-- Select Gender --</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Contact Number -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Contact Number:</strong>
                <input type="text" name="phone" class="form-control" placeholder="Enter phone number" value="{{ old('phone') }}">
                @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Email -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Email:</strong>
                <input type="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}">
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Address -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Address:</strong>
                <textarea name="address" class="form-control" placeholder="Enter address">{{ old('address') }}</textarea>
                @error('address') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Emergency Contact:</strong>
                <input type="text" name="emergency_contact" class="form-control" placeholder="Name & phone number" value="{{ old('emergency_contact') }}">
                @error('emergency_contact') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Medical History / Notes -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Medical History / Notes:</strong>
                <textarea name="medical_history" class="form-control" placeholder="Enter medical history or notes">{{ old('medical_history') }}</textarea>
                @error('medical_history') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Insurance Information (optional) -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Insurance Info:</strong>
                <input type="text" name="insurance" class="form-control" placeholder="Enter insurance details (optional)" value="{{ old('insurance') }}">
                @error('insurance') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2">
                <i class="fa-solid fa-floppy-disk"></i> Submit
            </button>
        </div>
    </div>
</form>

</div>
@endsection
@push('scripts')

@endpush