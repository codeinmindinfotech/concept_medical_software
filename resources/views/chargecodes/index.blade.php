@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
    $breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'Charge Code List'],
    ];
    @endphp

    @include('backend.theme.breadcrumb', [
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
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i> Charge Codes Management
            </div>
            <div>
                <a href="{{guard_route('chargecodeprices.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-dollar-sign"></i> Maintain Prices
                </a>
            </div>
        </div>

        <div class="card-body ">
            <div id="chargecode-list" data-pagination-container>
                @include('chargecodes.list', ['chargecodes' => $chargecodes])
            </div>
        </div>
    </div>
</div>
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