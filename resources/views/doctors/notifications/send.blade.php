@extends('backend.theme.default')

@section('content')
<div class="container">
    <h2>Send Notification to Patients or Clinics</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('doctor.notification.send') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Message:</label>
            <textarea name="message" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Select Recipients:</label>
            <div class="form-check">
                <strong>Patients:</strong><br>
                @foreach($patients as $patient)
                    <input class="form-check-input" type="checkbox" name="recipients[]" value="patient-{{ $patient->id }}">
                    <label class="form-check-label">{{ $patient->full_name }}</label><br>
                @endforeach
            </div>

            <div class="form-check mt-3">
                <strong>Clinics:</strong><br>
                @foreach($clinics as $clinic)
                    <input class="form-check-input" type="checkbox" name="recipients[]" value="clinic-{{ $clinic->id }}">
                    <label class="form-check-label">{{ $clinic->name }}</label><br>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>
@endsection
