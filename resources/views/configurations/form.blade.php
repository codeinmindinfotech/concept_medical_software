<div class="row g-3">
    {{-- Key --}}
    <div class="col-md-6">
        <label for="key" class="form-label"><strong>Key <span class="text-danger">*</span></strong></label>
        <input id="key" name="key" type="text" maxlength="255" class="form-control @error('key') is-invalid @enderror" value="{{ old('key', $configuration->key ?? '') }}" required>
        @error('key')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Value --}}
    <div class="col-md-6">
        <label for="value" class="form-label"><strong>Value</strong></label>
        <textarea id="value" name="value" rows="3" class="form-control @error('value') is-invalid @enderror">{{ old('value', $configuration->value ?? '') }}</textarea>
        @error('value')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Submit --}}
<div class="text-center mt-4">
    <button type="submit" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-floppy-disk"></i> Submit
    </button>
</div>
