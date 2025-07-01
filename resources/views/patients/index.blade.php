@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Patients</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/dashbaord">Dashboard</a></li>
        <li class="breadcrumb-item active">Patients</li>
    </ol>
    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Patients Management
        </div>
        <div class="pull-right">
            <a class="btn btn-success mb-2" href="{{ route('patients.create') }}"  title="Create Patient"><i class="fa fa-plus"></i></a>
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
                @forelse ($patients as $patient)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $patient->name }}</td>
                    <td>
                        <form action="{{ route('patients.destroy',$patient->id) }}" method="POST">
                            <a class="btn btn-info btn-sm" href="{{ route('patients.show',$patient->id) }}" title="Show"><i class="fa-solid fa-list"></i></a>
                            @can('patient-edit')
                            <a class="btn btn-primary btn-sm" href="{{ route('patients.edit',$patient->id) }}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            @endcan

                            @csrf
                            @method('DELETE')

                            @can('patient-delete')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            @endcan
                        </form>
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