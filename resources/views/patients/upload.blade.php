@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patients.index')],
            ['label' => 'Edit Patient'],
        ];
    @endphp

    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Edit Patient',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.index'),
        'isListPage' => false
    ])

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
    <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-camera"></i> Upload Patient Picture</h5>
        </div>

        <div class="card-body text-center">
            <h3>Upload Profile Picture</h3>

<div class="btn-group mb-3" role="group">
    <button class="btn btn-outline-primary" onclick="showUpload()">Upload Image</button>
    <button class="btn btn-outline-success" onclick="showWebcam()">Use Webcam</button>
</div>

<!-- Upload from Computer -->
<div id="uploadBox" style="display:none;">
    <form id="browseUploadForm" action="{{ guard_route('patients.upload-picture', $patient->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $patient->id }}">

        <label>Select Image:</label>
        <input type="file" name="patient_picture" class="form-control" accept="image/*">
        <button class="btn btn-primary mt-3">Upload</button>
    </form>
</div>

<!-- Webcam Capture -->
<div id="webcamBox" style="display:none;">
    <div id="camera" style="width:320px;height:240px;border:1px solid #ccc;"></div>

    <button onclick="takeSnapshot()" class="btn btn-success mt-2">Capture</button>

    <div id="snapshotResult" class="mt-3"></div>

    <form id="webcamForm" action="{{ guard_route('patients.upload-picture', $patient->id) }}" method="POST">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
        <input type="hidden" name="patient_picture_webcam" id="webcamImageField">

        <button type="submit" class="btn btn-primary mt-3" style="display:none;" id="uploadBtn">
            Upload Captured Image
        </button>
    </form>
</div>

            {{-- <form id="uploadPatientPictureForm" action="{{ guard_route('patients.upload-picture', $patient->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <div class="mb-3 position-relative d-inline-block">
                    <input type="file" name="patient_picture" id="patient_picture_input" class="d-none" accept="image/*" >
                    <label for="patient_picture_input" style="cursor: pointer; display: inline-block; position: relative;">
                        <img id="patient_picture_preview"
                             src="{{ $patient->patient_picture ? asset('storage/' . $patient->patient_picture) : asset('default-avatar.png') }}"
                             alt="Patient Picture"
                             class="rounded-circle border"
                             style="width: 150px; height: 150px; object-fit: cover;">
                             
                        <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow"
                              style="transform: translate(20%, 20%);">
                            <i class="fas fa-camera fa-lg text-primary"></i>
                        </span>
                    </label>
                </div>

                <h5 class="mb-1">{{ $patient->full_name }}</h5>
                <p class="text-muted mb-4">DOB: {{ format_date($patient->dob) }} | Gender: {{ $patient->gender }}</p>

                <button type="submit" class="btn btn-primary w-100">Upload Picture</button>
            </form> --}}
        </div>
    </div>

@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

<script>
function showUpload() {
    document.getElementById('uploadBox').style.display = 'block';
    document.getElementById('webcamBox').style.display = 'none';
}

function showWebcam() {
    document.getElementById('uploadBox').style.display = 'none';
    document.getElementById('webcamBox').style.display = 'block';
}

Webcam.set({
    width: 320,
    height: 240,
    image_format: 'png',
    png_quality: 90
});

Webcam.attach('#camera');

function takeSnapshot() {
    Webcam.snap(function(dataURI) {
        document.getElementById('snapshotResult').innerHTML =
            '<img src="'+dataURI+'" width="320" class="img-thumbnail">';

        document.getElementById('webcamImageField').value = dataURI;
        document.getElementById('uploadBtn').style.display = 'block';
    });
}
</script>

@endpush