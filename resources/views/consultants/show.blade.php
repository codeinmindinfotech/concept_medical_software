@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Consultants', 'url' =>guard_route('consultants.index')],
            ['label' => 'Show Consultant'],
        ];
    @endphp

    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Show Consultant',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('consultants.index'),
        'isListPage' => false
    ])

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0"><i class="fas fa-user-md me-2"></i>Consultant Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">

                <x-show-field label="Code" :value="$consultant->code" />
                <x-show-field label="Name" :value="$consultant->name" />
                <x-show-field label="Phone" :value="$consultant->phone" />
                <x-show-field label="Fax" :value="$consultant->fax ?? '-'" />
                <x-show-field label="Email" :value="$consultant->email" />
                <x-show-field label="IMC No" :value="$consultant->imc_no" />

                <div class="col-12">
                    <label class="form-label fw-bold">Address</label>
                    <p class="form-control-plaintext">{{ $consultant->address }}</p>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Insurance Providers</label>
                    @if($consultant->insurances->isEmpty())
                        <p class="form-control-plaintext"><em>None assigned.</em></p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($consultant->insurances as $ins)
                                <li class="list-group-item">{{ $ins->code }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="col-12 text-center">
                    <label class="form-label fw-bold">Image</label><br>
                    @if($consultant->image)
                        <img src="{{ asset('storage/'.$consultant->image) }}" 
                             alt="{{ $consultant->name }}" 
                             class="img-thumbnail mt-2" 
                             style="max-height: 200px;">
                    @else
                        <p><em>No image provided.</em></p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection