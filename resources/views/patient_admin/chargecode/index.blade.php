@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container pt-3">

        <div class="row">

            <div class="card mb-4 shadow-sm p-3">
                <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Charge Code Management
                    </h5>
                    @if(has_permission('chargecode-create'))
                    <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('chargecodes.create') }}">
                        <i class="fas fa-plus-circle me-1"></i> Add Charge Code
                    </a>
                    @endif
                </div>
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
                            <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
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

    </div>
</div>

@endsection
@push('scripts')
<script>
    $('#ClinicTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
        , columnDefs: [{
            targets: 6, // column index for "Start Date" (0-based)
            orderable: false // Disable sorting
        }]
    });

</script>
@endpush
