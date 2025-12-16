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
                            <i class="fas fa-user-clock me-2"></i> Communication Management
                        </h5>
                        @if(has_permission('patient-create'))
                        <a class="btn bg-primary text-white btn-light btn-sm" href="{{ guard_route('sms.index', ['patient' => $patient]) }}">
                            <i class="fas fa-plus-circle me-1"></i> Add SMS
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered data-table align-middle mb-0" data-route="{{guard_route('communications.received', ['communication' => '__ID__']) }}" id="CommunicationTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Method</th>
                                        <th>Received</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($communications as $communication)
                                    <tr data-id="{{ $communication->id }}">
                                        <td>{{ $communication->id }}</td>
                                        <td>{{ format_date($communication->created_at) }}</td>
                                        <td>{{ $communication->message ?? '-' }}</td>
                                        <td>{{ $communication->method ?? '-' }}</td>
                                        <td><input type="checkbox" onchange="markAsReceived({{ $communication->id }}, this)" /></td>
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
    function markAsReceived(id, checkbox) {
        if (!checkbox.checked) return;

        const routeTemplate = document.getElementById('CommunicationTable').dataset.route;
        const route = routeTemplate.replace('__ID__', id);

        fetch(route, {
                method: 'POST'
                , headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    , 'Content-Type': 'application/json'
                , }
            })
            .then(response => {
                if (response.ok) {
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) row.remove();
                } else {
                    Swal.fire("Error", 'Failed to update.', "warning");
                    checkbox.checked = false;
                }
            })
            .catch(() => {
                Swal.fire("Error", 'Request failed.', "warning");
                checkbox.checked = false;
            });
    }

    $('#CommunicationTable').DataTable({
        paging: true
        , searching: true
        , ordering: true
        , info: true
        , lengthChange: true
        , pageLength: 10
    , });

</script>
@endpush
