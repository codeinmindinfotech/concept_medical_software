<table class="table table-hover align-middle text-nowrap" id="AudioTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Patient Name</th>
            <th>Doctor Name</th>
            <th>Created At</th>
            <th width="120px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($audios as $index => $audio)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                @if($audio->patient)
                    {{ $audio->patient->full_name }}
                @else
                    <span class="text-muted">Not Assigned</span>
                @endif
            </td>
            <td>
                @if($audio->doctor)
                    Dr. {{ $audio->doctor->name }}
                @else
                    <span class="text-muted">Not Assigned</span>
                @endif
            </td>
            <td>{{ format_date($audio->created_at) }}</td>
            <td>
                <a class="btn btn-info btn-sm" href="{{guard_route('audios.show',$audio->id) }}" title="Show">
                    <i class="fa-solid fa-eye text-white"></i>
                </a>
                <a class="btn btn-primary btn-sm" href="{{guard_route('audios.edit',$audio->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>

                <form method="POST" action="{{guard_route('audios.destroy', [$audio->id]) }}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>