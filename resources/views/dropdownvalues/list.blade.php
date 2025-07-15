<table class="table table-bordered data-table" id="DropdownValueTable">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Value</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($values as $index => $val)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td><a href="{{ route('dropdowns.edit', [$dropDownId]) }}" >{{ $val->dropdown->name??'' }}</a></td>
            <td>{{ $val->value }}</td>
            <td>
                @can('dropdownvalue-edit')
                <a href="{{ route('dropdownvalues.edit', [$val->id, $dropDownId]) }}" class="btn btn-primary btn-sm" title="Edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                @endcan
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4">No values found.</td>
        </tr>
        @endforelse
    </tbody>    
</table>
{{-- {{ $values->appends(['dropDownId' => $dropDownId])->links('pagination::bootstrap-5') }} --}}