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
                            <i class="fas fa-user-clock me-2"></i> Note Management
                        </h5>
                        @if(has_permission('patient-list'))
                            <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('patients.notes.index', $patient) }}" >
                                <i class="fas fa-plus-circle me-1"></i> List Note
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <form action="{{guard_route('patients.notes.store', $patient->id) }}" method="POST" data-ajax class="needs-validation" novalidate>
                            @csrf
                            @include('patients.notes.form', [
                                        'patient' => $patient,
                                        'note'=> $note
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
