@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Patients', 'url' => route('patients.index')],
    ['label' => 'Show Patient'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Show Patient',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('patients.index'),
    'isListPage' => false
    ])

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                role="tab">Patient</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                role="tab">Doctor</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button"
                role="tab">Insurance</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#note" type="button"
                role="tab">Notes</button>
        </li>
    </ul>

    <div class="tab-content border border-top-0 p-3" id="myTabContent">

        {{-- Patient Information --}}
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <h4>Personal Information</h4>
            <p><strong>Title:</strong> {{ $patient->title->value ?? 'N/A' }}</p>
            <p><strong>Name:</strong> {{ $patient->first_name }} {{ $patient->surname }}</p>
            <p><strong>Date of Birth:</strong> {{ $patient->dob }}</p>
            <p><strong>Gender:</strong> {{ $patient->gender }}</p>

            <h4>Contact Information</h4>
            <p><strong>Phone:</strong> {{ $patient->phone }}</p>
            <p><strong>Email:</strong> {{ $patient->email }}</p>
            <p><strong>Preferred Contact Method:</strong> {{ $patient->preferredContact->value ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $patient->address }}</p>

            <h4>Emergency Contact</h4>
            <p>{{ $patient->emergency_contact }}</p>

            <h4>Medical Information</h4>
            <p><strong>Medical History:</strong> {{ $patient->medical_history }}</p>
            <p><strong>Referral Reason:</strong> {{ $patient->referral_reason }}</p>
            <p><strong>Symptoms:</strong> {{ $patient->symptoms }}</p>
            <p><strong>Patient Needs:</strong> {{ $patient->patient_needs }}</p>
            <p><strong>Allergies:</strong> {{ $patient->allergies }}</p>
            <p><strong>Diagnosis:</strong> {{ $patient->diagnosis }}</p>

            <h4>Insurance Information</h4>
            <p><strong>Provider:</strong> {{ $patient->insurance->name ?? 'N/A' }}</p>
            <p><strong>Plan:</strong> {{ $patient->insurance_plan }}</p>
            <p><strong>Policy No:</strong> {{ $patient->policy_no }}</p>

            <h4>Status</h4>
            <p><strong>RIP:</strong> {{ $patient->rip ? 'Yes' : 'No' }}</p>
            @if($patient->rip)
            <p><strong>RIP Date:</strong> {{ $patient->rip_date }}</p>
            @endif

            <h4>Consent</h4>
            <p><strong>SMS Consent:</strong> {{ $patient->sms_consent ? 'Yes' : 'No' }}</p>
            <p><strong>Email Consent:</strong> {{ $patient->email_consent ? 'Yes' : 'No' }}</p>
        </div>

        {{-- Doctor Information --}}
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            @if($patient->doctor)
            <h4>Doctor Details</h4>
            <p><strong>Name:</strong> Dr. {{ $patient->doctor->name }}</p>
            <p><strong>Email:</strong> {{ $patient->doctor->email }}</p>
            <p><strong>Phone:</strong> {{ $patient->doctor->phone }}</p>
            @else
            <p>No doctor assigned.</p>
            @endif
        </div>

        {{-- Invoice or Contact --}}
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <h4>Invoice / Additional Information</h4>
            @if($patient->insurance)
            <h4>Insurance Details</h4>
            <p><strong>Code:</strong> {{ $patient->insurance->code }} </p>
            <p><strong>Email:</strong> {{ $patient->insurance->email }}</p>
            <p><strong>Phone:</strong> {{ $patient->insurance->contact }}</p>
            @else
            <p>No doctor assigned.</p>
            @endif
        </div>

        {{-- Notes --}}
<div class="tab-pane fade" id="note" role="tabpanel" aria-labelledby="note-tab">
    <h4>Patient Notes</h4>
    @if($patient->notes->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Method</th>
                        <th>Note</th>
                        <th>Completed</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->notes as $index => $note)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ ucfirst($note->method) }}</td>
                            <td>{{ $note->notes }}</td>
                            <td>{{ $note->completed ? 'Yes' : 'No' }}</td>
                            <td>{{ $note->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No notes available for this patient.</p>
    @endif
</div>


    </div>

</div>
@endsection