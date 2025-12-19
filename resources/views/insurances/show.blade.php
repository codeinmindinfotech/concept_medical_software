@extends('layout.mainlayout_admin')

@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-4">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Insurances', 'url' =>guard_route('insurances.index')],
        ['label' => 'Show Insurance'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Show Insurance',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('insurances.index'),
        'isListPage' => false
        ])


        <div class="col-12">
            <div class="card border-start border-warning shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-invoice-dollar me-2 text-warning"></i>Insurance Information
                    </h5>
                </div>
                <div class="card-body row g-3">
                    <div class="col-md-4">
                        <label class="form-label"><strong>Code:</strong></label>
                        <div class="form-control-plaintext">{{ $insurance->code ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><strong>Address:</strong></label>
                        <div class="form-control-plaintext">{{ $insurance->address ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><strong>Contact Name:</strong></label>
                        <div class="form-control-plaintext">{{ $insurance->contact_name ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><strong>Contact:</strong></label>
                        <div class="form-control-plaintext">{{ $insurance->contact ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><strong>Email:</strong></label>
                        <div class="form-control-plaintext">{{ $insurance->email ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><strong>Postcode:</strong></label>
                        <div class="form-control-plaintext">{{ $insurance->postcode ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><strong>Fax:</strong></label>
                        <div class="form-control-plaintext">{{ $insurance->fax ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
</div>
@endsection