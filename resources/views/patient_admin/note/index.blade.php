<?php $page = 'patient-dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
{{-- @component('components.admin.breadcrumb')
@slot('title') Edit History @endslot
@slot('li_1') Patients @endslot
@slot('li_2') Edit @endslot
@endcomponent --}}
<!-- Page Content -->
<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-lg-9 col-xl-10">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Note Management
                        </h5>
                        @if(has_permission('patient-create'))
                            <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('patients.notes.create', $patient) }}" >
                                <i class="fas fa-plus-circle me-1"></i> Note Add
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @include('patients.notes.list', [
                                'patient' => $patient,
                                'notes'=> $notes
                                ])
                        </div>
                    </div> 
                </div>
			</div>
            <!-- Profile Sidebar -->
            @component('components.admin.tab-navigation', ['patient' => $patient])
            @endcomponent
        </div>

    </div>

</div>
<!-- /Page Content -->
@endsection

@push('scripts')
<script>
$('#PatientNote').DataTable({
     paging: true,
     searching: true,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
     columnDefs: [
       {
         targets: 5, // column index for "Start Date" (0-based)
         orderable: false   // Disable sorting
       }
     ]
});
</script>
@endpush
