<?php $page = 'View Clinic'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content container-fluid">
        @php
        $days = ['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'];
        @endphp
        @php
        $breadcrumbs = [
        ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
        ['label' => 'Clinics', 'url' =>guard_route('clinics.index')],
        ['label' => 'Show Clinic'],
        ];
        @endphp

        @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Show Clinic',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' =>guard_route('clinics.index'),
        'isListPage' => false
        ])
        <div class="row">
            <div class="col-12">
                <!-- General -->
                <div class="card">
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Clinic Information Card -->
                            <div class="col-md-6">
                                <div class="card shadow-sm border-start border-primary">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-clinic-medical me-2 text-primary"></i>Clinic Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <x-show-field label="Code" :value="$clinic->code" />
                                        <x-show-field label="Name" :value="$clinic->name" />
                                        <x-show-field label="Type" :value="ucfirst($clinic->clinic_type)" />
                                        <x-show-field label="MRN" :value="$clinic->mrn ?? '-'" />
                                        <x-show-field label="Planner Seq" :value="$clinic->planner_seq ?? '-'" />
                                    </div>
                                </div>
                            </div>

                            <!-- Contact & Address Card -->
                            <div class="col-md-6">
                                <div class="card shadow-sm border-start border-info">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-map-marker-alt me-2 text-info"></i>Contact & Address
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <x-show-field label="Email" :value="$clinic->email" />
                                        <x-show-field label="Phone" :value="$clinic->phone ?? '-'" />
                                        <x-show-field label="Fax" :value="$clinic->fax ?? '-'" />
                                        <x-show-field label="Address" :value="$clinic->address ?? '-'" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Weekly Schedule Table -->
                        <h5 class="mb-3">Weekly Schedule</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Day</th>
                                        <th>Open?</th>
                                        <th>AM Hours</th>
                                        <th>PM Hours</th>
                                        <th>Interval (mins)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($days as $key => $label)
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td>{{ $clinic->$key ? '✅' : '❌' }}</td>
                                        <td>
                                            @if($clinic->{$key.'_start_am'})
                                            {{ format_time($clinic->{$key.'_start_am'}) }} –
                                            {{ format_time($clinic->{$key.'_finish_am'}) }}
                                            @else
                                            &mdash;
                                            @endif
                                        </td>
                                        <td>
                                            @if($clinic->{$key.'_start_pm'})
                                            {{ format_time($clinic->{$key.'_start_pm'}) }} –
                                            {{ format_time($clinic->{$key.'_finish_pm'}) }}
                                            @else
                                            &mdash;
                                            @endif
                                        </td>
                                        <td>{{ $clinic->{$key.'_interval'} ?? '-' }}</td>
                                    </tr>
                                    @endforeach
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
<!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->
@endsection