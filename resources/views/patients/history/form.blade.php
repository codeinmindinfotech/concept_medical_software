<div class="row g-4">

  {{-- ▶ Patient history Information --}}
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Patient History</strong></h5>
      </div>
      <div class="card-body">
        <div class="row g-3">

          {{-- Patient (readonly display if assigned) --}}
          <div class="col-md-6">
            <label class="form-label"><strong>Patient</strong></label>
            <input type="text" class="form-control" 
              value="{{ $patient->full_name ?? 'N/A' }}" disabled>
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
          </div>

          {{-- historys --}}
          <div class="col-md-12">
            <label for="history_notes" class="form-label"><strong>History Notes</strong></label>
            <textarea id="history_notes" name="history_notes" class="form-control @error('history_notes') is-invalid @enderror" rows="4">{{ old('history_notes', $history->history_notes ?? '') }}</textarea>
            @error('history_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

        </div>
      </div>
    </div>
  </div>

  {{-- ▶ Submit Button --}}
  <div class="col-12 text-center mb-4">
    <button type="submit" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-floppy-disk"></i> Save History
    </button>
  </div>

</div>
