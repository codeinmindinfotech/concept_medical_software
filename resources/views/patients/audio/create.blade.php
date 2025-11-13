@extends('backend.theme.tabbed')

@section('tab-navigation')
    @include('backend.theme.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patients.index')],
            ['label' => 'Patients Audio Recording List'],
        ];
    @endphp

    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Patients Audio Recording List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('patients.audio.index', $patient->id),
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