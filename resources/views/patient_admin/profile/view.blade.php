@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-lg-9 col-xl-10">

                @include('patients.inner_show', [
                    'patient' => $patient
                   ])
                
            </div>
             {{-- Patient Dashboard Sidebar --}}
             @component('components.admin.tab-navigation', ['patient' => $patient])
             @endcomponent
        </div>

    </div>
</div>

@endsection