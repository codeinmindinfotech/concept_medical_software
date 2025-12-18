@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
@php
$breadcrumbs = [
['label' => 'Dashboard', 'url' =>guard_route('dashboard.index')],
['label' => 'Patients Recall List', 'url' =>guard_route('recalls.index', $patient)],
['label' => 'Create Recall'],
];
@endphp

@include('layout.partials.breadcrumb', [
'pageTitle' => 'Create Recall Management',
'breadcrumbs' => $breadcrumbs,
'backUrl' =>guard_route('recalls.index', $patient),
'isListPage' => false
])

<form action="{{guard_route('recalls.store', ['patient' => $patient->id]) }}" data-ajax class="needs-validation" novalidate method="POST">
    @csrf
    <input type="hidden" name="patient_id" value="{{ $patient->id ?? '' }}">
    <input type="hidden" name="recall_id" id="recall_id">

    <div class="mb-3">
        <label for="recall_interval" class="form-label">Interval<span class="txt-error">*</span></label>
        <select name="recall_interval" id="recall_interval" class="form-select select2" required>
            <option value="">-- Select --</option>
            <option value="Today">Today</option>
            <option value="6 weeks">6 weeks</option>
            <option value="2 months">2 months</option>
            <option value="3 months">3 months</option>
            <option value="6 months">6 months</option>
            <option value="1 year">1 year</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="recall_date" class="form-label">Recall Date<span class="txt-error">*</span></label>
        <div class="cal-icon">
            <input id="recall_date" name="recall_date" type="text" required class="form-control datetimepicker" placeholder="YYYY-MM-DD">
        </div>
    </div>

    <div class="mb-3">
        <label for="status_id" class="form-label">Status<span class="txt-error">*</span></label>
        <select name="status_id" id="status_id" class="form-select select2" required>
            @foreach($statuses as $id => $value)
            <option value="{{ $id }}" {{ old('status_id') == $id ? 'selected' : '' }}>
                {{ $value }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="note" class="form-label">Note<span class="txt-error">*</span></label>
        <textarea name="note" id="note" class="form-control" required></textarea>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>

@endsection
