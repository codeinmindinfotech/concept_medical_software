<div class="row g-4">
  {{-- ▶ Doctor Information --}}
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Doctor Information</strong></h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <!-- Name -->
          <div class="col-md-4">
            <label for="name" class="form-label"><strong>Name<span class="txt-error">*</span></strong></label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
              value="{{ old('name', $doctor->name ?? '') }}">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Company -->
          <div class="col-md-4">
            <label for="company" class="form-label"><strong>Company</strong></label>
            <input type="text" name="company" id="company" class="form-control @error('company') is-invalid @enderror"
              value="{{ old('company', $doctor->company ?? '') }}">
            @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Salutation -->
          <div class="col-md-4">
            <label for="salutation" class="form-label"><strong>Salutation</strong></label>
            <input type="text" name="salutation" id="salutation"
              class="form-control @error('salutation') is-invalid @enderror"
              value="{{ old('salutation', $doctor->salutation ?? '') }}">
            @error('salutation') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Address -->
          <div class="col-md-6">
            <label for="address" class="form-label"><strong>Address</strong></label>
            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror"
              rows="2">{{ old('address', $doctor->address ?? '') }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Postcode -->
          <div class="col-md-3">
            <label for="postcode" class="form-label"><strong>Postcode</strong></label>
            <input type="text" name="postcode" id="postcode"
              class="form-control @error('postcode') is-invalid @enderror"
              value="{{ old('postcode', $doctor->postcode ?? '') }}">
            @error('postcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Mobile -->
          <div class="col-md-3">
            <label for="mobile" class="form-label"><strong>Mobile</strong></label>
            <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror"
              value="{{ old('mobile', $doctor->mobile ?? '') }}">
            @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Phone -->
          <div class="col-md-4">
            <label for="phone" class="form-label"><strong>Phone</strong></label>
            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
              value="{{ old('phone', $doctor->phone ?? '') }}">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Fax -->
          <div class="col-md-4">
            <label for="fax" class="form-label"><strong>Fax</strong></label>
            <input type="text" name="fax" id="fax" class="form-control @error('fax') is-invalid @enderror"
              value="{{ old('fax', $doctor->fax ?? '') }}">
            @error('fax') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Email -->
          <div class="col-md-4">
            <label for="email" class="form-label"><strong>Email</strong><span class="txt-error">*</span></label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
              value="{{ old('email', $doctor->email ?? '') }}">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Contact -->
          <div class="col-md-6">
            <label for="contact" class="form-label"><strong>Contact Person</strong></label>
            <input type="text" name="contact" id="contact" class="form-control @error('contact') is-invalid @enderror"
              value="{{ old('contact', $doctor->contact ?? '') }}">
            @error('contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Contact Type -->
          <div class="col-md-3">
            <label for="contact_type_id" class="form-label"><strong>Contact Type</strong></label>
            <select name="contact_type_id" id="contact_type_id"
              class="select2 @error('contact_type_id') is-invalid @enderror">
              <option value="">-- Select Type --</option>
              @foreach($contactTypes as $type)
              <option value="{{ $type->id }}" {{ old('contact_type_id', $doctor->contact_type_id ?? '') == $type->id ?
                'selected' : '' }}>
                {{ $type->value }}
              </option>
              @endforeach
            </select>
            @error('contact_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Payment Method -->
          <div class="col-md-3">
            <label for="payment_method_id" class="form-label"><strong>Payment Method</strong></label>
            <select name="payment_method_id" id="payment_method_id"
              class="select2 @error('payment_method_id') is-invalid @enderror">
              <option value="">-- Select Method --</option>
              @foreach($paymentMethods as $method)
              <option value="{{ $method->id }}" {{ old('payment_method_id', $doctor->payment_method_id ?? '') ==
                $method->id ? 'selected' : '' }}>
                {{ $method->value }}
              </option>
              @endforeach
            </select>
            @error('payment_method_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

        </div>

        <!-- Notes -->
        <div class="col-md-12">
          <label for="note" class="form-label"><strong>Notes</strong></label>
          <textarea name="note" id="note" rows="3"
            class="form-control @error('note') is-invalid @enderror">{{ old('note', $doctor->note ?? '') }}</textarea>
          @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

      </div>
    </div>
  </div>

  {{-- ▶ Submit --}}
  <div class="col-12 text-center mb-4">
    <button type="submit" class="btn btn-primary">
      <i class="fa-solid fa-floppy-disk"></i> Save Doctor
    </button>
  </div>
</div>