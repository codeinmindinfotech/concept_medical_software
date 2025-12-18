<table class="table table-bordered table-hover align-middle text-nowrap" id="PatientHistoryTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Notes</th>
            <th>Created At</th>
            <th width="120px" data-sortable="false">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($historys as $index => $history)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $history->history_notes }}</td>
                <td>{{ optional($history->created_at)->format('Y-m-d H:i') }}</td>
                <td>
                    @if(has_permission('patient-edit'))
                        <a href="{{guard_route('patients.history.edit', [$patient->id, $history->id]) }}" class="btn btn-sm bg-primary-light" title="Edit">
                            <i class="fe fe-pencil"></i> Edit
                        </a>
                    @endif
                    @if(has_permission('patient-delete'))
                        <form action="{{guard_route('patients.history.destroy', [$patient->id, $history->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
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