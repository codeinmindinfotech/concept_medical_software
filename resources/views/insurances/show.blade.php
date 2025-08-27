@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Insurances', 'url' =>guard_route('insurances.index')],
            ['label' => 'Show Insurance'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Show Insurance',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('insurances.index'),
        'isListPage' => false
    ])

       
    <div class="tab-content border border-top-0 p-3">
        <div class="row g-3">
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
@endsection