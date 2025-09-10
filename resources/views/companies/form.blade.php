<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Company Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    {{-- Company Name --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label"><strong>Name <span class="text-danger">*</span></strong></label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $company->name ?? '') }}" maxlength="255">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-floppy-disk"></i> Submit
        </button>
    </div>
</div>