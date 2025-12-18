<table class="table table-hover align-middle text-nowrap" id="ClinicTable">
    <thead>
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
        @foreach ($clinics as $index => $clinic)
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
                    <a class="btn btn-sm bg-success-light" href="{{guard_route('clinics.show',$clinic->id) }}">
                        <i class="fe fe-eye"></i> Show
                    </a>
                    @can('clinic-edit')
                    <a class="btn btn-sm bg-primary-light" href="{{guard_route('clinics.edit',$clinic->id) }}">
                        <i class="fe fe-pencil"></i> Edit
                    </a>
                    @endcan
                    @can('clinic-delete')
                    <form action="{{guard_route('clinics.destroy', $clinic->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this clinic?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
                    </form>
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
</table>