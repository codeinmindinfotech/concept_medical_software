<?php $page = 'patient-dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
@component('components.admin.breadcrumb')
@slot('title')
Patient
@endslot
@slot('li_1')
Appointments 
@endslot
@slot('li_2')
Appointments
@endslot
@endcomponent
<!-- Page Content -->
<div class="content">
    <div class="container">

        <div class="row">

            <!-- Profile Sidebar -->
            @component('components.admin.sidebar_patient', ['patient' => $patient])
			@endcomponent

            <!-- / Profile Sidebar -->

            <div class="col-lg-8 col-xl-9">
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
