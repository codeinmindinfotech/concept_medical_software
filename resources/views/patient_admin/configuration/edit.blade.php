@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container pt-3">

        <div class="row">

            <div class="card mb-4 shadow-sm p-3">
                <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Configuration Management
                    </h5>
                    @if(has_permission('configuration-list'))
                    <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('configurations.index') }}">
                        <i class="fas fa-plus-circle me-1"></i> List Configuration
                    </a>
                    @endif
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
                                    <form action="{{guard_route('configurations.update', $configuration->id) }}" method="POST" data-ajax class="needs-validation" novalidate>
                                        @csrf
                                        @method('PUT')
                            
                                        @include('configurations.form', [
                                        'configuration' => $configuration
                                        ])
                            
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