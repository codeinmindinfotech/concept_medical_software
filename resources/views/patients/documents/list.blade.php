<table class="table table-hover align-middle text-nowrap" id="PatientDocument">
    <thead>
        <tr>
            <th>#</th>
            <th>Template</th>
            <th>Created At</th>
            <th width="120px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($documents as $index => $doc)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $doc->template->name }}</td>
                <td>{{ $doc->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ guard_route('patient-documents.edit', [$patient, $doc]) }}" class="btn btn-sm bg-primary-light" title="Edit">
                        <i class="fe fe-pencil"></i> Edit
                    </a>
                    <form action="{{ guard_route('patient-documents.destroy', [$patient, $doc]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
                    </form>
                    <a href="{{ guard_route('patient-documents.email.form', [$patient, $doc]) }}" class="btn btn-sm bg-warning-light">
                        <i class="fa-solid fa-envelope"></i></a>                    
                </td>

            </tr>
        @endforeach
    </tbody>
</table>