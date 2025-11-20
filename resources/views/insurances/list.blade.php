<table class="datatable table table-hover table-center mb-0" id="InsuranceTable">
    <thead>
        <tr>
            <th>No</th>
            <th>Code</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($insurances as $index => $insurance)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $insurance->code }} </td>
            <td>


                <a class="btn btn-sm bg-success-light" href="{{guard_route('insurances.show',$insurance->id) }}" title="Show">
                    <i class="fe fe-eye"></i> Show
                </a>
                @can('insurance-edit')
                <a class="btn btn-sm bg-primary-light" href="{{guard_route('insurances.edit',$insurance->id) }}" title="Edit">
                    <i class="fe fe-pencil"></i> Edit
                </a>
                @endcan

                @can('insurance-delete')

                <form action="{{guard_route('insurances.destroy', $insurance->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this insurance?');">
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