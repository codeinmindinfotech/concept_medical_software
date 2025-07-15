<table class="table table-bordered data-table" id="DropdownTable">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($dropdowns as $i => $dropdown)
        <tr>
            <td>{{ ++$i }}</td>
            <td>
                <a class="btn-sm" href="{{ route('dropdownvalues.index',$dropdown->id) }}" title="{{ $dropdown->name }}">
                    {{ $dropdown->name }}
                </a>
            </td>
            <td>
                <form action="{{ route('dropdowns.destroy',$dropdown->id) }}" method="POST">
                    @can('dropdown-edit')
                    <a class="btn btn-primary btn-sm" href="{{ route('dropdowns.edit',$dropdown->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    @endcan

                    @can('dropdown-create')
                        <a class="btn btn-success btn-sm" href="{{ route('dropdownvalues.create',$dropdown->id) }}" title="Add Value"><i class="fa-solid fa-plus"></i></a>
                    @endcan

                    {{-- @csrf
                    @method('DELETE')

                    @can('dropdown-delete')
                    <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    @endcan --}}
                </form>
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="3">There are no dropdowns.</td>
            </tr>
        @endforelse
    </tbody>    
</table>
{{-- {!! $dropdowns->links('pagination::bootstrap-5') !!} --}}
