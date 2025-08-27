<table class="table table-striped table-bordered" id="doctorTable">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($doctors as $i => $doctor)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $doctor->name }}</td>
            <td>
                <form action="{{guard_route('doctors.destroy',$doctor->id) }}" method="POST">
                    @usercan('doctor-list')
                        <a class="btn btn-info btn-sm" href="{{guard_route('doctors.show',$doctor->id) }}" title="Show">
                            <i class="fa-solid fa-eye text-white"></i>
                        </a>
                    @endusercan
                    @usercan('doctor-edit')
                        <a class="btn btn-primary btn-sm" href="{{guard_route('doctors.edit',$doctor->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    @endusercan


                    @csrf
                    @method('DELETE')

                    @usercan('doctor-delete')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    @endusercan
                </form>
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="3">There are no doctors.</td>
            </tr>
        @endforelse
    </tbody>    
</table>
{{-- {!! $doctors->links('pagination::bootstrap-5') !!} --}}