<table class="table table-hover table-center mb-0" id="CompanyTable">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($companies as $index => $company)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $company->name }} </td>
            <td>
                
                <a class="btn btn-sm bg-primary-light" href="{{guard_route('companies.edit',$company->id) }}">
                    <i class="fe fe-pencil"></i> Edit
                </a>
                <form action="{{guard_route('companies.destroy', $company->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this company? This will also delete the associated database.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
                </form>

                   
            </td>
        </tr>
        @endforeach
    </tbody>    
</table>