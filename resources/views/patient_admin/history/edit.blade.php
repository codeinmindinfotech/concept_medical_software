@extends('layout.mainlayout')

@section('content')

{{-- @component('components.admin.breadcrumb')
@slot('title') Edit History @endslot
@slot('li_1') Patients @endslot
@slot('li_2') Edit @endslot
@endcomponent --}}

<div class="content">
    <div class="container pt-3">

        <div class="row">
            <div class="col-lg-9 col-xl-10">

                <div class="card mb-4 shadow-sm p-3">
                    <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> History Management
                        </h5>
                        @if(has_permission('patient-list'))
                        <a href="{{guard_route('patients.history.index', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> History List
                        </a>
                        @endif
                    </div>
                    <div class="card-body">               
                        <form action="{{guard_route('patients.history.update',[$patient->id, $history->id]) }}" method="POST" data-ajax class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                        
                            @include('patients.history.form', [
                                        'patient' => $patient,
                                        'history'=> $history
                                        ])
                    
                        </form>
                    </div>
                </div>  
            </div>
            <!-- Profile Sidebar -->
            @component('components.admin.tab-navigation', ['patient' => $patient])
            @endcomponent
        </div>

    </div>
</div>

@endsection
