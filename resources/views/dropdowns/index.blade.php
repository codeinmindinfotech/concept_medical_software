@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">{{ $pageTitle }}</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/dashbaord">Dashboard</a></li>
        <li class="breadcrumb-item active">Dropdown</li>
    </ol>
    <div class="pull-right">
        <a class="btn btn-success mb-2" href="{{ route('dropdowns.create') }}"  title="Create dropdown"><i class="fa fa-plus"></i></a>
    </div>
    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-md"></i>
            Dropdown Management
        </div>
        
        
        <div class="card-body">
            <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dropdowns as $dropdown)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        <a class="btn-sm" href="{{ route('dropdownvalues.index',$dropdown->id) }}" title="{{ $dropdown->name }}">
                            {{ $dropdown->name }}
                        </a>
                    </td>
                    <td>
                        <form action="{{ route('dropdowns.destroy',$dropdown->id) }}" method="POST">
                            @can('dropdown-edit')
                            <a class="btn btn-primary btn-sm" href="{{ route('dropdowns.edit',$dropdown->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            @endcan

                            @can('dropdown-create')
                                <a class="btn btn-success btn-sm" href="{{ route('dropdownvalues.create',$dropdown->id) }}" title="Add Value"><i class="fa-solid fa-plus"></i></a>
                            @endcan

                            {{-- @csrf
                            @method('DELETE')

                            @can('dropdown-delete')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            @endcan --}}
                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="3">There are no dropdowns.</td>
                    </tr>
                @endforelse
            </tbody>    
            </table>
            {!! $dropdowns->links('pagination::bootstrap-5') !!}

        </div>    
    </div>
</div>
@endsection