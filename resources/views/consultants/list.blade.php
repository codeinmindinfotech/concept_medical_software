<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Code</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($consultants as $index => $consultant)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $consultant->code }} </td>
            <td>
                <form action="{{ route('consultants.destroy',$consultant->id) }}" method="POST">
                    <a class="btn btn-info btn-sm" href="{{ route('consultants.show',$consultant->id) }}" title="Show"><i class="fa-solid fa-list"></i></a>
                    @can('consultant-edit')
                    <a class="btn btn-primary btn-sm" href="{{ route('consultants.edit',$consultant->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    @endcan

                    @csrf
                    @method('DELETE')

                    @can('consultant-delete')
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
{!! $consultants->links('pagination::bootstrap-5') !!}