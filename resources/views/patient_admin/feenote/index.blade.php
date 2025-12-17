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
                                <i class="fas fa-user-clock me-2"></i> Fees Note Management
                            </h5>
                            <a href="{{guard_route('fee-notes.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                                <i class="fas fa-plus-circle me-1"></i> Add Fees Note
                            </a>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                                <div class="table-responsive">
                                    @if($feeNotes->count())
                                    <table class="table table-hover align-middle text-nowrap" id="FeeNoteTable">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Date</th>
                                                <th>Charge Code</th>
                                                <th>Gross</th>
                                                <th>Net</th>
                                                <th>Total</th>
                                                <th>Qty</th>
                                                <th width="100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($feeNotes as $note)
                                            <tr data-id="{{ $note->id }}">
                                                <td>{{ $note->id }}</td>
                                                <td>{{ format_date($note->procedure_date) }}</td>
                                                <td>{{ $note->chargecode->code ?? '' }}</td>
                                                <td>{{ $note->charge_gross }}</td>
                                                <td>{{ $note->charge_net }}</td>
                                                <td>{{ $note->line_total }}</td>
                                                <td>{{ $note->qty }}</td>
                                                <td>
                                                    <a href="{{guard_route('fee-notes.edit', ['patient' => $patient, 'fee_note' => $note]) }}" class="btn btn-sm bg-primary-light" title="Edit">
                                                        <i class="fe fe-pencil"></i> Edit
                                                    </a>
            
                                                    <form action="{{guard_route('fee-notes.destroy', ['patient' => $patient, 'fee_note' => $note]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this fee note?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <p>No Fee Notes found.</p>
                                    @endif
            
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
    $('#FeeNoteTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
        , columnDefs: [{
            targets: 7, // column index for "Start Date" (0-based)
            orderable: false // Disable sorting
        }]
    });

</script>
@endpush
