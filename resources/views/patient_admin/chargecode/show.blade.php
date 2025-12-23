@extends('layout.mainlayout')

@section('content')
@php
$days = ['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'];
@endphp
<div class="content">
    <div class="container pt-3">

        <div class="row">

            <div class="card mb-4 shadow-sm p-3">
                <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Charge Code Management
                    </h5>
                    @if(has_permission('chargecode-create'))
                    <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('chargecodes.index') }}">
                        <i class="fas fa-plus-circle me-1"></i> List Charge Code
                    </a>
                    @endif
                </div>
                <!-- General -->
                <div class="card">
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
                <!-- /General -->
            </div>

        </div>

    </div>
</div>

@endsection