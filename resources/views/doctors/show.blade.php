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
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-user-md me-2"></i><strong>Doctor Information</strong></h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <x-show-field label="Name" :value="$doctor->name" />
                        <x-show-field label="Company" :value="$doctor->company" />
                        <x-show-field label="Salutation" :value="$doctor->salutation" />
                        <x-show-field label="Address" :value="$doctor->address" col="6" />
                        <x-show-field label="Postcode" :value="$doctor->postcode" col="2" />
                        <x-show-field label="Mobile" :value="$doctor->mobile" col="2" />
                        <x-show-field label="Phone" :value="$doctor->phone" col="2" />
                        <x-show-field label="Fax" :value="$doctor->fax" col="2" />
                        <x-show-field label="Email" :value="$doctor->email" />
                        <x-show-field label="Contact" :value="$doctor->contact" />
                        <x-show-field label="Contact Type" :value="$doctor->contactType->value ?? '-'" />
                        <x-show-field label="Payment Method" :value="$doctor->paymentMethod->value ?? '-'" />
                        
                        <div class="col-12">
                            <label class="form-label fw-bold">Notes</label>
                            <div class="form-control-plaintext">{{ $doctor->note ?? '-' }}</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection