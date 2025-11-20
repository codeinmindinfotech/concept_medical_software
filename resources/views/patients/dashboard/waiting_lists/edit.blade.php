@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
@php
$breadcrumbs = [
    ['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
    ['label' => 'Patients Waiting Management', 'url' =>guard_route('waiting-lists.index', $patient)],
    ['label' => 'Edit Waiting'],
];
@endphp

@include('layout.partials.breadcrumb', [
'pageTitle' => 'Edit Waiting Management',
'breadcrumbs' => $breadcrumbs,
'backUrl' =>guard_route('waiting-lists.index', $patient),
'isListPage' => false
])


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{guard_route('waiting-lists.update', ['patient' => $patient->id, 'waiting_list' => $waitingList->id]) }}" class="validate-form" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="patient_id" value="{{ $patient->id }}">

        <div class="mb-3">
            <label for="editVisitDate" class="form-label">Visit Date<span class="txt-error">*</span></label>
            <div class="cal-icon">
                <input id="editVisitDate" name="visit_date" type="text" class="form-control datetimepicker" placeholder="YYYY-MM-DD" required 
                       value="{{ old('visit_date', $waitingList->visit_date) }}">
            </div>
        </div>

        {{-- Clinic --}}
        <div class="mb-3">
            <label for="editClinic" class="form-label">Clinic<span class="txt-error">*</span></label>
            <select class="form-select select2" id="editClinic" name="clinic_id">
                <option value="">-- Select Clinic --</option>
                @foreach($clinics as $clinic)
                    <option value="{{ $clinic->id }}" {{ old('clinic_id', $waitingList->clinic_id) == $clinic->id ? 'selected' : '' }}>
                        {{ $clinic->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Type of Appointment --}}
        <div class="mb-3">
            <label for="editNote" class="form-label">Type Of Appointment<span class="txt-error">*</span></label>
            <textarea class="form-control" id="editNote" name="consult_note" required>{{ old('consult_note', $waitingList->consult_note) }}</textarea>
        </div>

        {{-- Category --}}
        <div class="mb-3">
            <label for="editCategory" class="form-label">Category<span class="txt-error">*</span></label>
            <select class="form-select select2" id="editCategory" name="category_id" required>
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $waitingList->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->value }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{guard_route('waiting-lists.index', ['patient' => $patient->id]) }}" class="btn btn-secondary">Cancel</a>

    </form>
  @endsection