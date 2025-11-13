<table class="datatable table table-hover table-center mb-0" id="documentTable">
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
                <form action="{{guard_route('documents.destroy',$document->id) }}" method="POST">
                    {{-- @can('view', $document) --}}
                        <a class="btn btn-info btn-sm" href="{{guard_route('documents.show',$document->id) }}" title="Show">
                            <i class="fa-solid fa-eye text-white"></i>
                        </a>
                    {{-- @endcan
                    @can('update', $document) --}}
                        <a class="btn btn-primary btn-sm" href="{{guard_route('documents.edit',$document->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    {{-- @endcan --}}


                    @csrf
                    @method('DELETE')

                    {{-- @can('delete', $document) --}}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    {{-- @endcan --}}
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>    
</table>
{{-- {!! $templates->links('pagination::bootstrap-5') !!} --}}