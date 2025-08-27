<table class="table table-hover align-middle text-nowrap" id="PatientPhysical">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Notes</th>
            <th>Created At</th>
            <th width="120px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($physicals as $index => $physical)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $physical->physical_notes }}</td>
                <td>{{ optional($physical->created_at)->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{guard_route('patients.physical.edit', [$patient->id, $physical->id]) }}" class="btn btn-primary btn-sm" title="Edit">
                        <i class="fa fa-pen"></i>
                    </a>
                    <form method="POST" action="{{guard_route('patients.physical.destroy', [$patient->id, $physical->id]) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
    
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No physicals found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{-- {!! $physicals->links('pagination::bootstrap-5') !!} --}}