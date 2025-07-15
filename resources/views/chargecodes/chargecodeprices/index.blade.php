@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Maintain Prices'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Maintain Prices',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('chargecodes.create'),
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
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i> Maintain Prices
            </div>
        </div>
        <div class="card-body ">
            <div id="chargecodeprices-list" data-pagination-container>
                @include('chargecodes.chargecodeprices.list', ['insurances' => $insurances])
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $('#ChargeCodePriceTable').DataTable({
     paging: true,
     searching: true,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
    //  columnDefs: [
    //    {
    //      targets: 6, // column index for "Start Date" (0-based)
    //      orderable: false   // Disable sorting
    //    }
    //  ]
   });
</script>
@endpush