<?php $page = 'chargecodeprices.edit'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">

    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Charge Codes', 'url' =>guard_route('chargecodeprices.index')],
            ['label' => 'Adjust Prices'],
        ];
    @endphp

    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Adjust Prices',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('chargecodeprices.index'),
        'isListPage' => false
    ])
<div class="row">
    <div class="col-12">
        <!-- General -->
        <div class="card">
            <div class="card-body">
                <div class="card mb-4 shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-hospital-user me-1"></i> Adjust Prices for: <strong>{{ $insurance->code }}</strong></h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <p><strong>Contact:</strong> {{ $insurance->contact_name ?? '-' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Phone:</strong> {{ $insurance->contact }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Address:</strong> {{ $insurance->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{guard_route('chargecodeprices.adjust-prices', $insurance->id) }}" data-ajax class="needs-validation" novalidate>
                    @csrf

                    <div class="card mb-4 border-success">
                        <div class="card-header bg-success text-white">
                            <strong><i class="fas fa-percent me-1"></i> Percentage Adjustment</strong>
                        </div>
                        <div class="card-body row g-3">
                            <div class="col-md-4">
                                <label class="form-label"><strong>Percentage Increase/Decrease (%)</strong></label>
                                <input type="number" name="percentage" class="form-control" step="0.01" placeholder="0.00" value="0.00" required>
                                @error('percentage') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-calculator me-1"></i> Apply Adjustment
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card border-secondary">
                        <div class="card-header bg-light">
                            <strong><i class="fas fa-list-ul me-1"></i> Charge Code Prices</strong>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Price ({{ $insurance->code }})</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($chargePrices as $charge)
                                        @php
                                            $priceEntry = $charge->insurancePrices->first();
                                            $price = $priceEntry ? number_format($priceEntry->price, 2) : number_format($charge->price ?? 0, 2);
                                        @endphp
                                        <tr>
                                            <td><span class="badge bg-primary">{{ $charge->code }}</span></td>
                                            <td>{{ $charge->description }}</td>
                                            <td>
                                                <input type="hidden" name="charge_code_ids[]" value="{{ $charge->id }}">
                                                <input type="number" name="updated_prices[{{ $charge->id }}]" step="0.01"
                                                    value="{{ $price }}" class="form-control form-control-sm">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /General -->

    </div>
</div>

</div>
</div>
<!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->

@endsection

@push('scripts')
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush
