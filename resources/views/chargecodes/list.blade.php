<table class="table table-bordered data-table" id="ChargeCodeTable">
    <thead class="table-dark">
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
        @forelse ($chargecodes as $i => $chargecode)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $chargecode->code }}</td>
            <td>{{ $chargecode->description }}</td>
            <td>{{ $chargecode->chargeGroup->value ?? '-' }}</td>
            <td>{{ $chargecode->vatrate }}%</td>
            <td>{{ number_format($chargecode->price, 2) }}</td>
            <td>
                <form action="{{guard_route('chargecodes.destroy',$chargecode->id) }}" method="POST">
                    @can('view', $chargecode)
                        <a class="btn btn-info btn-sm" href="{{guard_route('chargecodes.show',$chargecode->id) }}" title="Show">
                            <i class="fa-solid fa-eye text-white"></i>
                        </a>
                    @endcan
                    @can('update', $chargecode)
                        <a class="btn btn-primary btn-sm" href="{{guard_route('chargecodes.edit',$chargecode->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    @endcan


                    @csrf
                    @method('DELETE')

                    @can('delete', $chargecode)
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    @endcan
                </form>
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="7">There are no chargecodes.</td>
            </tr>
        @endforelse
    </tbody>    
</table>
{{-- {!! $chargecodes->appends(request()->query())->links('pagination::bootstrap-5') !!} --}}