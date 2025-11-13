<table class="datatable table table-hover table-center mb-0" id="DropdownValueTable">
    <thead>
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Value</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($values as $index => $val)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td><a href="{{guard_route('dropdowns.edit', [$dropDownId]) }}" >{{ $val->dropdown->name??'' }}</a></td>
            <td>{{ $val->value }}</td>
            <td>
                @can('dropdownvalue-edit')
                <a href="{{guard_route('dropdownvalues.edit', [$val->id, $dropDownId]) }}" class="btn btn-primary btn-sm" title="Edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>    
</table>
{{-- {{ $values->appends(['dropDownId' => $dropDownId])->links('pagination::bootstrap-5') }} --}}