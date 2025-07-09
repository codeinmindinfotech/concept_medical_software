<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th width="100px">No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>    
    @forelse($roles as $key => $role)
        <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $role->name }}</td>
            <td>
                <a class="btn btn-info btn-sm" href="{{ route('roles.show',$role->id) }}" title="Show"><i class="fa-solid fa-eye text-white"></i></a>
                @can('role-edit')
                    <a class="btn btn-primary btn-sm" href="{{ route('roles.edit',$role->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                @endcan

                @can('role-delete')
                <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                </form>
                @endcan
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3">There are no Patients.</td>
        </tr>
    @endforelse
    </tbody>    
</table>