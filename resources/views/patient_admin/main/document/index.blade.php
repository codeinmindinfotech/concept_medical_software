@extends('layout.mainlayout')
@section('content')

<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Document Management
                        </h5>
                        @if(has_permission('document-create'))
                        <a class="btn bg-primary text-white btn-light btn-sm" href="{{guard_route('documents.create') }}">
                            <i class="fas fa-plus-circle me-1"></i> Add DocumentTemplate
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-bottom" id="documentTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="list-tab" data-bs-toggle="tab" href="#list" role="tab">All Documents</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="keywords-tab" data-bs-toggle="tab" href="#keywords" role="tab">Keyword Guidelines</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="library-tab" data-bs-toggle="tab" href="#library" role="tab">Online Forms Library</a>
                            </li>
                        </ul>
            
                        <div class="tab-content mt-3" id="documentTabsContent">
                            <!-- 1. List all documents -->
                            <div class="tab-pane fade show active" id="list" role="tabpanel">
                                <div class="table-responsive">
                                    @include('documents.list', ['templates' => $templates])
                                </div>
                            </div>
    
    
                            <!-- 2. Keyword Guidelines -->
                            <div class="tab-pane fade" id="keywords" role="tabpanel">
                                @include('documents.keywords')
                            </div>
            
            
                            <!-- 3. Online Forms Library -->
                            <div class="tab-pane fade" id="library" role="tabpanel">
                                <h5>Online Forms Library</h5>
                                <form id="libraryForm" method="POST" action="{{ guard_route('documents.library.download') }}">
                                    @csrf
                                    <table class="table table-bordered table-hover" id="libraryTable">
                                        <thead>
                                            <tr>
                                                <th>Document Name</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($templates as $template)
                                            <tr data-id="{{ $template->id }}" style="cursor:pointer;">
                                                <td>{{ $template->name }}</td>
                                                <td>{{ $template->type ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                            
                                    <!-- Hidden input to store selected document ID -->
                                    <input type="hidden" name="selected_doc" id="selected_doc">
                            
                                    <div class="mb-3">
                                        <label for="newTemplateDescription" class="form-label">New Template Description (optional)</label>
                                        <input type="text" class="form-control" name="newTemplateDescription" placeholder="Enter description to create new template">
                                    </div>
                            
                                    <button type="submit" class="btn btn-success">Download Selected Document</button>
                                </form>
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

