@extends('layout.mainlayout')

@section('content')

<div class="content">
    <div class="container">

        <div class="row">

            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i> Consultant Management
                    </h5>
                   @can('create', \App\Models\Consultant::class)
                    <a class="btn btn-sm bg-success-light" href="{{guard_route('consultants.create') }}">
                        <i class="fe fe-eye"></i> Add
                    </a>
                    @endcan
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
                        @include('consultants.list', ['consultants' => $consultants])
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection
@push('scripts')
<script>
     $('#ConsultantTable').DataTable({
     paging: true,
     searching: true,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
     columnDefs: [
       {
         targets: 2, // column index for "Start Date" (0-based)
         orderable: false   // Disable sorting
       }
     ]
   });
</script>
@endpush