@extends('layout.mainlayout')

@section('content')

{{-- @component('components.admin.breadcrumb')
@slot('title') Edit History @endslot
@slot('li_1') Patients @endslot
@slot('li_2') Edit @endslot
@endcomponent --}}

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

                            <form action="{{guard_route('waiting-lists.store', ['patient' => $patient->id]) }}" data-ajax class="needs-validation" novalidate method="POST">
                                @csrf
                                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                                <div class="mb-3">
                                    <label for="editVisitDate" class="form-label">Visit Date<span class="txt-error">*</span></label>
                                    <div class="cal-icon">
                                        <input id="editVisitDate" name="visit_date" type="text" class="form-control datetimepicker" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="note" class="form-label">Clinic<span class="txt-error">*</span></label>
                                    <select class="form-control select2" id="editClinic" name="clinic_id">
                                        <option value="">-- Select Clinic --</option>
                                        @foreach($clinics as $clinic)
                                        <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="editNote" class="form-label">Type Of appointment<span class="txt-error">*</span></label>
                                    <textarea class="form-control" id="editNote" name="consult_note"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="editCategory" class="form-label">Category<span class="txt-error">*</span></label>
                                    <select class="form-control select2" id="editCategory" name="category_id">
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="{{guard_route('waiting-lists.index', ['patient' => $patient->id]) }}" class="btn btn-secondary">Cancel</a>

                            </form>
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

@endsection
