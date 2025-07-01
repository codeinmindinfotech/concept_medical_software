@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Role Management </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/dashbaord">Dashboard</a></li>
        <li class="breadcrumb-item active">Role Management</li>
    </ol>
    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="pull-right">
        <a class="btn btn-success mb-2" href="{{ route('roles.create') }}" title="Create Role"><i class="fa fa-plus"></i></a>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-check"></i>
            Role Management
        </div>
        
        <div class="card-body">
            <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th width="100px">No</th>
                    <th>Name</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>    
            @forelse($roles as $key => $role)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        <a class="btn btn-info btn-sm" href="{{ route('roles.show',$role->id) }}" title="Show"><i class="fa-solid fa-list"></i></a>
                        @can('role-edit')
                            <a class="btn btn-primary btn-sm" href="{{ route('roles.edit',$role->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        @endcan

                        @can('role-delete')
                        <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">There are no Patients.</td>
                </tr>
            @endforelse
            </tbody>    
            </table>
        </div>
    </div>
</div>
@endsection