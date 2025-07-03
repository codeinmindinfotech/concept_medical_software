@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $pageTitle }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/dashbaord">Dashboard</a></li>
        <li class="breadcrumb-item active">Insurances</li>
    </ol>
    <div class="pull-right">
        <a class="btn btn-success mb-2" href="{{ route('insurances.create') }}"  title="Create Patient"><i class="fa fa-plus"></i></a>
    </div>
    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Insurances Management
        </div>
        <div class="card-body">
            <div id="insurances-list" data-pagination-container>
                @include('insurances.list', ['insurances' => $insurances])
            </div>
        </div> 
    </div>
</div>
@endsection