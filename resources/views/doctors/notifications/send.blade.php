@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'Notifications', 'url' =>guard_route('notifications.index')],
    ['label' => 'Send Notification to Patients or Clinics'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Send Notification to Patients or Clinics',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('notifications.index'),
    'isListPage' => false
    ])

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('doctor.notification.send') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="patient_id" class="form-label">Select Recipients:<span class="txt-error">*</span></label>
            <select name="recipients[]" id="patient_id" class="select2" multiple required>
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
            <select name="recipients[]" id="clinic_id" class="select2" multiple required>
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
@endsection
