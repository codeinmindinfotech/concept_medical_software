<table class="table table-bordered data-table" id="ConsultantTable">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Code</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($consultants as $index => $consultant)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $consultant->code }} </td>
            <td>
                <form action="{{ guard_route('consultants.destroy',$consultant->id) }}" method="POST">
                    @can('view', $consultant)
                        <a class="btn btn-info btn-sm" href="{{ guard_route('consultants.show',$consultant->id) }}" title="Show"><i class="fa-solid fa-eye text-white"></i></a>
                    @endcan

                    @can('update', $consultant)
                        <a class="btn btn-primary btn-sm" href="{{ guard_route('consultants.edit',$consultant->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    @endcan

                    @csrf
                    @method('DELETE')

                    @can('delete', $consultant)
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
{{-- {!! $consultants->links('pagination::bootstrap-5') !!} --}}