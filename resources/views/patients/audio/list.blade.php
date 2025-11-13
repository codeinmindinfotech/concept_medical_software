<table class="table table-hover align-middle text-nowrap" id="PatientAudioTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Audio</th>
            <th>Created At</th>
            <th width="120px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($audios as $index => $audio)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                @if($audio->file_path)
                    <audio controls>
                        <source src="{{ asset_url($audio->file_path) }}" type="audio/webm">
                        Your browser does not support the audio element.
                    </audio>
                @else
                    <span class="text-muted">No audio</span>
                @endif
            </td>
            
                           
            <td>{{ format_date($audio->created_at) }}</td>
            <td>
                <button 
                    type="button" 
                    class="btn btn-info btn-sm me-1 show-transcription-btn" 
                    data-bs-toggle="modal" 
                    data-bs-target="#transcriptionModal"
                    data-transcription="{{ e($audio->transcription) }}"
                    data-title="Transcription #{{ $index + 1 }}"
                >
                    <i class="fa-solid fa-align-left"></i> Transcription
                </button>
                <form method="POST" action="{{guard_route('patients.audio.destroy', [$patient->id, $audio->id]) }}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
