@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container">

        <div class="row">
            <!-- General -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Doctor Management
                    </h5>
                   @can('viewAny', \App\Models\Doctor::class)
                    <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('doctors.index') }}">
                        <i class="fas fa-plus-circle me-1"></i> List Doctor
                    </a>
                    @endcan
                </div>
                
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="card-body">
                        <form action="{{guard_route('doctors.store') }}" method="POST"  data-ajax class="needs-validation" novalidate>
                            @csrf
                            @include('doctors.form', [
                            'contactTypes' => $contactTypes,
                            'paymentMethods' => $paymentMethods
                            ])
                        </form>
                    </div>
                </div>

            </div>


        </div>

    </div>
</div>

@endsection
@push('scripts')
<script src="{{ URL::asset('/assets/js/signature.js') }}"></script>
@endpush