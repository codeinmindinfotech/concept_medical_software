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
                    <span class="badge bg-{{ $note->completed ? 'success' : 'warning' }}">
                        {{ $note->completed ? 'Yes' : 'No' }}
                    </span>
                </td>
                <td>{{ $note->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('patients.notes.edit', [$patient->id, $note->id]) }}" class="btn btn-primary btn-sm" title="Edit">
                        <i class="fa fa-pen"></i>
                    </a>
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