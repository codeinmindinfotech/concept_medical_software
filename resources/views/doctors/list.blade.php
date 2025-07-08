<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($doctors as $i => $doctor)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $doctor->name }}</td>
            <td>
                <form action="{{ route('doctors.destroy',$doctor->id) }}" method="POST">
                    @can('view', $doctor)
                        <a class="btn btn-info btn-sm" href="{{ route('doctors.show',$doctor->id) }}" title="Show"><i class="fa-solid fa-list text-white"></i></a>
                    @endcan
                    @can('update', $doctor)
                        <a class="btn btn-primary btn-sm" href="{{ route('doctors.edit',$doctor->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    @endcan


                    @csrf
                    @method('DELETE')

                    @can('delete', $doctor)
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    @endcan
                </form>
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="3">There are no doctors.</td>
            </tr>
        @endforelse
    </tbody>    
</table>
{!! $doctors->links('pagination::bootstrap-5') !!}