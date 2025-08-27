<table class="table table-hover align-middle text-nowrap" id="ClinicTable">
    <thead class="table-dark">
        <tr>
            <th style="width: 50px;">#</th>
            <th>Code</th>
            <th>Name</th>
            <th>Type</th>
            <th>Phone</th>
            <th>Planner Sequence</th>
            <th style="width: 220px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($clinics as $index => $clinic)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $clinic->code }}</td>
                <td>{{ $clinic->name }}</td>
                <td>
                    @switch($clinic->clinic_type)
                        @case('clinic')
                            Clinic
                            @break
                        @case('hospital')
                            Hospital
                            @break
                        @default
                            Unknown
                    @endswitch
                </td>
                <td>{{ $clinic->phone ?? '-' }}</td>
                <td>{{ $clinic->planner_seq }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Clinic Actions">
                        <a href="{{ guard_route('clinics.show', $clinic->id) }}" class="btn btn-info btn-sm" title="View">
                            <i class="fa-solid fa-eye text-white"></i>
                        </a>

                        @can('clinic-edit')
                            <a href="{{ guard_route('clinics.edit', $clinic->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        @endcan

                        @can('clinic-delete')
                            <form action="{{ guard_route('clinics.destroy', $clinic->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this clinic?');">
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
                <td colspan="7" class="text-center text-muted">No clinics found.</td>
            </tr>
        @endforelse
    </tbody>
</table>