@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container pt-3">

        <div class="row">

            <div class="card mb-4 shadow-sm p-3">
                <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Consultant Management
                    </h5>
                    @can('viewAny', \App\Models\Consultant::class)
                    <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('consultants.index') }}">
                        <i class="fas fa-plus-circle me-1"></i> List Consultant
                    </a>
                    @endcan
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

    </div>
</div>

@endsection