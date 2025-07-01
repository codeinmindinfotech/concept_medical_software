@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2 class="mt-4">{{ $pageTitle }}</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm mb-2" href="{{ route('patients.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
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

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Patient</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Doctor</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Invoice</button>
    </li>
</ul>
<div class="tab-content border border-top-0 p-3" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <form action="{{ route('patients.update', $patient->id) }}" method="POST" class="validate-form">
            @csrf
            @method('PUT')
        
            <div class="row">
                <!-- Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" name="name" value="{{ old('name', $patient->name) }}" class="form-control" placeholder="Name">
                    </div>
                </div>
        
                <!-- Date of Birth -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Date of Birth:</strong>
                        <input type="date" name="dob" value="{{ old('dob', $patient->dob) }}" class="form-control" required>
                    </div>
                </div>
        
                <!-- Gender -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Gender:</strong>
                        <select name="gender" class="form-control" required>
                            <option value="">-- Select Gender --</option>
                            <option value="Male" {{ old('gender', $patient->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $patient->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $patient->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
        
                <!-- Contact Number -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Contact Number:</strong>
                        <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" class="form-control" placeholder="Enter phone number" required>
                    </div>
                </div>
        
                <!-- Email -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Email:</strong>
                        <input type="email" name="email" value="{{ old('email', $patient->email) }}" class="form-control" placeholder="Enter email">
                    </div>
                </div>
        
                <!-- Address -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Address:</strong>
                        <textarea name="address" class="form-control" required>{{ old('address', $patient->address) }}</textarea>
                    </div>
                </div>
        
                <!-- Emergency Contact -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Emergency Contact:</strong>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $patient->emergency_contact) }}" class="form-control" placeholder="Name & phone number">
                    </div>
                </div>
        
                <!-- Medical History / Notes -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Medical History / Notes:</strong>
                        <textarea name="medical_history" class="form-control">{{ old('medical_history', $patient->medical_history) }}</textarea>
                    </div>
                </div>
        
                <!-- Insurance Information -->
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Insurance Info:</strong>
                        <input type="text" name="insurance" value="{{ old('insurance', $patient->insurance) }}" class="form-control" placeholder="Enter insurance details (optional)">
                    </div>
                </div>
        
                <!-- Submit Button -->
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-sm mb-2 mt-2">
                        <i class="fa-solid fa-floppy-disk"></i> Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Profile Tab Content</div>
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Contact Tab Content</div>
</div>
  

</div>
@endsection
@push('scripts')
    <script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush