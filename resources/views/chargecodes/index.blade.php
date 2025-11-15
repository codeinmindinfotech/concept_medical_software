<?php $page = 'chargecodes-list'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="container-fluid px-4">
            @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
                ['label' => 'Charge Code List'],
            ];
            @endphp

            @include('layout.partials.breadcrumb', [
                'pageTitle' => 'Charge Code List',
                'breadcrumbs' => $breadcrumbs,
                'backUrl' =>guard_route('chargecodes.create'),
                'isListPage' => true
            ])

            @session('success')
                <div class="alert alert-success" role="alert">
                    {{ $value }}
                </div>
            @endsession

            @php
                $hasFilters = request()->hasAny(['search']);
            @endphp

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                </div>
                                <div>
                                    <a href="{{guard_route('chargecodeprices.index') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-dollar-sign"></i> Maintain Prices
                                    </a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                @include('chargecodes.list', ['chargecodes' => $chargecodes])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->
@endsection
@push('scripts')
<script>
    $('#ChargeCodeTable').DataTable({
     paging: true,
     searching: true,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
     columnDefs: [
       {
         targets: 6, // column index for "Start Date" (0-based)
         orderable: false   // Disable sorting
       }
     ]
   });
</script>
@endpush