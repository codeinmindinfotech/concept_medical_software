@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container pt-3">

        <div class="row">
            <!-- General -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Doctor Management
                    </h5>
                   @can('viewAny', \App\Models\Doctor::class)
                    <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('doctors.index') }}">
                        <i class="fas fa-plus-circle me-1"></i> List Doctor
                    </a>
                    @endcan
                </div>
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" role="tab">
                            <i class="fas fa-user me-1"></i>Doctor Management
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab">
                            <i class="fas fa-camera me-1"></i>Upload Picture
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    {{-- Patient Information --}}
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card-body">
                            <form action="{{guard_route('doctors.update', $doctor->id) }}" method="POST"
                                class="needs-validation" novalidate data-ajax>
                                @csrf
                                @method('PUT')

                                @include('doctors.form', [
                                'doctor' => $doctor,
                                'contactTypes' => $contactTypes,
                                'paymentMethods' => $paymentMethods
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
                                    <form id="browseUploadForm"
                                        action="{{ guard_route('doctor.upload-picture', $doctor->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                                        <label>Select Image:</label>
                                        <input type="file" name="doctor_picture" class="form-control" accept="image/*">
                                        <button class="btn btn-primary mt-3">Upload</button>
                                    </form>
                                </div>

                                <!-- Webcam Capture -->
                                <div id="webcamBox" class="col-md-6" style="display:none;">
                                    <div id="camera" style="width:320px;height:240px;border:1px solid #ccc;"></div>

                                    <button onclick="takeSnapshot()" class="btn btn-success mt-2">Capture</button>

                                    <div id="snapshotResult" class="mt-3"></div>

                                    <form id="webcamForm"
                                        action="{{ guard_route('doctor.upload-picture', $doctor->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                                        <input type="hidden" name="doctor_picture_webcam" id="webcamImageField">

                                        <button type="submit" class="btn btn-primary mt-3" style="display:none;"
                                            id="uploadBtn">
                                            Upload Captured Image
                                        </button>
                                    </form>
                                </div>

                                <!-- Existing Signature -->
                                @if(!empty($doctor->doctor_picture))
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Existing:</strong></label>
                                    <div>
                                        <img src="{{ asset('storage/' . $doctor->doctor_picture) }}" width="150"
                                            height="100" class="img-thumbnail">
                                    </div>
                                </div>
                                @endif

                            </div>

                        </div>
                    </div>
                    <!-- /General -->
                </div>
            </div>


        </div>

    </div>
</div>

@endsection
@push('scripts')
<script src="{{ URL::asset('/assets/js/signature.js') }}"></script>
<script src="{{ URL::asset('/assets/js/webcam.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/webcam.js') }}"></script>
@endpush