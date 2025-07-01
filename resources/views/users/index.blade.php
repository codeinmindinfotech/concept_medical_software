@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Users Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
        <li class="breadcrumb-item active">Users</li>
    </ol>
    
    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Users Management
        </div>
        <div class="pull-right">
            <a class="btn btn-success mb-2" href="{{ route('users.create') }}"><i class="fa fa-plus"></i> Create New User</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
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
                    @forelse($data as $user)
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
                                <a class="btn btn-info btn-sm" href="{{ route('users.show',$user->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                                <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
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
        </div>
    </div>
</div>
@endsection