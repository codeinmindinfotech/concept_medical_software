<div class="row g-4">
  {{-- ▶ Document Template Information --}}
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Document Template</strong></h5>
      </div>
      <div class="card-body">
        <div class="row g-3">

          <!-- Template Name -->
          <div class="col-md-6">
            <label for="name" class="form-label"><strong>Template Name <span class="text-danger">*</span></strong></label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
              value="{{ old('name', $template->name ?? '') }}">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Template Type -->
          <div class="col-md-6">
            <label for="type" class="form-label"><strong>Template Type <span class="text-danger">*</span></strong></label>
            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
              <option value="">-- Select Type --</option>
              <option value="letter" {{ old('type', $template->type ?? '') == 'letter' ? 'selected' : '' }}>Letter</option>
              <option value="form" {{ old('type', $template->type ?? '') == 'form' ? 'selected' : '' }}>Form</option>
            </select>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- File Upload -->
          <div class="mb-3">
            <label for="file" class="form-label"><strong>Upload File {{ empty($template->file_path) ? '*' : '' }}</strong></label>
            <input 
                class="form-control @error('file') is-invalid @enderror" 
                type="file" 
                name="file" 
                id="file" 
                accept=".doc,.docx,.pdf" 
                {{ empty($template->file_path) ? 'required' : '' }}
            >
            <div class="form-text">Accepted file types: .doc, .docx, .pdf. Max size: 2MB.</div>
            @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Show Current File (Edit Only) -->
          @if(!empty($template->file_path))
            <div class="mb-3">
              <a href="{{ asset('storage/' . $template->file_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                View Current File
              </a>
            </div>
          @endif

        </div>
      </div>
    </div>
  </div>

  {{-- ▶ Submit --}}
  <div class="col-12 text-center mb-4">
    <button type="submit" class="btn btn-primary">
      <i class="fa-solid fa-floppy-disk"></i> Save Template
    </button>
  </div>
</div>
