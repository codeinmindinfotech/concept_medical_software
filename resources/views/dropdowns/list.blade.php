<table class="datatable table table-hover table-center mb-0" id="DropdownTable">
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
                <form action="{{guard_route('dropdowns.destroy',$dropdown->id) }}" method="POST">
                    @can('dropdown-edit')
                    <a class="btn btn-primary btn-sm" href="{{guard_route('dropdowns.edit',$dropdown->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    @endcan

                    @can('dropdown-create')
                        <a class="btn btn-success btn-sm" href="{{guard_route('dropdownvalues.create',$dropdown->id) }}" title="Add Value"><i class="fa-solid fa-plus"></i></a>
                    @endcan

                    {{-- @csrf
                    @method('DELETE')

                    @can('dropdown-delete')
                    <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    @endcan --}}
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>    
</table>
{{-- {!! $dropdowns->links('pagination::bootstrap-5') !!} --}}
