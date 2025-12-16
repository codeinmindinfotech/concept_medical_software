@extends('layout.mainlayout')
@section('content')

<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Insurance Management
                        </h5>
                        @if(has_permission('insurance-list'))
                        <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('insurances.create') }}">
                            <i class="fas fa-plus-circle me-1"></i> Add Insurance
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @include('insurances.list', ['insurances' => $insurances])
                        </div>
                    </div>                        
                </div>
            </div>
        </div>

    </div>

</div>
<!-- /Page Content -->
@endsection
@push('scripts')
<script>
    $('#InsuranceTable').DataTable({
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

