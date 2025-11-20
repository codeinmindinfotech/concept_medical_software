<table class="table table-hover table-center mb-0" id="ChargeCodeTable">
    <thead>
        <tr>
            <th>No</th>
            <th>Code</th>
            <th>Description</th>
            <th>Type</th>
            <th>VAT Rate</th>
            <th>Price</th>
            <th width="200px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($chargecodes as $i => $chargecode)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $chargecode->code }}</td>
            <td>{{ $chargecode->description }}</td>
            <td>{{ $chargecode->chargeGroup->value ?? '-' }}</td>
            <td>{{ $chargecode->vatrate }}%</td>
            <td>{{ number_format($chargecode->price, 2) }}</td>
            <td>
                @can('view', $chargecode)
                <a class="btn btn-sm bg-success-light" href="{{guard_route('chargecodes.show',$chargecode->id) }}" title="Show">
                    <i class="fe fe-eye"></i> Show
                </a>
                @endcan
                @can('update', $chargecode)
                <a class="btn btn-sm bg-primary-light" href="{{guard_route('chargecodes.edit',$chargecode->id) }}" title="Edit">
                    <i class="fe fe-pencil"></i> Edit
                </a>
                @endcan

                @can('delete', $chargecode)

                <form action="{{guard_route('chargecodes.destroy', $chargecode->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this chargecode?');">
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