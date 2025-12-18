@extends('layout.mainlayout')

@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
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

                    <form action="{{ route('patient.patient.notification.send') }}" method="POST" class="needs-validation" novalidate>
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
                            <label for="consultant_id" class="form-label">Select Consultant:</label>
                            <select name="recipients[]" id="consultant_id" class="form-control select2" multiple required>
                                <option value="">-- Select Doctors --</option>
                                @foreach($consultants as $cons)
                                <option value="consultant-{{ $cons->id }}">{{ $cons->name }}</option>
                                @endforeach
                            </select>
                            @error('consultant_id')
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

