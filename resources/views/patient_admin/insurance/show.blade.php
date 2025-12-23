@extends('layout.mainlayout')
@section('content')

<div class="content">
    <div class="container pt-3">

        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Insurance Management
                        </h5>
                        @if(has_permission('insurance-list'))
                        <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('insurances.index') }}">
                            <i class="fas fa-plus-circle me-1"></i> List Insurance
                        </a>
                        @endif
                    </div>
                    <div class="col-12">
                        <div class="card border-start border-warning shadow-sm">
                            <div class="card-header bg-light mb-1 p-2">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-file-invoice-dollar me-2 text-warning"></i>Insurance Information
                                </h5>
                            </div>
                            <div class="card-body row g-3">
                                <div class="col-md-4">
                                    <label class="form-label"><strong>Code:</strong></label>
                                    <div class="form-control-plaintext">{{ $insurance->code ?? '-' }}</div>
                                </div>
                            
                                <div class="col-md-4">
                                    <label class="form-label"><strong>Address:</strong></label>
                                    <div class="form-control-plaintext">{{ $insurance->address ?? '-' }}</div>
                                </div>
                            
                                <div class="col-md-4">
                                    <label class="form-label"><strong>Contact Name:</strong></label>
                                    <div class="form-control-plaintext">{{ $insurance->contact_name ?? '-' }}</div>
                                </div>
                            
                                <div class="col-md-4">
                                    <label class="form-label"><strong>Contact:</strong></label>
                                    <div class="form-control-plaintext">{{ $insurance->contact ?? '-' }}</div>
                                </div>
                            
                                <div class="col-md-4">
                                    <label class="form-label"><strong>Email:</strong></label>
                                    <div class="form-control-plaintext">{{ $insurance->email ?? '-' }}</div>
                                </div>
                            
                                <div class="col-md-4">
                                    <label class="form-label"><strong>Postcode:</strong></label>
                                    <div class="form-control-plaintext">{{ $insurance->postcode ?? '-' }}</div>
                                </div>
                            
                                <div class="col-md-4">
                                    <label class="form-label"><strong>Fax:</strong></label>
                                    <div class="form-control-plaintext">{{ $insurance->fax ?? '-' }}</div>
                                </div>
                            </div>
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
    $('#documentTable').DataTable({
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

   let selectedRow = null;

    $('#libraryTable tbody tr').on('click', function() {
    // Remove highlight from previous selection
    if (selectedRow) {
        $(selectedRow).removeClass('table-active');
    }

    // Highlight new selection
    $(this).addClass('table-active');
    selectedRow = this;

    // Store selected ID in hidden input
    $('#selected_doc').val($(this).data('id'));
    });

    // Form submit validation
    $('#libraryForm').submit(function(e){
        if (!$('#selected_doc').val()) {
            e.preventDefault();
            alert('Please select a document!');
            return false;
        }

        if (!confirm('Are you sure? Current template should be replaced or new template added?')) {
            e.preventDefault();
            return false;
        }
    });

</script>
@endpush

