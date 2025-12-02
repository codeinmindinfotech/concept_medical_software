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
                <div class="card-body">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-user-clock me-2"></i> WaitingList Management
                            </h5>
                            <a href="{{guard_route('waiting-lists.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                                <i class="fas fa-plus-circle me-1"></i> Waiting Add
                            </a>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered data-table align-middle mb-0" id="WaitingTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Clinic</th>
                                            <th>Category</th>
                                            <th>Consult Note</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($waitingLists as $visit)
                                        <tr data-id="{{ $visit->id }}">
                                            <td>{{ $visit->id }}</td>
                                            <td>{{ format_date($visit->visit_date) }}</td>
                                            <td>{{ $visit->clinic->code ?? '-' }}</td>
                                            <td>{{ $visit->category->value ?? '-' }}</td>
                                            <td>{{ $visit->consult_note ?? '-' }}</td>
                                            <td >
                                              <a href="{{guard_route('waiting-lists.edit', ['patient' => $patient, 'waiting_list' => $visit->id]) }}" class="btn btn-sm bg-primary-light" title="Edit">
                                                <i class="fa fa-edit"></i>
                                              </a>
                                              <form action="{{guard_route('waiting-lists.destroy',['patient' => $patient, 'waiting_list' => $visit->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" style="display: inline;">
                                                @csrf
                                                  @method('DELETE')
                                                  <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
                                              </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
    $('#WaitingTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
        , columnDefs: [{
            targets: 5, // column index for "Start Date" (0-based)
            orderable: false // Disable sorting
        }]
    });

</script>
@endpush
