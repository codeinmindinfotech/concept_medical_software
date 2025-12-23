@extends('layout.mainlayout')

@section('content')
<div class="content">
    <div class="container pt-3">
        <div class="row">
            <div class="card mb-4 shadow-sm p-3">
                <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Notification Management
                    </h5>
                    <a href="{{ guard_route('notifications.index')  }}" class="btn bg-primary-light btn-sm">
                        <i class="isax isax-notification-bing"></i>View All
                    </a>
                </div>
                <div class="card-body">

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('doctor.notification.send') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="manager_id" class="form-label">Select Managers:<span class="txt-error">*</span></label>
                            <select name="recipients[]" id="manager_id" class="form-control select2" multiple required>
                                <option value="">-- Select Manager --</option>
                                @foreach($managers as $manager)
                                <option value="manager-{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                            @error('manager_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
            
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Select Patients:<span class="txt-error">*</span></label>
                            <select name="recipients[]" id="patient_id" class="form-control select2" multiple required>
                                <option value="">-- Select Patient --</option>
                                @foreach($patients as $patient)
                                <option value="patient-{{ $patient->id }}">{{ $patient->full_name }}</option>
                                @endforeach
                            </select>
                            @error('patient_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
            
                        <div class="mb-3">
                            <label for="clinic_id" class="form-label">Select Clinics:</label>
                            <select name="recipients[]" id="clinic_id" class="form-control select2" multiple required>
                                <option value="">-- Select Clinics --</option>
                                @foreach($clinics as $clinic)
                                <option value="clinic-{{ $clinic->id }}">{{ $clinic->code }} - {{ $clinic->name }}</option>
                                @endforeach
                            </select>
                            @error('clinic_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
            
                        <div class="mb-3">
                            <label>Message:<span class="txt-error">*</span></label>
                            <textarea name="message" class="form-control" required></textarea>
                        </div>
            
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

