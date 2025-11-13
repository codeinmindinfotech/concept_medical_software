<table class="datatable table table-hover table-center mb-0" id="doctorTable">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($doctors as $i => $doctor)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $doctor->name }}</td>
            <td>
                <form action="{{guard_route('doctors.destroy',$doctor->id) }}" method="POST">
                    @can('view', $doctor)
                        <a class="btn btn-info btn-sm" href="{{guard_route('doctors.show',$doctor->id) }}" title="Show">
                            <i class="fa-solid fa-eye text-white"></i>
                        </a>
                    @endcan
                    @can('update', $doctor)
                        <a class="btn btn-primary btn-sm" href="{{guard_route('doctors.edit',$doctor->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    @endcan


                    @csrf
                    @method('DELETE')

                    @can('delete', $doctor)
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    @endcan
                </form>
            </td>
        </tr>
       @endforeach
    </tbody>    
</table>
{{-- {!! $doctors->links('pagination::bootstrap-5') !!} --}}