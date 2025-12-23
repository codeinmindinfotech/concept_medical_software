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
    <div class="container pt-3">

        <div class="row">
            <div class="col-lg-9 col-xl-10">
			    <div class="card mb-4 shadow-sm p-3">
                    <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i> Recall Management
                        </h5>
                        @if(has_permission('patient-edit'))
                        <a href="{{guard_route('recalls.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> New Recall
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-hover table-center mb-0" id="RecallTable">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recalls as $recall)
                                    <tr data-id="{{ $recall->id }}">
                                        <td>{{ $recall->id }}</td>
                                        <td>{{ format_date($recall->recall_date) }}</td>
                                        <td>{{ $recall->note }}</td>
                                        <td>{{ $recall->status?->value }}</td>
                                        <td>
                                            @if(has_permission('patient-edit'))
                                            <a href="{{guard_route('recalls.edit', ['patient' => $patient, 'recall' => $recall]) }}" class="btn btn-sm bg-primary-light" title="Edit">
                                                <i class="fe fe-pencil"></i> Edit
                                            </a>
                                            @endif
                                            @if(has_permission('patient-delete'))
                                            <form action="{{guard_route('recalls.destroy', ['patient' => $patient, 'recall' => $recall]) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this task?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    $('#RecallTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
        , columnDefs: [{
            targets: 4, // column index for "Start Date" (0-based)
            orderable: false // Disable sorting
        }]
    });

</script>
@endpush
