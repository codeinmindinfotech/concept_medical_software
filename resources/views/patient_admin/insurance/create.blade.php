@extends('layout.mainlayout')
@section('content')

<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Insurance Management
                        </h5>
                        @if(has_permission('insurance-list'))
                        <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('insurances.index') }}">
                            <i class="fas fa-plus-circle me-1"></i> List Insurance
                        </a>
                        @endif
                    </div>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> Please fix the following errors:<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="card-body">
                        <form action="{{guard_route('insurances.store') }}" method="POST" data-ajax class="needs-validation" novalidate>
                            @csrf
                            @include('insurances.form')
                        </form>
                    </div>                       
                </div>
            </div>
        </div>

    </div>

</div>
<!-- /Page Content -->
@endsection