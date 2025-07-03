<div class="row g-3">
    <div class="col-md-4">
        <label for="code" class="form-label"><strong>Code <span class="txt-error">*</span></strong></label>
        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" 
               value="{{ old('code', $insurance->code ?? '') }}" placeholder="Enter Code">
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="address" class="form-label"><strong>Address <span class="txt-error">*</span></strong></label>
        <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
               value="{{ old('address', $insurance->address ?? '') }}" placeholder="Enter Address">
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="contact_name" class="form-label"><strong>Contact Name <span class="txt-error">*</span></strong></label>
        <input type="text" name="contact_name" id="contact_name" class="form-control @error('contact_name') is-invalid @enderror" 
               value="{{ old('contact_name', $insurance->contact_name ?? '') }}" placeholder="Enter Contact Name">
        @error('contact_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="contact" class="form-label"><strong>Contact <span class="txt-error">*</span></strong></label>
        <input type="text" name="contact" id="contact" class="form-control @error('contact') is-invalid @enderror" 
               value="{{ old('contact', $insurance->contact ?? '') }}" placeholder="Enter Contact Number">
        @error('contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="email" class="form-label"><strong>Email <span class="txt-error">*</span></strong></label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
               value="{{ old('email', $insurance->email ?? '') }}" placeholder="Enter Email">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="postcode" class="form-label"><strong>Postcode <span class="txt-error">*</span></strong></label>
        <input type="text" name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" 
               value="{{ old('postcode', $insurance->postcode ?? '') }}" placeholder="Enter Postcode">
        @error('postcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="fax" class="form-label"><strong>Fax</strong></label>
        <input type="text" name="fax" id="fax" class="form-control @error('fax') is-invalid @enderror" 
               value="{{ old('fax', $insurance->fax ?? '') }}" placeholder="Enter Fax Number">
        @error('fax') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="text-center mb-5">
    <button type="submit" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-floppy-disk"></i> Submit
    </button>
</div>