<table class="table table-hover table-center mb-0" id="DropdownTable">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dropdowns as $i => $dropdown)
        <tr>
            <td>{{ ++$i }}</td>
            <td>
                <a class="btn-sm" href="{{guard_route('dropdownvalues.index',$dropdown->id) }}" title="{{ $dropdown->name }}">
                    {{ $dropdown->name }}
                </a>
            </td>
            <td>
                @can('dropdown-edit')
                <a class="btn btn-sm bg-primary-light" href="{{guard_route('dropdowns.edit',$dropdown->id) }}">
                    <i class="fe fe-pencil"></i> Edit
                </a>
                @endcan
                @can('dropdown-create')
                <form action="{{guard_route('dropdowns.destroy', $dropdown->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this dropdown?');">
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