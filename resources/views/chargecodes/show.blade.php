<?php $page = 'chargecodes.create'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Charge Codes', 'url' =>guard_route('chargecodes.index')],
        ['label' => 'View Charge Code'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'View Charge Code',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('chargecodes.index'),
        'isListPage' => false
        ])
        <div class="row">
            <div class="col-12">
                <!-- General -->
                <div class="card">
                    <div class="card-body">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5><i class="fas fa-file-invoice-dollar me-2"></i> Charge Code: {{ $chargecode->code }}</h5>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <x-show-field label="Charge Code" :value="$chargecode->code" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-show-field label="Group Type" :value="$groupTypes[$chargecode->chargeGroupType] ?? '-'" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-show-field label="VAT Rate (%)" :value="number_format($chargecode->vatrate ?? 0, 2)" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-show-field label="Description" :value="$chargecode->description" />
                                    </div>
                                    <div class="col-md-3">
                                        <x-show-field label="Base Price" :value="'₹ ' . number_format($chargecode->price, 2)" />
                                    </div>
                                </div>

                                <hr>

                                <h5><strong>Insurance-specific Prices</strong></h5>
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Insurance</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($insurances as $index => $insurance)
                                            @php
                                            $priceModel = $chargecode->insurancePrices->firstWhere('insurance_id', $insurance->id);
                                            $displayPrice = $priceModel->price ?? $chargecode->price;
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $insurance->code }}</td>
                                                <td>₹ {{ number_format($displayPrice, 2) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center">No insurance prices available.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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

