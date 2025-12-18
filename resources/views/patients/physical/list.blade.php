<table class="table table-hover align-middle text-nowrap" id="PatientPhysical">
    <thead>
        <tr>
            <th>#</th>
            <th>Notes</th>
            <th>Created At</th>
            <th width="120px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($physicals as $index => $physical)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $physical->physical_notes }}</td>
                <td>{{ optional($physical->created_at)->format('Y-m-d H:i') }}</td>
                <td>
                    @if(has_permission('patient-edit'))
                    <a href="{{guard_route('patients.physical.edit', [$patient->id, $physical->id]) }}" class="btn btn-sm bg-primary-light" title="Edit">
                        <i class="fe fe-pencil"></i> Edit
                    </a>
                    @endif
                    @if(has_permission('patient-delete'))
                    <form action="{{guard_route('patients.physical.destroy', [$patient->id, $physical->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this physical?');">
                        @csrf
                        @method('DELETE')
    
                        <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>