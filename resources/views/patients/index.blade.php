@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Patients</h2>
        </div>
        <div class="pull-right">
            @can('patient-create')
            <a class="btn btn-success btn-sm mb-2" href="{{ route('patients.create') }}"><i class="fa fa-plus"></i> Create New patient</a>
            @endcan
        </div>
    </div>
</div>

@session('success')
    <div class="alert alert-success" role="alert"> 
        {{ $value }}
    </div>
@endsession

<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($patients as $patient)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $patient->name }}</td>
        <td>
            <form action="{{ route('patients.destroy',$patient->id) }}" method="POST">
                <a class="btn btn-info btn-sm" href="{{ route('patients.show',$patient->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                @can('patient-edit')
                <a class="btn btn-primary btn-sm" href="{{ route('patients.edit',$patient->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                @endcan

                @csrf
                @method('DELETE')

                @can('patient-delete')
                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                @endcan
            </form>
        </td>
    </tr>
    @endforeach
</table>

{!! $patients->links() !!}
</div>
@endsection