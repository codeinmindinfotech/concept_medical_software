@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">{{ $pageTitle }}</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/dashbaord">Dashboard</a></li>
        <li class="breadcrumb-item active">Dropdownvalues</li>
    </ol>
    <div class="pull-right">
        <a class="btn btn-success mb-2" href="{{ route('dropdownvalues.create',$dropDownId) }}"  title="Create dropdownvalue"><i class="fa fa-plus"></i></a>
    </div>
    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-md"></i>
            DropdownValues Management
        </div>
        
        <div class="card-body">
            <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Value</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($values as $index => $val)
                <tr>
                    <td>{{ $i + $index + 1 }}</td>
                    <td>{{ $dropdown->name }}</td>
                    <td>{{ $val->value }}</td>
                    <td>
                        @can('dropdownvalue-edit')
                        <a href="{{ route('dropdownvalues.edit', [$val->id, $dropdown->id]) }}" class="btn btn-primary btn-sm" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">No values found.</td>
                </tr>
                @endforelse

            </tbody>    
            </table>
            {!! $values->links('pagination::bootstrap-5') !!}

        </div>      
    </div>
</div>
@endsection