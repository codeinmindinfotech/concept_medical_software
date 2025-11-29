@extends('layout.mainlayout')

@section('content')

@component('components.admin.breadcrumb')
    @slot('title') Edit Patient @endslot
    @slot('li_1') Patients @endslot
    @slot('li_2') Edit @endslot
@endcomponent

<div class="content">
    <div class="container">

        <div class="row">

            {{-- Patient Dashboard Sidebar --}}
            @component('components.admin.sidebar_patient', ['patient' => $patient])
            @endcomponent

            <div class="col-lg-8 col-xl-9">

                <h3 class="mb-4">Edit Patient</h3>
                <form action="{{guard_route('patients.notes.update',[$patient->id, $note->id]) }}" method="POST" data-ajax class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                
                    @include('patients.notes.form', [
                    'patient' => $patient,
                    'note'=> $note
                    ])
                
                </form>

            </div>
        </div>

    </div>
</div>

@endsection
