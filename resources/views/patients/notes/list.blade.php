<table class="table table-hover align-middle text-nowrap" id="PatientNote">
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
        @foreach ($notes as $index => $note)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ ucfirst($note->method) }}</td>
                <td>{{ $note->notes }}</td>
                <td>
                    <span class="badge bg-{{ $note->completed ? 'success' : 'warning' }} toggle-completed"
                        data-url="{{guard_route('patients.notes.toggleCompleted', [$patient->id, $note->id]) }}"
                        style="cursor: pointer;">
                      {{ $note->completed ? 'Yes' : 'No' }}
                  </span>  
                </td>
                <td>{{ $note->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    @if(has_permission('patient-edit'))
                        <a href="{{guard_route('patients.notes.edit', [$patient->id, $note->id]) }}" class="btn btn-sm bg-primary-light" title="Edit">
                            <i class="fe fe-pencil"></i> Edit
                        </a>
                    @endif
                    @if(has_permission('patient-delete'))
                    <form action="{{guard_route('patients.notes.destroy', [$patient->id, $note->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
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