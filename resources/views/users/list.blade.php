<table class="table table-hover table-center mb-0" id="UserTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $i =>$user)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                @if(!empty($user->getRoleNames()))
                    @foreach($user->getRoleNames() as $v)
                    <label class="badge bg-success">{{ $v }}</label>
                    @endforeach
                @endif
                </td>
                <td>
                    <a class="btn btn-sm bg-success-light" href="{{guard_route('users.show',$user->id) }}">
                        <i class="fe fe-eye"></i> Show
                    </a>
                    <a class="btn btn-sm bg-primary-light" href="{{guard_route('users.edit',$user->id) }}">
                        <i class="fe fe-pencil"></i> Edit
                    </a>
                    <form action="{{guard_route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">There are no users.</td>
            </tr>
        @endforelse
    </tbody>
</table>