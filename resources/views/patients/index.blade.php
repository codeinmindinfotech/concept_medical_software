@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Patients', 'url' => route('patients.index')],
            ['label' => 'Patients List'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Patients List',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => route('patients.create'),
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
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i> Patients Management
            </div>
            <div>
                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSearch" aria-expanded="{{ $hasFilters ? 'true' : 'false' }}" aria-controls="collapseSearch">
                    <i class="fas fa-filter me-1"></i> Advanced Search
                </button>
            </div>
        </div>
        
        <div class="card-body">
            <div class="accordion mb-4" id="searchAccordion">
                <div class="accordion-item border-0 shadow-sm">
                    <div id="collapseSearch" class="accordion-collapse {{ $hasFilters ? 'show' : '' }}" aria-labelledby="headingSearch"
                         data-bs-parent="#searchAccordion">
                        <div class="accordion-body">
                            <form method="GET" action="{{ route('patients.index') }}">
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
                                    {{-- <div class="col-md-4">
                                        <label for="title" class="form-label">Title</label>
                                        <select name="title" id="title" class="select2">
                                            <option value="">-- Select Title --</option>
                                            @foreach ($titles as $title)
                                                <option value="{{ $title->value }}" {{ request('title') == $title->value ? 'selected' : '' }}>
                                                    {{ $title->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}
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
                                    <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-sync-alt me-1"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="patients-list" data-pagination-container>
                @include('patients.list', ['patients' => $patients])
            </div>
        </div> 
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

<script>
    $('#PatientTable').DataTable({
     paging: true,
     searching: false,
     ordering: true,
     info: true,
     lengthChange: true,
     pageLength: 10,
     columnDefs: [
       {
         targets: 4, // column index for "Start Date" (0-based)
         orderable: false   // Disable sorting
       }
     ]
   });
</script>
@endpush