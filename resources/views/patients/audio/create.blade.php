@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Patients', 'url' => route('patients.index')],
            ['label' => 'Patients Audio Recording List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Patients Audio Recording List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('patients.audio.index', $patient->id),
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
            <form id="audioForm" action="{{ route('patients.audio.store', $patient->id) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label for="patientName" class="form-label"><strong>Patient Name</strong></label>
                    <input type="text" id="patientName" class="form-control" value="{{ $patient->surname ?? 'N/A' }}" disabled>
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