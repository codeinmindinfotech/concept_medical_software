@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container">

        <div class="row">

            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Consultant Management
                    </h5>
                </div>
                <div class="card-body">
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
                            <!-- General -->
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{guard_route('consultants.store') }}" method="POST" data-ajax class="needs-validation" novalidate>
                                        @csrf
            
                                        @include('consultants.form')
            
                                    </form>
                                </div>
                            </div>
                            <!-- /General -->
            
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection