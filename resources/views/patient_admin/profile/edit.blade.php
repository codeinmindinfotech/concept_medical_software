@extends('layout.mainlayout')

@section('content')

{{-- @component('components.admin.breadcrumb')
@slot('title') Edit History @endslot
@slot('li_1') Patients @endslot
@slot('li_2') Edit @endslot
@endcomponent --}}

<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-lg-9 col-xl-10">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Note Management
                        </h5>
                        <a href="{{guard_route('patients.notes.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> Note Add
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{guard_route('patients.update', $patient->id) }}" method="POST" data-ajax class="needs-validation" novalidate>

                            @csrf
                            @method('PUT')

                            {{-- Reuse SAME patient form --}}
                            @include('patients.form', [
                                'patient' => $patient,
                                'insurances' => $insurances,
                                'preferredContact' => $preferredContact,
                                'doctors' => $doctors,
                                'titles' => $titles,
                                'consultants' => $consultants,
                                'relations' => $relations,
                            ])

                        </form>
                    </div>    
                </div>
            </div>

             {{-- Patient Dashboard Sidebar --}}
             @component('components.admin.tab-navigation', ['patient' => $patient])
             @endcomponent
        </div>

    </div>
</div>

@endsection
