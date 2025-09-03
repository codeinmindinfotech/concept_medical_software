    <table class="table table-bordered table-striped table-sm align-middle" id="RoleTable">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Role Name</th>
                <th>Guard</th>
                <th>Permissions</th>
                <th width="180">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $index => $role)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ ucfirst($role->name) }}</td>
                    <td>{{ $role->guard_name }}</td>
                    <td>
                        @if($role->permissions->isNotEmpty())
                            {!! $role->permissions->map(fn($perm) => '<span class="badge bg-primary">' . e($perm->name) . '</span>')->implode(' ') !!}
                        @else
                            <span class="text-muted">No permissions</span>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-sm btn-info" href="{{ guard_route('roles.edit', $role->id) }}">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <form action="{{ guard_route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure to delete this role?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No roles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- <div class="mt-3">
        {{ $roles->links() }}
    </div>
</div>
</div>
@endsection --}}
