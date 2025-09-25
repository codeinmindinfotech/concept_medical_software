@extends('backend.theme.default') 
<style>
#tab-nav {
    position: sticky;
    top: 20px;
    z-index: 1020; /* above content */
}
</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- <div class="card shadow-sm mb-4">
            <div class="card-body d-flex align-items-center">
                <form id="uploadPatientPictureForm" action="{{guard_route('patients.upload-picture', $patient->id) }}" enctype="multipart/form-data" class="position-relative me-3">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    <input type="file" name="patient_picture" id="patient_picture_input" class="d-none" accept="image/*">

                    <label for="patient_picture_input" style="cursor: pointer;">
                        <img id="patient_picture_preview" src="{{ $patient->patient_picture ? asset('storage/' . $patient->patient_picture) : '' }}" alt="Avatar" class="rounded-circle" style="width: 70px; height: 70px; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow" title="Change picture">
                            <i class="fas fa-camera"></i>
                        </span>
                    </label>
                </form>

                <div>
                    <h4 class="mb-1">
                        {{ $patient->full_name }}
                    </h4>
                    <p class="mb-0 text-muted">DOB: {{ format_date($patient->dob) }} | Gender: {{ $patient->gender }}</p>
                </div>

                @can('update', $patient)
                <a href="{{guard_route('patients.edit', $patient->id) }}" class="btn btn-outline-primary btn-sm ms-auto">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endcan
            </div>
        </div> --}}
        
        {{-- Tab Content --}}
        <div class="col-md-10">
            <div class="tab-content" id="tab-content">
                @yield('tab-content')
            </div>
        </div>

        {{-- Sidebar Tabs --}}
        <div class="col-md-2">
            <div class="nav flex-column nav-pills position-sticky" id="tab-nav"
                 style="z-index: 1020;top: 20px;"
                 role="tablist" aria-orientation="vertical">
                @yield('tab-navigation')
            </div>
        </div>

        
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('theme/patient-dashboard.js') }}"></script>
<script src="{{ asset('theme/form-validation.js') }}"></script>
@endpush
