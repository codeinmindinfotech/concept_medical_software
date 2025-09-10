@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'Patients', 'url' =>guard_route('audios.index')],
    ['label' => 'Audio Recording List'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Audio Recording List',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' =>guard_route('audios.index'),
    'isListPage' => false
    ])

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-microphone"></i> Audio Recording Management
        </div>
        <div class="card-body">
            <form id="audioForm" method="POST" action="{{guard_route('audios.update',$audio->id) }}" enctype="multipart/form-data" class="mb-4">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="patient_id" class="form-label"><strong>Patient Name</strong></label>
                    <select id="patient_id" name="patient_id" class="select2 @error('patient_id') is-invalid @enderror">
                        <option value="">-- Select Patient --</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" 
                            {{ old('patient_id', $audio->patient_id) == $patient->id ? 'selected' : '' }}>
                            {{ $patient->first_name }} {{ $patient->surname }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="doctor_id" class="form-label"><strong>Doctor Name</strong></label>
                    <select id="doctor_id" name="doctor_id" class="select2 @error('doctor_id') is-invalid @enderror">
                        <option value="">-- Select Doctor --</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" 
                            {{ old('doctor_id', $audio->doctor_id) == $doctor->id ? 'selected' : '' }}>
                            Dr. {{ $doctor->name }}
                        </option>                        
                        @endforeach
                    </select>
                </div>

                @if ($audio->file_path)
                        <div class="mb-3">
                            <label class="form-label"><strong>Existing Audio</strong></label>
                            <audio controls class="w-100" style="max-height: 80px;">
                                <source src="{{ asset_url($audio->file_path) }}" type="audio/webm">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    @endif

                <div class="mb-4">
                    {{-- <label for="audioFileInput" class="form-label"><strong>Recorded Audio</strong></label>
                    <input type="file" name="file_path" id="audioFileInput" accept="audio/*" hidden required>
                    <div class="d-flex align-items-center gap-2"> 
                         <button type="button" id="startRecording" class="btn btn-success">
                            <i class="fas fa-circle"></i> Start Recording
                        </button>
                        <button type="button" id="stopRecording" class="btn btn-danger" disabled>
                            <i class="fas fa-stop"></i> Stop Recording
                        </button> --}}
                        <button type="submit" id="uploadBtn" class="btn btn-primary" disabled>
                            <i class="fas fa-upload"></i> Upload Recording
                        </button>
                    {{-- </div> --}}
                </div>
            </form>


        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    const uploadBtn = document.getElementById('uploadBtn');

    // Store initial selected values from server-rendered form
    const initialPatient = '{{ old('patient_id', $audio->patient_id) }}';
    const initialDoctor = '{{ old('doctor_id', $audio->doctor_id) }}';

    // Function to check if patient or doctor changed from initial values
    function hasFormChanged() {
        const currentPatient = $('#patient_id').val();
        const currentDoctor = $('#doctor_id').val();
        return currentPatient !== initialPatient || currentDoctor !== initialDoctor;
    }

    function validateForm() {
        const patientSelected = $('#patient_id').val() !== "";
        const doctorSelected = $('#doctor_id').val() !== "";

        // Enable upload only if patient or doctor changed AND both selected
        uploadBtn.disabled = !(hasFormChanged() && patientSelected && doctorSelected);
    }

    // Initialize Select2
    $('#patient_id, #doctor_id').select2();

    // Listen for changes on patient or doctor selects
    $('#patient_id, #doctor_id').on('select2:select select2:unselect change', () => {
        validateForm();
    });

    // Run validation once on page load to disable upload button
    validateForm();
</script>
@endpush
