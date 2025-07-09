    {{-- Basic Info --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="code" class="form-label">Code</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $clinic->code ?? '') }}" required>
        </div>
        <div class="col-md-4">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $clinic->name ?? '') }}" required>
        </div>
        <div class="col-md-4">
            <label for="clinic_type" class="form-label">Clinic Type</label>
            <input type="text" name="clinic_type" class="form-control" value="{{ old('clinic_type', $clinic->clinic_type ?? '') }}">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2">{{ old('address', $clinic->address ?? '') }}</textarea>
        </div>
        <div class="col-md-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $clinic->phone ?? '') }}">
        </div>
        <div class="col-md-3">
            <label for="fax" class="form-label">Fax</label>
            <input type="text" name="fax" class="form-control" value="{{ old('fax', $clinic->fax ?? '') }}">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $clinic->email ?? '') }}">
        </div>
        <div class="col-md-4">
            <label for="mrn" class="form-label">MRN</label>
            <input type="text" name="mrn" class="form-control" value="{{ old('mrn', $clinic->mrn ?? '') }}">
        </div>
        <div class="col-md-4">
            <label for="planner_seq" class="form-label">Planner Seq</label>
            <input type="text" name="planner_seq" class="form-control" value="{{ old('planner_seq', $clinic->planner_seq ?? '') }}">
        </div>
    </div>

    {{-- Weekly Schedule --}}
    <hr>
    <h5>Weekly Schedule</h5>

    @php
        $days = ['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'];
    @endphp

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Day</th>
                    <th>Enable</th>
                    <th>AM Start</th>
                    <th>AM End</th>
                    <th>PM Start</th>
                    <th>PM End</th>
                    <th>Interval (mins)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($days as $key => $label)
                    <tr>
                        <td><strong>{{ $label }}</strong></td>
                        <td>
                            <input type="checkbox" class="form-check-input" name="{{ $key }}"
                                   {{ old($key, $clinic->$key ?? false) ? 'checked' : '' }}>
                        </td>
                        <td><input type="time" name="{{ $key }}_start_am" class="form-control"
                                   value="{{ old("{$key}_start_am", $clinic->{$key . '_start_am'} ?? '') }}"></td>
                        <td><input type="time" name="{{ $key }}_finish_am" class="form-control"
                                   value="{{ old("{$key}_finish_am", $clinic->{$key . '_finish_am'} ?? '') }}"></td>
                        <td><input type="time" name="{{ $key }}_start_pm" class="form-control"
                                   value="{{ old("{$key}_start_pm", $clinic->{$key . '_start_pm'} ?? '') }}"></td>
                        <td><input type="time" name="{{ $key }}_finish_pm" class="form-control"
                                   value="{{ old("{$key}_finish_pm", $clinic->{$key . '_finish_pm'} ?? '') }}"></td>
                        <td><input type="number" name="{{ $key }}_interval" class="form-control" min="1" max="240"
                                   value="{{ old("{$key}_interval", $clinic->{$key . '_interval'} ?? '') }}"></td>
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

