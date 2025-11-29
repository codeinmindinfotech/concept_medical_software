@extends('layout.mainlayout')

@section('content')

@component('components.admin.breadcrumb')
    @slot('title') Create Patient @endslot
    @slot('li_1') Patients @endslot
    @slot('li_2') Create @endslot
@endcomponent

<div class="content">
    <div class="container">

        <div class="row">

            {{-- Patient Dashboard Sidebar --}}
            @component('components.admin.sidebar_patient', ['patient' => $patient])
            @endcomponent

            <div class="col-lg-8 col-xl-9">

                <h3 class="mb-4">Create Patient</h3>
                <form action="{{guard_route('patients.store') }}" method="POST" data-ajax class="needs-validation" novalidate>
                    @csrf
                    @include('patients.form', [
                        'insurances' => $insurances,
                        'preferredContact' => $preferredContact,
                        'doctors' => $doctors,
                        'titles' => $titles
                        ])
                </form>

            </div>
        </div>

    </div>
</div>

@endsection
