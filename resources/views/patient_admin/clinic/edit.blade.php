@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container">

        <div class="row">

                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Clinic Management
                        </h5>
                        @if(has_permission('clinic-list'))
                        <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('clinics.index') }}">
                            <i class="fas fa-plus-circle me-1"></i> List Clinic
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
                        <form action="{{guard_route('clinics.update', $clinic->id) }}" method="POST" data-ajax class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                        
                            @include('clinics.form', [
                                'clinic' => $clinic
                                ])

                        </form>
                    </div>    
                </div>
            
        </div>

    </div>
</div>

@endsection
