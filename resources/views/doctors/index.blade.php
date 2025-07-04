@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Doctor List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Doctor List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('doctors.create'),
        'isListPage' => true
    ])

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
                    @forelse ($doctors as $i => $doctor)
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
            {!! $doctors->links('pagination::bootstrap-5') !!}

        </div>    
    </div>
</div>
@endsection