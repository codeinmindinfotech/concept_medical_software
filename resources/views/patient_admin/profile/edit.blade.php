@extends('layout.mainlayout')

@section('content')

{{-- @component('components.admin.breadcrumb')
@slot('title') Edit History @endslot
@slot('li_1') Patients @endslot
@slot('li_2') Edit @endslot
@endcomponent --}}

<div class="content">
    <div class="container pt-3">

        <div class="row">
            <div class="col-lg-9 col-xl-10">
                <div class="card mb-4 shadow-sm p-3">
                    <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" role="tab">
                                    <i class="fas fa-user me-1"></i>Patient Management
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab">
                                    <i class="fas fa-camera me-1"></i>Upload Picture
                                </a>
                            </li>
                        </ul>

                        {{-- <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Patient Management
                        </h5> --}}
                        <a href="{{guard_route('patients.index') }}" class="btn bg-primary text-white btn-light btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> Patient List
                        </a>
                    </div>
                    <div class="tab-content" id="myTabContent">

                        {{-- Patient Information --}}
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                            <div class="card-body">
                                <form action="{{guard_route('patients.update', $patient->id) }}" method="POST" data-ajax class="needs-validation" novalidate>

                                    @csrf
                                    @method('PUT')

                                    {{-- Reuse SAME patient form --}}
                                    @include('patients.form', [
                                    'patient' => $patient,
                                    'insurances' => $insurances,
                                    'doctors' => $doctors,
                                    'titles' => $titles,
                                    'consultants' => $consultants,
                                    'relations' => $relations,
                                    ])

                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="card-body">
                                <div class="btn-group mb-3" role="group">
                                    <button class="btn btn-outline-primary" onclick="showUpload()">Upload Image</button>
                                    <button class="btn btn-outline-success" onclick="showWebcam()">Use Webcam</button>
                                </div>
                                <div class="row">
                                    <!-- Upload from Computer -->
                                    <div id="uploadBox" class="col-md-6" style="display:none;">
                                        <form id="browseUploadForm" action="{{ guard_route('patients.upload-picture', $patient->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                                            <label>Select Image:</label>
                                            <input type="file" name="patient_picture" class="form-control" accept="image/*">
                                            <button class="btn btn-primary mt-3">Upload</button>
                                        </form>
                                    </div>

                                    <!-- Webcam Capture -->
                                    <div id="webcamBox" class="col-md-6" style="display:none;">
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

                                    <!-- Existing Signature -->
                                    @if(!empty($patient->patient_picture))
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Existing:</strong></label>
                                        <div>
                                            <img src="{{ asset('storage/' . $patient->patient_picture) }}" width="150" height="100" class="img-thumbnail">
                                        </div>
                                    </div>
                                    @endif

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Patient Dashboard Sidebar --}}
            @component('components.admin.tab-navigation', ['patient' => $patient])
            @endcomponent
        </div>

    </div>
</div>

@endsection
@push('scripts')
<script src="{{ URL::asset('/assets/js/signature.js') }}"></script>
<script src="{{ URL::asset('/assets/js/webcam.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/webcam.js') }}"></script>
@endpush
