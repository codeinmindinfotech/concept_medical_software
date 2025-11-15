<table class="table table-hover table-center mb-0" id="documentTable">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($templates as $i => $document)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $document->name }}</td>
            <td>
                <a class="btn btn-sm bg-success-light" href="{{guard_route('documents.show',$document->id) }}">
                    <i class="fe fe-eye"></i> Show
                </a>
                <a class="btn btn-sm bg-primary-light" href="{{guard_route('documents.edit',$document->id) }}">
                    <i class="fe fe-pencil"></i> Edit
                </a>
                <form action="{{guard_route('documents.destroy', $document->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this document?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>    
</table>
{{-- {!! $templates->links('pagination::bootstrap-5') !!} --}}