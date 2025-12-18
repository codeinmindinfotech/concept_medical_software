@extends('layout.mainlayout')

@section('content')

{{-- @component('components.admin.breadcrumb')
@slot('title') Edit History @endslot
@slot('li_1') Patients @endslot
@slot('li_2') Edit @endslot
@endcomponent --}}

<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-lg-9 col-xl-10">

                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Audio Management
                        </h5>
                        <a href="{{guard_route('patients.audio.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> New Audio
                        </a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-microphone"></i> Patients Consultation Management
                            </div>
                            <div class="card-body">
                                <!-- Add Loader Here -->
                                <div id="uploadLoader" class="text-center my-3" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Uploading...</span>
                                    </div>
                                    <p class="mt-2">Uploading & transcribing audio. Please wait...</p>
                                </div>
                                <form id="audioForm" action="{{guard_route('patients.audio.store', $patient->id) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="patientName" class="form-label"><strong>Patient Name</strong></label>
                                        <input type="text" id="patientName" class="form-control" value="{{ $patient->full_name ?? 'N/A' }}" disabled>
                                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    </div>
                    
                                    <div class="mb-4">
                                        <label for="audioFileInput" class="form-label"><strong>Recorded Audio</strong></label>
                                        <input type="file" name="file_path" id="audioFileInput" accept="audio/*" hidden required>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" id="startRecording" class="btn btn-success">
                                                <i class="fas fa-circle"></i> Start Recording
                                            </button>
                                            <button type="button" id="stopRecording" class="btn btn-danger" disabled>
                                                <i class="fas fa-stop"></i> Stop Recording
                                            </button>
                                            <button type="submit" id="uploadBtn" class="btn btn-primary" disabled>
                                                <i class="fas fa-upload"></i> Upload Recording
                                            </button>
                                        </div>
                                    </div>
                                </form>
                    
                                <div>
                                    <audio id="audioPlayback" controls class="w-100" style="max-height: 80px;"></audio>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>           
            
          

            </div>
            <!-- Profile Sidebar -->
            @component('components.admin.tab-navigation', ['patient' => $patient])
            @endcomponent
        </div>

    </div>
</div>

@endsection
@push('scripts')
<script>
  const startBtn = document.getElementById('startRecording');
  const stopBtn = document.getElementById('stopRecording');
  const audioPlayback = document.getElementById('audioPlayback');
  const audioFileInput = document.getElementById('audioFileInput');
  const uploadBtn = document.getElementById('uploadBtn');

  let mediaRecorder;
  let audioChunks = [];

  const form = document.getElementById('audioForm');
  const loader = document.getElementById('uploadLoader');

  form.addEventListener('submit', function (e) {
      loader.style.display = 'block';
      uploadBtn.disabled = true;
      startBtn.disabled = true;
      stopBtn.disabled = true;
  });

  startBtn.addEventListener('click', async () => {
      try {
          const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
          mediaRecorder = new MediaRecorder(stream);

          mediaRecorder.start();
          audioChunks = [];

          mediaRecorder.addEventListener('dataavailable', e => {
              audioChunks.push(e.data);
          });

          mediaRecorder.addEventListener('stop', () => {
              const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
              const audioUrl = URL.createObjectURL(audioBlob);
              audioPlayback.src = audioUrl;

              // Convert blob to file and assign to hidden input
              const file = new File([audioBlob], 'recording.webm', { type: 'audio/webm' });
              const dt = new DataTransfer();
              dt.items.add(file);
              audioFileInput.files = dt.files;

              uploadBtn.disabled = false;
          });

          startBtn.disabled = true;
          stopBtn.disabled = false;
          uploadBtn.disabled = true;
      } catch (err) {
          alert('Could not start recording: ' + err.message);
      }
  });

  stopBtn.addEventListener('click', () => {
      if(mediaRecorder) {
          mediaRecorder.stop();
          startBtn.disabled = false;
          stopBtn.disabled = true;
      }
  });
</script>
@endpush