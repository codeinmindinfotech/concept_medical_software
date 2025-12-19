@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container">

        <div class="row">

            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Company Management
                    </h5>
                    @if(has_permission('company-list'))
                    <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('companies.index') }}">
                        <i class="fas fa-plus-circle me-1"></i> List Company
                    </a>
                    @endif
                </div>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            
                                    <form action="{{guard_route('companies.store') }}" method="POST" data-ajax class="needs-validation" novalidate>
                                        @csrf
            
                                        @include('companies.form')
            
                                    </form>
                                
            
                        </div>
                    </div>
            </div>

        </div>

    </div>
</div>

@endsection