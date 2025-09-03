<table class="table table-bordered data-table" id="CompanyTable">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($companies as $index => $company)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $company->name }} </td>
            <td>
                    @usercan('company-list')
                        <a class="btn btn-info btn-sm" href="{{ guard_route('companies.show',$company->id) }}" title="Show"><i class="fa-solid fa-eye text-white"></i></a>
                    @endusercan

                    @usercan('company-edit')
                        <a class="btn btn-primary btn-sm" href="{{ guard_route('companies.edit',$company->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    @endusercan

                    @usercan('company-delete')                     
                    <form action="{{ guard_route('companies.destroy', $company->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this company? This will also delete the associated database.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                   @endusercan
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="3">There are no Companies.</td>
            </tr>
        @endforelse
    </tbody>    
</table>
