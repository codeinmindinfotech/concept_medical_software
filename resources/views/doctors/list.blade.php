<table class="table table-hover table-center mb-0" id="DoctorTable">
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
                @can('view', $doctor)
                <a class="btn btn-sm bg-success-light" href="{{guard_route('doctors.show',$doctor->id) }}">
                    <i class="fe fe-eye"></i> Show
                </a>
                @endcan
                @can('update', $doctor)
                <a class="btn btn-sm bg-primary-light" href="{{guard_route('doctors.edit',$doctor->id) }}">
                    <i class="fe fe-pencil"></i> Edit
                </a>
                @endcan
                @can('delete', $doctor)
                <form action="{{guard_route('doctors.destroy', $doctor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this doctor?');">
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