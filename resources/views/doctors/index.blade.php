@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">{{ $pageTitle }}</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/dashbaord">Dashboard</a></li>
        <li class="breadcrumb-item active">doctors</li>
    </ol>
    <div class="pull-right">
        <a class="btn btn-success mb-2" href="{{ route('doctors.create') }}"  title="Create doctor"><i class="fa fa-plus"></i></a>
    </div>
    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-md"></i>
            Doctors Management
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
                @forelse ($doctors as $doctor)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $doctor->name }}</td>
                    <td>
                        <form action="{{ route('doctors.destroy',$doctor->id) }}" method="POST">
                            <a class="btn btn-info btn-sm" href="{{ route('doctors.show',$doctor->id) }}" title="Show"><i class="fa-solid fa-list"></i></a>
                            @can('doctor-edit')
                            <a class="btn btn-primary btn-sm" href="{{ route('doctors.edit',$doctor->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            @endcan

                            @csrf
                            @method('DELETE')

                            @can('doctor-delete')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            @endcan
                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="3">There are no doctors.</td>
                    </tr>
                @endforelse
            </tbody>    
            </table>
        </div>    
    </div>
</div>
@endsection