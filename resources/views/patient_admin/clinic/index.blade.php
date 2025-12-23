@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container pt-3">

        <div class="row">

            <div class="card mb-4 shadow-sm p-3">
                <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Clinic Management
                    </h5>
                    @if(has_permission('clinic-create'))
                    <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('clinics.create') }}">
                        <i class="fas fa-plus-circle me-1"></i> Add Clinic
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="table-responsive">
                        @include('clinics.list', ['clinics' => $clinics])
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
