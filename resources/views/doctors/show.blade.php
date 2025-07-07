@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Doctors', 'url' => route('doctors.index')],
    ['label' => 'Show Doctor'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
    'pageTitle' => 'Show Doctor',
    'breadcrumbs' => $breadcrumbs,
    'backUrl' => route('doctors.index'),
    'isListPage' => false
    ])

    <div class="row g-4">
        {{-- ▶ Doctor Information --}}
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0"><strong>Doctor Information</strong></h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label"><strong>Name</strong></label>
                            <div class="form-control-plaintext">{{ $doctor->name }}</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label"><strong>Company</strong></label>
                            <div class="form-control-plaintext">{{ $doctor->company }}</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label"><strong>Salutation</strong></label>
                            <div class="form-control-plaintext">{{ $doctor->salutation }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><strong>Address</strong></label>
                            <div class="form-control-plaintext">{{ $doctor->address }}</div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><strong>Postcode</strong></label>
                            <div class="form-control-plaintext">{{ $doctor->postcode }}</div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><strong>Mobile</strong></label>
                            <div class="form-control-plaintext">{{ $doctor->mobile }}</div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><strong>Phone</strong></label>
                            <div class="form-control-plaintext">{{ $doctor->phone }}</div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><strong>Fax</strong></label>
                            <div class="form-control-plaintext">{{ $doctor->fax }}</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label"><strong>Email</strong></label>
                            <div class="form-control-plaintext">{{ $doctor->email }}</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label"><strong>Contact</strong></label>
                            <div class="form-control-plaintext">{{ $doctor->contact }}</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label"><strong>Contact Type</strong></label>
                            <div class="form-control-plaintext">
                                {{ $doctor->contactType->value ?? '-' }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label"><strong>Payment Method</strong></label>
                            <div class="form-control-plaintext">
                                {{ $doctor->paymentMethod->value ?? '-' }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><strong>Notes</strong></label>
                            <div class="form-control-plaintext">{{ $doctor->note }}</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection