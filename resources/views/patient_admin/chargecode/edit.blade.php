@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container pt-3">

        <div class="row">

                <div class="card mb-4 shadow-sm p-3">
                    <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Charge Code Management
                        </h5>
                        @if(has_permission('chargecode-create'))
                        <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('chargecodes.index') }}">
                            <i class="fas fa-plus-circle me-1"></i> List Charge Code
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
                        <div class="card-body">
                            <form action="{{guard_route('chargecodes.update', $chargecode->id) }}" data-ajax class="needs-validation" novalidate method="POST">
                                @csrf
                                @method('PUT')
            
                                @include('chargecodes.form', [
                                    'chargecode' => $chargecode,
                                    'insurances' => $insurances,
                                    'groupTypes' => $groupTypes
                                ])
                            </form>
                        </div>
                </div>
            
        </div>

    </div>
</div>

@endsection
