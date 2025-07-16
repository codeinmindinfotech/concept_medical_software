<table class="table table-hover align-middle text-nowrap" id="AudioTable">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Audio</th>
            <th>Created At</th>
            <th width="120px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($audios as $index => $audio)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                <audio controls>
                    <source src="{{ Storage::url($audio->file_path) }}" type="audio/webm">
                    Your browser does not support the audio element.
                </audio>
            </td>
            <td>{{ format_date($audio->created_at) }}</td>
            <td>
                <form method="POST" action="{{ route('patients.audio.destroy', [$patient->id, $audio->id]) }}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No audio found.</td>
            </tr>
        @endforelse
    </tbody>
</table>