<table class="table table-bordered data-table" id="UserTable">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            {{-- <th>Role</th> --}}
            <th width="280px">Action</th>
        </tr>
    </thead>
    {{-- <tfoot>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th width="280px">Action</th>
        </tr>
    </tfoot> --}}
    <tbody>
        @forelse($data as $i =>$user)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                {{-- <td>
                @if(!empty($user->getRoleNames()))
                    @foreach($user->getRoleNames() as $v)
                    <label class="badge bg-success">{{ $v }}</label>
                    @endforeach
                @endif
                </td> --}}
                <td>
                    <a class="btn btn-info btn-sm" href="{{guard_route('users.show',$user->id) }}" title="Show"><i class="fa-solid fa-eye text-white"></i></a>
                    <a class="btn btn-primary btn-sm" href="{{guard_route('users.edit',$user->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <form method="POST" action="{{guard_route('users.destroy', $user->id) }}" style="display:inline">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">There are no users.</td>
            </tr>
        @endforelse
    </tbody>
</table>