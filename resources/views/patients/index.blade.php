<?php $page = 'patients-list'; ?>
@extends('layout.mainlayout_admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="container-fluid px-1">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
            ['label' => 'Patients', 'url' =>guard_route('patients.index')],
            ['label' => 'Patients List'],
        ];
    @endphp

    @include('layout.partials.breadcrumb', [
        'pageTitle' => 'Patients List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => guard_route('patients.create'),
        'isListPage' => true
    ])

    @session('success')
        <div class="alert alert-success" role="alert"> 
            {{ $value }}
        </div>
    @endsession
@php
    $hasFilters = request()->hasAny(['first_name', 'surname', 'phone', 'dob']);
@endphp
    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center mb-1 p-2">
                    <div>
                        <i class="fas fa-table me-1"></i> Patients Search
                    </div>
                    <div>
                        <button class="btn btn-sm btn-primary {{ $hasFilters ? '' : 'collapsed' }}" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#collapseSearch" 
                                aria-expanded="{{ $hasFilters ? 'true' : 'false' }}" 
                                aria-controls="collapseSearch">
                            <i class="fas fa-filter me-1"></i> Advanced Search
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="accordion mb-1" id="searchAccordion">
                        <div class="accordion-item border-0 shadow-sm">
                            <div id="collapseSearch"
                                 class="accordion-collapse collapse {{ $hasFilters ? 'show' : '' }}"
                                 data-bs-parent="#searchAccordion">
                                <div class="accordion-body">
                                    <form method="GET" action="{{ guard_route('patients.index') }}">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="first_name" class="form-label">First Name</label>
                                                <input type="text" name="first_name" id="first_name" class="form-control"
                                                       placeholder="e.g. John" value="{{ request('first_name') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="surname" class="form-label">Surname</label>
                                                <input type="text" name="surname" id="surname" class="form-control"
                                                       placeholder="e.g. Doe" value="{{ request('surname') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="phone" class="form-label">Phone</label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                       placeholder="e.g. 0123456789" value="{{ request('phone') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="pin" class="form-label">PIN</label>
                                                <input type="text" name="pin" id="pin" class="form-control"
                                                       placeholder="e.g. 123456" value="{{ request('pin') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="dob" class="form-label">Date of Birth</label>
                                                <input type="date" name="dob" id="dob" class="form-control"
                                                       value="{{ request('dob') }}">
                                            </div>
                                        </div>
            
                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-primary me-2">
                                                <i class="fas fa-search me-1"></i> Search
                                            </button>
                                            <a href="{{ guard_route('patients.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-sync-alt me-1"></i> Reset
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                                
                    <ul class="nav nav-tabs nav-tabs-bottom" id="patientTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request('tab') !== 'trashed' ? 'active' : '' }}"
                               href="{{ guard_route('patients.index') }}">
                               Active Patients
                            </a>
                        </li>
                        @if (has_role('superadmin') || has_role('manager'))
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ request('tab') === 'trashed' ? 'active' : '' }}"
                                href="{{ guard_route('patients.index', ['tab' => 'trashed']) }}">
                                Trashed Patients
                                </a>
                            </li>
                        @endif
                    </ul>
        
        
                    <div class="table-responsive">
                        @include('patients.list', ['patients' => $patients, 'trashed' => request('tab') === 'trashed'])
                    </div>
                    
                </div>
            </div>
        </div>
    </div>                    
</div>
</div>
<!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->
@push('modals')
    <!-- WhatsApp Modal (Only One Modal for All Appointments) -->
    <div class="modal fade" id="whatsAppModal" tabindex="-1" aria-labelledby="whatsAppModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="whatsAppModalLabel">Send WhatsApp Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Custom Message Input -->
                    <textarea id="customMessage" class="form-control" rows="4" placeholder="Enter your message here...">
                        Hello, I wanted to confirm my appointment for
                    </textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="sendWhatsAppMessage()">Send Message</button>
                </div>
            </div>
        </div>
    </div>
@endpush    
    
@endsection
@push('scripts')
<script>
    window.appConfig = {
        whatsappSend: "{{ guard_route('whatsapp.send.runtime') }}"
     };
</script>
<script src="{{ URL::asset('/assets/js/modalpopup.js') }}"></script>
<script src="{{ URL::asset('/assets/js/popupForm.js') }}"></script>
<script>
    
document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    
    $('#PatientTable').DataTable({
        paging: true,
        searching: false,
        ordering: true,
        info: true,
        lengthChange: true,
        pageLength: 10,
        // columnDefs: [
        // {
        //     targets: 3, // column index for "Start Date" (0-based)
        //     orderable: false   // Disable sorting
        // }
        // ]
   });
});
</script>
@endpush