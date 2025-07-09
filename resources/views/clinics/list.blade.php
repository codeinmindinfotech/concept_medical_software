<table class="table table-striped table-hover align-middle">
    <thead class="table-primary">
        <tr>
            <th style="width: 50px;">#</th>
            <th>Code</th>
            <th>Name</th>
            <th>Type</th>
            <th>Phone</th>
            <th style="width: 220px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($clinics as $index => $clinic)
            <tr>
                <td>{{ $clinics->firstItem() + $index }}</td>
                <td>{{ $clinic->code }}</td>
                <td>{{ $clinic->name }}</td>
                <td>{{ ucfirst($clinic->clinic_type) ?? '-' }}</td>
                <td>{{ $clinic->phone ?? '-' }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Clinic Actions">
                        <a href="{{ route('clinics.show', $clinic->id) }}" class="btn btn-info btn-sm" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        @can('clinic-edit')
                            <a href="{{ route('clinics.edit', $clinic->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        @endcan

                        @can('clinic-delete')
                            <form action="{{ route('clinics.destroy', $clinic->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this clinic?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No clinics found.</td>
            </tr>
        @endforelse
    </tbody>
</table>