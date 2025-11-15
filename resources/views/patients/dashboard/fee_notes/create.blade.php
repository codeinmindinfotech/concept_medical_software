@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="tasks" role="tabpanel" aria-labelledby="tab-tasks">
  <div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center  ">
      <h5 class="mb-0">
        <i class="fas fa-user-clock me-2"></i> Fee Notes Management
      </h5>
      <a href="{{guard_route('fee-notes.index', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
        <i class="fas fa-plus-circle me-1"></i> Fee Note List
      </a>
    </div>
    <div class="card-body">
      <form action="{{guard_route('fee-notes.store', ['patient' => $patient->id]) }}" class="validate-form"
        method="POST">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $patient->id ?? '' }}">

        <div class="row g-3">
          <input type="hidden" name="id" id="fee_note_id">
          <input type="hidden" name="patient_id" id="patient_id" value="{{ $patient->id }}">

          <div class="mb-3 col-md-4">
            <label>Charge Code<span class="txt-error">*</span></label>
            <select name="chargecode_id" id="chargecode_id" class="form-control select2">
              <option value="">-- Charge Code --</option>
              @foreach($chargecodes as $code)
              <option value="{{ $code->id }}" data-code="{{ json_encode($code) }}">{{ $code->code }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label>Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
          </div>

          <!-- Comment -->
          <div class="col-md-4">
            <label>Comment</label>
            <textarea name="comment" id="comment" class="form-control"></textarea>
          </div>

          <!-- Visit Date -->
          <div class="mb-3 col-md-4">
            <label for="procedure_date" class="form-label"><strong>Procedure Date<span
                  class="txt-error">*</span></strong></label>
            <div class="input-group">
              <input id="procedure_date" name="procedure_date" type="text" class="form-control flatpickr"
                placeholder="YYYY-MM-DD">
              <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
            </div>
          </div>

          <!-- Admission Date -->
          <div class="mb-3 col-md-4">
            <label for="admission_date" class="form-label"><strong>Admission Date</strong></label>
            <div class="input-group">
              <input id="admission_date" name="admission_date" type="text" class="form-control flatpickr"
                placeholder="YYYY-MM-DD">
              <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
            </div>
          </div>

          <!-- Discharge Date -->
          <div class="mb-3 col-md-4">
            <label for="discharge_date" class="form-label"><strong>Discharge Date</strong></label>
            <div class="input-group">
              <input id="discharge_date" name="discharge_date" type="text" class="form-control flatpickr"
                placeholder="YYYY-MM-DD">
              <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
            </div>
          </div>

          <!-- Narrative -->
          <div class="col-md-3">
            <label for="narrative" class="form-label">Narrative</label>
            <select class="form-control select2" id="narrative" name="narrative">
              @foreach($narrative as $id => $value)
              <option value="{{ $id }}" {{ old('narrative')==$id ? 'selected' : '' }}>
                {{ $value }}
              </option>
              @endforeach
            </select>
          </div>

          <!-- Qty -->
          <div class="col-md-2">
            <label>Qty</label>
            <input type="number" name="qty" id="qty" class="form-control" value="1">
          </div>

          <!-- Gross -->
          <div class="col-md-2">
            <label>Gross<span class="txt-error">*</span></label>
            <input type="number" name="charge_gross" id="charge_gross" class="form-control">
          </div>

          <!-- Reduction % -->
          <div class="col-md-2">
            <label>Reduction %</label>
            <input type="number" name="reduction_percent" id="reduction_percent" class="form-control">
          </div>

          <!-- Net -->
          <div class="col-md-2">
            <label>Net<span class="txt-error">*</span></label>
            <input type="number" name="charge_net" id="charge_net" class="form-control">
          </div>

          <!-- VAT % -->
          <div class="col-md-2">
            <label>VAT %<span class="txt-error">*</span></label>
            <input type="number" name="vat_rate_percent" id="vat_rate_percent" class="form-control">
          </div>

          <!-- Total -->
          <div class="col-md-2">
            <label>Total<span class="txt-error">*</span></label>
            <input type="number" name="line_total" id="line_total" class="form-control" readonly>
          </div>


        </div>

        <!-- Separate section for Clinic and Consultant -->
        <hr>
        <div class="row g-3">
          <div class="col-md-3">
            <label>Clinic<span class="txt-error">*</span></label>
            <select name="clinic_id" id="clinic_id" class="form-control select2">
              <option value="">-- Select --</option>
              @foreach($clinics as $clinic)
              <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label>Consultant<span class="txt-error">*</span></label>
            <select name="consultant_id" id="consultant_id" class="form-control select2">
              <option value="">-- Consultant --</option>
              @foreach($consultants as $consultant)
              <option value="{{ $consultant->id }}">{{ $consultant->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection