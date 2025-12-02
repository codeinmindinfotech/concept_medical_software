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

@endsection
