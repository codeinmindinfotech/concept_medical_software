<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Method</th>
            <th>Notes</th>
            <th>Completed</th>
            <th>Created At</th>
            <th width="120px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($notes as $index => $note)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ ucfirst($note->method) }}</td>
                <td>{{ $note->notes }}</td>
                <td>
                    <span class="badge bg-{{ $note->completed ? 'success' : 'warning' }} toggle-completed"
                        data-url="{{ route('patients.notes.toggleCompleted', [$patient->id, $note->id]) }}"
                        style="cursor: pointer;">
                      {{ $note->completed ? 'Yes' : 'No' }}
                  </span>  
                </td>
                <td>{{ $note->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('patients.notes.edit', [$patient->id, $note->id]) }}" class="btn btn-primary btn-sm" title="Edit">
                        <i class="fa fa-pen"></i>
                    </a>
                    <form method="POST" action="{{ route('patients.notes.destroy', [$patient->id, $note->id]) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
    
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No notes found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
{!! $notes->links('pagination::bootstrap-5') !!}