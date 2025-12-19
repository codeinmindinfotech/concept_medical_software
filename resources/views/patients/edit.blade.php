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
<div class="card-header d-flex justify-content-between align-items-center">
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
    {{-- <a href="{{guard_route('patients.index') }}" class="btn bg-primary text-white btn-light btn-sm">
        <i class="fas fa-plus-circle me-1"></i> Patient List
    </a> --}}
</div>
    
    <div class="tab-content" id="myTabContent">
        
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
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
            <form action="{{guard_route('patients.update', $patient->id) }}" method="POST" data-ajax class="needs-validation" novalidate>
                @csrf
                @method('PUT')
            
                @include('patients.form', [
                    'patient' => $patient,
                    'insurances' => $insurances,
                    'doctors' => $doctors,
                    'titles' => $titles
                    ])
            </form>
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
                        <div id="webcamBox"  class="col-md-6" style="display:none;">
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
@endsection
@push('scripts')
<script src="{{ URL::asset('/assets/js/signature.js') }}"></script>
<script src="{{ URL::asset('/assets/js/webcam.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/webcam.js') }}"></script>
@endpush