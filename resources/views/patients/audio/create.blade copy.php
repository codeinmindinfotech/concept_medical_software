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

    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-history"></i>
            Patients Audio Recording  Management
        </div>
        <div class="card-body">

          
@if(session('success'))
<div style="color: green">{{ session('success') }}</div>
@endif
<form id="audioForm" action="{{ route('patients.audio.store', $patient->id) }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="col-md-6">
    <label class="form-label"><strong>Patient</strong></label>
    <input type="text" class="form-control" 
      value="{{ $patient->surname ?? 'N/A' }}" disabled>
    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
  </div>

<input type="file" name="file_path" id="audioFileInput" accept="audio/*" hidden required>
<button type="submit" id="uploadBtn" disabled>Upload Recording</button>
</form>

<button id="startRecording">Start Recording</button>
<button id="stopRecording" disabled>Stop Recording</button>
<audio id="audioPlayback" controls></audio>

@if ($errors->any())
    <div>
        <ul>
        @foreach ($errors->all() as $error)
            <li style="color:red;">{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif

 
          

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
  });

  stopBtn.addEventListener('click', () => {
      mediaRecorder.stop();
      startBtn.disabled = false;
      stopBtn.disabled = true;
  });
</script>

@endpush

