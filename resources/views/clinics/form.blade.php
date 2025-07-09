@php
$days = ['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'];
@endphp
{{-- Basic Info --}}
<div class="row mb-3">
    <div class="col-md-4">
        <label for="code" class="form-label"><strong>Code <span class="text-danger">*</span></strong></label>
        <input id="code" name="code" type="text" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $clinic->code ?? '') }}">
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="name" class="form-label"><strong>Name <span class="text-danger">*</span></strong></label>
        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $clinic->name ?? '') }}">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="clinic_type"><strong>Clinic Type</strong></label>
        <select name="clinic_type" id="clinic_type" class="form-control @error('clinic_type') is-invalid @enderror"">
            <option value="clinic" {{ old('clinic_type', $clinic->clinic_type ?? '') === 'clinic' ? 'selected' : '' }}>Clinic</option>
            <option value="hospital" {{ old('clinic_type', $clinic->clinic_type ?? '') === 'hospital' ? 'selected' : '' }}>Hospital</option>
        </select>
        @error('clinic_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Contact Info --}}
<div class="row mb-3">
    <div class="col-md-6">
        <label for="address" class="form-label"><strong>Address</strong></label>
        <textarea id="address" name="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address', $clinic->address ?? '') }}</textarea>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label for="phone" class="form-label"><strong>Phone</strong></label>
        <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $clinic->phone ?? '') }}">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label for="fax" class="form-label"><strong>Fax</strong></label>
        <input id="fax" name="fax" type="text" class="form-control @error('fax') is-invalid @enderror" value="{{ old('fax', $clinic->fax ?? '') }}">
        @error('fax')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <label for="email" class="form-label"><strong>Email <span class="text-danger">*</span></strong></label>
        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $clinic->email ?? '') }}">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="mrn" class="form-label"><strong>MRN</strong></label>
        <input id="mrn" name="mrn" type="text" class="form-control @error('mrn') is-invalid @enderror" value="{{ old('mrn', $clinic->mrn ?? '') }}">
        @error('mrn')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="planner_seq" class="form-label"><strong>Planner Seq</strong></label>
        <input id="planner_seq" name="planner_seq" type="text" class="form-control @error('planner_seq') is-invalid @enderror" value="{{ old('planner_seq', $clinic->planner_seq ?? '') }}">
        @error('planner_seq')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Weekly Schedule --}}
<hr>
<h5>Weekly Schedule</h5>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Day</th>
                <th></th>
                <th>AM Start</th>
                <th>AM End</th>
                <th>PM Start</th>
                <th>PM End</th>
                <th>Interval (mins)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($days as $key=>$label)
            <tr>
                <td><strong>{{ $label }}</strong></td>
                <td>
                    <input type="checkbox" id="{{ $key }}"  name="{{ $key }}" value="1" class="form-check-input @error($key) is-invalid @enderror"  {{ old($key, $clinic->{$key} ?? false) ? 'checked' : '' }}>
                    @error($key)<div class="invalid-feedback">{{ $message }}</div>@enderror
                </td>
                @foreach(['start_am','finish_am','start_pm','finish_pm','interval'] as $suffix)
                <td>
                    <input
                      type="{{ $suffix === 'interval' ? 'number' : 'time' }}"
                      id="{{ $key.'_'.$suffix }}"
                      name="{{ $key.'_'.$suffix }}"
                      @if($suffix !== 'interval')
                        min="00:00" max="23:59" step="60"
                      @else
                        min="1" max="240"
                      @endif
                      class="form-control @error($key.'_'.$suffix) is-invalid @enderror"
                      value="{{ old($key.'_'.$suffix, $clinic->{$key.'_'.$suffix} ?? '') }}"
                    >
                    @error($key.'_'.$suffix)<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </td>
                  
                  
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="text-end mt-4">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-1"></i> {{ isset($clinic) ? 'Update' : 'Create' }} Clinic
    </button>
</div>
