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
        @foreach ($documents as $index => $doc)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $doc->template->name }}</td>
                <td>{{ $doc->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ guard_route('patient-documents.edit', [$patient, $doc]) }}" class="btn btn-sm btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
                    <form action="{{ guard_route('patient-documents.destroy', [$patient, $doc]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                    <a href="{{ guard_route('patient-documents.email.form', [$patient, $doc]) }}" class="btn btn-sm btn-success"><i class="fa-solid fa-envelope"></i></a>
                    <a href="{{ guard_route('patient-documents.download-pdf', [$patient, $doc]) }}" class="btn btn-sm btn-success"><i class="fa-solid fa-download"></i></a>
                    
                </td>

            </tr>
        @endforeach
    </tbody>
</table>