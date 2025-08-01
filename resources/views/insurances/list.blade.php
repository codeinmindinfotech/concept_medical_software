<table class="table table-bordered data-table" id="InsuranceTable">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Code</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($insurances as $index => $insurance)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $insurance->code }} </td>
            <td>
                <form action="{{ route('insurances.destroy',$insurance->id) }}" method="POST">
                    <a class="btn btn-info btn-sm" href="{{ route('insurances.show',$insurance->id) }}" title="Show"><i class="fa-solid fa-eye text-white"></i></a>
                    @can('insurance-edit')
                    <a class="btn btn-primary btn-sm" href="{{ route('insurances.edit',$insurance->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    @endcan

                    @csrf
                    @method('DELETE')

                    @can('insurance-delete')
                    <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    @endcan
                </form>
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="3">There are no Patients.</td>
            </tr>
        @endforelse
    </tbody>    
</table>
{{-- {!! $insurances->links('pagination::bootstrap-5') !!} --}}