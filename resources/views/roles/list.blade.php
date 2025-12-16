<table class="table table-hover table-center mb-0" id="RoleTable">
    <thead>
        <tr>
            <th width="100px">No</th>
            <th>Name</th>
            <th>Company Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>    
    @foreach($roles as $key => $role)
        <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $role->name }}</td>
            <td>{{ $role->company_name }}</td>
            <td>
                <a class="btn btn-sm bg-success-light" href="{{guard_route('roles.show',$role->id) }}" title="Show"><i class="fe fe-eye"></i> Show</a>
                @can('role-edit')
                    <a class="btn btn-sm bg-primary-light" href="{{guard_route('roles.edit',$role->id) }}" title="Edit"><i class="fe fe-pencil"></i> Edit</a>
                @endcan

                @can('role-delete')
                <form method="POST" action="{{guard_route('roles.destroy', $role->id) }}" style="display:inline">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
                </form>
                @endcan
            </td>
        </tr>
    @endforeach
    </tbody>    
</table>