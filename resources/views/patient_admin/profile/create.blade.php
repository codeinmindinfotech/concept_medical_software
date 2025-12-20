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


            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Patient Management
                    </h5>
                    <a href="{{guard_route('patients.index') }}" class="btn bg-primary text-white btn-light btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Patient List
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{guard_route('patients.store') }}" method="POST" data-ajax class="needs-validation" novalidate>
                        @csrf
                        @include('patients.form', [
                        'insurances' => $insurances,
                        'doctors' => $doctors,
                        'titles' => $titles
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
