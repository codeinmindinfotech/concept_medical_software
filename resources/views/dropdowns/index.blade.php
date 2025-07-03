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
            <div id="dropdown-list" data-pagination-container>
                @include('dropdowns.list', ['dropdowns' => $dropdowns])
            </div>
        </div>     
    </div>
</div>
@endsection