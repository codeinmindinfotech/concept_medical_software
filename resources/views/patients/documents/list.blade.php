<table class="table table-hover align-middle text-nowrap" id="PatientDocument">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Template</th>
            <th>Created At</th>
            <th width="120px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($documents as $index => $doc)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $doc->template->name }}</td>
                <td>{{ $doc->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ guard_route('patient-documents.edit', [$patient, $doc]) }}" class="btn btn-sm btn-success">Edit</a>
                    <form action="{{ guard_route('patient-documents.destroy', [$patient, $doc]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
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