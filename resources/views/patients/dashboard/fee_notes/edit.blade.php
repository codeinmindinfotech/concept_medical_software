@extends('layout.tabbed')

@section('tab-navigation')
@include('layout.partials.tab-navigation', ['patient' => $patient])
@endsection

@section('tab-content')
<div class="tab-pane fade show active" id="tasks" role="tabpanel" aria-labelledby="tab-tasks">
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center  ">
            <h5 class="mb-0">
                <i class="fas fa-user-clock me-2"></i>Edit Fee Note
            </h5>
            <a href="{{guard_route('fee-note.create', $patient) }}" class="btn bg-primary text-white btn-light btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Add Fee Note
            </a>
        </div>
        <div class="card-body">
            <form action="{{guard_route('fee-notes.update', ['patient' => $patient->id, 'fee_note' => $feeNote]) }}"
                data-ajax class="needs-validation" novalidate method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="patient_id" id="patient_id" value="{{ $patient->id }}">

                <div class="row g-3">
                    {{-- Charge Code --}}
                    <div class="mb-3 col-md-4">
                        <label>Charge Code<span class="txt-error">*</span></label>
                        <select name="chargecode_id" id="chargecode_id" class="select2" required>
                            <option value="">-- Charge Code --</option>
                            @foreach($chargecodes as $code)
                            <option value="{{ $code->id }}" {{ old('chargecode_id', $feeNote->chargecode_id) ==
                                $code->id ? 'selected' : '' }}>
                                {{ $code->code }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="col-md-4">
                        <label>Description</label>
                        <textarea name="description"
                            class="form-control">{{ old('description', $feeNote->description) }}</textarea>
                    </div>

                    {{-- Comment --}}
                    <div class="col-md-4">
                        <label>Comment</label>
                        <textarea name="comment" class="form-control">{{ old('comment', $feeNote->comment) }}</textarea>
                    </div>

                    {{-- Procedure Date --}}
                    <div class="mb-3 col-md-4">
                        <label for="procedure_date" class="form-label"><strong>Procedure Date *</strong></label>
                        <div class="cal-icon">
                            <input id="procedure_date" name="procedure_date" type="text" class="form-control datetimepicker"
                                value="{{ old('procedure_date', $feeNote->procedure_date) }}" placeholder="YYYY-MM-DD">
                        </div>
                    </div>

                    {{-- Admission Date --}}
                    <div class="mb-3 col-md-4">
                        <label for="admission_date" class="form-label">Admission Date</label>
                        <div class="cal-icon">
                            <input id="admission_date" name="admission_date" type="text" class="form-control datetimepicker"
                                value="{{ old('admission_date', $feeNote->admission_date) }}" placeholder="YYYY-MM-DD">
                        </div>
                    </div>

                    {{-- Discharge Date --}}
                    <div class="mb-3 col-md-4">
                        <label for="discharge_date" class="form-label">Discharge Date</label>
                        <div class="cal-icon">
                            <input id="discharge_date" name="discharge_date" type="text" class="form-control datetimepicker"
                                value="{{ old('discharge_date', $feeNote->discharge_date) }}" placeholder="YYYY-MM-DD">
                        </div>
                    </div>

                    {{-- Narrative --}}
                    <div class="col-md-3">
                        <label for="narrative" class="form-label">Narrative</label>
                        <select class="select2" id="narrative" name="narrative" required>
                            @foreach($narrative as $id => $value)
                            <option value="{{ $id }}" {{ old('narrative', $feeNote->narrative) == $id ? 'selected' : ''
                                }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Qty --}}
                    <div class="col-md-2">
                        <label>Qty</label>
                        <input type="number" name="qty" class="form-control"
                            value="{{ old('qty', $feeNote->qty ?? 1) }}">
                    </div>

                    {{-- Gross --}}
                    <div class="col-md-2">
                        <label>Gross</label>
                        <input type="number" name="charge_gross" class="form-control"
                            value="{{ old('charge_gross', $feeNote->charge_gross) }}">
                    </div>

                    {{-- Reduction Percent --}}
                    <div class="col-md-2">
                        <label>Reduction %</label>
                        <input type="number" name="reduction_percent" class="form-control"
                            value="{{ old('reduction_percent', $feeNote->reduction_percent) }}">
                    </div>

                    {{-- Net --}}
                    <div class="col-md-2">
                        <label>Net</label>
                        <input type="number" name="charge_net" class="form-control"
                            value="{{ old('charge_net', $feeNote->charge_net) }}">
                    </div>

                    {{-- VAT Percent --}}
                    <div class="col-md-2">
                        <label>VAT %</label>
                        <input type="number" name="vat_rate_percent" class="form-control"
                            value="{{ old('vat_rate_percent', $feeNote->vat_rate_percent) }}">
                    </div>

                    {{-- Total --}}
                    <div class="col-md-2">
                        <label>Total</label>
                        <input type="number" name="line_total" class="form-control"
                            value="{{ old('line_total', $feeNote->line_total) }}" readonly>
                    </div>
                </div>

                <hr>

                {{-- Clinic & Consultant --}}
                <div class="row g-3">
                    <div class="mb-3 col-md-4">
                        <label>Clinic<span class="txt-error">*</span></label>
                        <select name="clinic_id" class="form-select select2" required>
                            <option value="">-- Select --</option>
                            @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}" {{ old('clinic_id', $feeNote->clinic_id) == $clinic->id ?
                                'selected' : '' }}>
                                {{ $clinic->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label>Consultant<span class="txt-error">*</span></label>
                        <select name="consultant_id" class="select2" required>
                            <option value="">-- Consultant --</option>
                            @foreach($consultants as $consultant)
                            <option value="{{ $consultant->id }}" {{ old('consultant_id', $feeNote->consultant_id) ==
                                $consultant->id ? 'selected' : '' }}>
                                {{ $consultant->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection