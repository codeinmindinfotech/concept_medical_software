<div class="row g-4">

  {{-- ▶ Patient Note Information --}}
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Patient Note</strong></h5>
      </div>
      <div class="card-body">
        <div class="row g-3">

          {{-- Patient (readonly display if assigned) --}}
          <div class="col-md-6">
            <label class="form-label"><strong>Patient</strong></label>
            <input type="text" class="form-control" 
              value="{{ $patient->surname ?? 'N/A' }}" disabled>
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
          </div>

          {{-- Method --}}
          <div class="col-md-6">
            <label for="method" class="form-label"><strong>Contact Method <span class="txt-error">*</span></strong></label>
            <select id="method" name="method" class="select2 @error('method') is-invalid @enderror">
              <option value="">-- Select Method --</option>
              <option value="phone message" {{ old('method', $note->method ?? '') === 'phone message' ? 'selected' : '' }}>Phone Message</option>
              <option value="note" {{ old('method', $note->method ?? '') === 'note' ? 'selected' : '' }}>Note</option>
            </select>
            @error('method') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Notes --}}
          <div class="col-md-12">
            <label for="notes" class="form-label"><strong>Note</strong></label>
            <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4">{{ old('notes', $note->notes ?? '') }}</textarea>
            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Completed --}}
          <div class="col-md-4 form-check pt-3">
            <input id="completed" name="completed" type="checkbox" class="form-check-input" value="1" {{ old('completed', $note->completed ?? false) ? 'checked' : '' }}>
            <label for="completed" class="form-check-label"><strong>Completed</strong></label>
            @error('completed') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          </div>

        </div>
      </div>
    </div>
  </div>

  {{-- ▶ Submit Button --}}
  <div class="col-12 text-center mb-4">
    <button type="submit" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-floppy-disk"></i> Save Note
    </button>
  </div>

</div>
