<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th style="width: 50px;">No</th>
            <th>Name</th>
            <th style="width: 100px;">Notes</th>
            <th style="width: 150px;">Physical Exam</th>
            <th >Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($patients as $index => $patient)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ optional($patient->title)->value ? $patient->title->value . ' ' : '' }}
                {{ $patient->first_name }} {{ $patient->surname }}</td>
            <td>
                @can('view', $patient)
                <a class="btn btn-success btn-sm" href="{{ route('patients.notes.index', $patient->id) }}" title="View Notes">
                    <i class="fa-solid fa-notes-medical"></i>
                </a>
                @endcan
            </td>
            <td>
                @can('view', $patient)
                <a class="btn btn-secondary btn-sm" href="{{ route('patients.physical.index', $patient->id) }}" title="Physical Exams">
                    <i class="fas fa-book-open"></i>
                </a>
                @endcan
            </td>
            <td>
                <form action="{{ route('patients.destroy',$patient->id) }}" method="POST">

                <a class="btn btn-info btn-sm" href="{{ route('patients.show',$patient->id) }}" title="Show"><i class="fa-solid fa-list"></i></a>
                @can('update', $patient)
                <a class="btn btn-primary btn-sm" href="{{ route('patients.edit',$patient->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                @endcan

                    @csrf
                    @method('DELETE')
                    @can('delete', $patient)
                    <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    @endcan
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3">There are no Patients.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{!! $patients->links('pagination::bootstrap-5') !!}
