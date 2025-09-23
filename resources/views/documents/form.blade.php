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

          <!-- Template Body -->
          <div class="col-12">
            <label for="template_body" class="form-label"><strong>Template Body <span class="text-danger">*</span></strong></label>
            <textarea name="template_body" id="template_body" rows="20"
              class="form-control font-monospace"
              style="white-space: pre; font-family: monospace; font-size: 14px;"
              placeholder="Use Blade syntax like">
            {{ old('template_body', $template->template_body ?? '') }}</textarea>

            @error('template_body') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

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
