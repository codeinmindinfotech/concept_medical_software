@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'Notifications', 'url' =>guard_route('notifications.index')],
    ['label' => 'Send Notification'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Send Notification',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('notifications.index'),
    'isListPage' => false
    ])

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    
    <form method="POST" action="{{ guard_route('notifications.managerform') }}">
        @csrf

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <div class="mb-3">
            <label for="user_id" class="form-label">Select Superadmin:<span class="txt-error">*</span></label>
            <select name="recipients[]" id="user_id" class="select2" multiple >
                <option value="">-- Select Users --</option>
                @foreach($users as $user)
                    <option value="user-{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            @error('user_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="recipients" class="form-label">Select Recipients:</label>
            <select name="recipients[]" class="select2" multiple >
                <optgroup label="Patients">
                    @foreach($patients as $patient)
                        <option value="patient-{{ $patient->id }}">{{ $patient->full_name }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Clinics">
                    @foreach($clinics as $clinic)
                        <option value="clinic-{{ $clinic->id }}">{{ $clinic->name }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Doctors">
                    @foreach($doctors as $doctor)
                        <option value="doctor-{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="message" class="form-label">Notification Message:</label>
            <textarea name="message" id="message" class="form-control" rows="4" required>{{ old('message') }}</textarea>
            @error('message')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <button type="submit" class="btn btn-primary">Send Notification</button>
    </form>
</div>
@endsection