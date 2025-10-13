<div class="row g-4">

  {{-- ▶ Patient Note Information --}}
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Patient Document</strong></h5>
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

          {{-- patients/documents/form.blade.php --}}
        <div class="mb-3">
          <label for="document_template_id" class="form-label">Select Document Template</label>
          <select name="document_template_id" id="document_template_id" class="form-control" required>
              <option value="">-- Select Template --</option>
              @foreach($templates as $template)
                  <option value="{{ $template->id }}"
                      {{ (isset($document) && $document->document_template_id == $template->id) || old('document_template_id') == $template->id ? 'selected' : '' }}>
                      {{ $template->name }}
                  </option>
              @endforeach
          </select>
        </div>

        {{-- Only show editor when editing an actual patient document --}}
        @if(isset($document) && $document->file_path)
        <div style="width: 100%; height: calc(100vh - 200px);">
          <div id="placeholder" style="width: 100%; height: calc(100vh - 200px);"></div>
        </div>
        @endif

        </div>
      </div>
    </div>
  </div>

  {{-- ▶ Submit Button --}}
  <div class="col-12 text-center mb-4">
    <button type="submit" class="btn btn-primary btn-sm">
      <i class="fa-solid"></i> Generate Document
    </button>
  </div>

</div>
