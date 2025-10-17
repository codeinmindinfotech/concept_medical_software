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
                    <a href="{{guard_route('patients.history.edit', [$patient->id, $history->id]) }}" class="btn btn-primary btn-sm" title="Edit">
                        <i class="fa fa-pen"></i>
                    </a>
                    <form method="POST" action="{{guard_route('patients.history.destroy', [$patient->id, $history->id]) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
    
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
{{-- {!! $historys->links('pagination::bootstrap-5') !!} --}}