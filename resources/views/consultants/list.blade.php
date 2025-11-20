<table class="table table-hover table-center mb-0" id="ConsultantTable">
    <thead>
        <tr>
            <th>No</th>
            <th>Code</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($consultants as $index => $consultant)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $consultant->code }} </td>
            <td>
                <a class="btn btn-sm bg-success-light" href="{{guard_route('consultants.show',$consultant->id) }}">
                    <i class="fe fe-eye"></i> Show
                </a>
                <a class="btn btn-sm bg-primary-light" href="{{guard_route('consultants.edit',$consultant->id) }}">
                    <i class="fe fe-pencil"></i> Edit
                </a>
                <form action="{{guard_route('consultants.destroy', $consultant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this consultant?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>    
</table>
{{-- {!! $consultants->links('pagination::bootstrap-5') !!} --}}