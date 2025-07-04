<div class="row g-4">

    {{-- Consultant Details --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Consultant Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <!-- Code -->
                    <div class="col-md-6">
                        <label for="code" class="form-label">
                            <strong>Code<span class="txt-error">*</span></strong>
                        </label>
                        <input id="code" name="code" type="text" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $consultant->code ?? '') }}" maxlength="50">
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            <strong>Name<span class="txt-error">*</span></strong>
                        </label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $consultant->name ?? '') }}" maxlength="255">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Address -->
                    <div class="col-12">
                        <label for="address" class="form-label">
                            <strong>Address<span class="txt-error">*</span></strong>
                        </label>
                        <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $consultant->address ?? '') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Phone & Fax -->
                    <div class="col-md-6">
                        <label for="phone" class="form-label">
                            <strong>Phone<span class="txt-error">*</span></strong>
                        </label>
                        <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $consultant->phone ?? '') }}" maxlength="20">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="fax" class="form-label">
                            <strong>Fax</strong>
                        </label>
                        <input id="fax" name="fax" type="text" class="form-control @error('fax') is-invalid @enderror" value="{{ old('fax', $consultant->fax ?? '') }}" maxlength="20">
                        @error('fax') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Email & IMC No -->
                    <div class="col-md-6">
                        <label for="email" class="form-label">
                            <strong>Email<span class="txt-error">*</span></strong>
                        </label>
                        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $consultant->email ?? '') }}" maxlength="255">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="imc_no" class="form-label">
                            <strong>IMC No<span class="txt-error">*</span></strong>
                        </label>
                        <input id="imc_no" name="imc_no" type="text" class="form-control @error('imc_no') is-invalid @enderror" value="{{ old('imc_no', $consultant->imc_no ?? '') }}" maxlength="50">
                        @error('imc_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="col-12">
                        <label for="image" class="form-label">
                            <strong>Image</strong>
                        </label>
                        <input id="image" name="image" type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        @if(isset($consultant->image))
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $consultant->image) }}" alt="Current Image" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0"><strong>Assign Insurances</strong></h5>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="insurance_id" class="form-label"><strong>Insurance Provider<span class="txt-error">*</span></strong></label>
                            <select id="insurance_id" name="insurance_id[]" multiple
                                    class="form-select @error('insurance_id') is-invalid @enderror">
                              @php
                                $selectedIds = old('insurance_id',
                                                   isset($consultant) 
                                                     ? $consultant->insurances->pluck('id')->toArray() 
                                                     : []);
                              @endphp
                          
                              @foreach($insurances as $i)
                                <option value="{{ $i->id }}"{{ in_array($i->id, $selectedIds) ? ' selected' : '' }}>
                                  {{ $i->code }}
                                </option>
                              @endforeach
                            </select>
                            @error('insurance_id')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
                          
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Submit Button --}}
    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-floppy-disk"></i> Submit
        </button>
    </div>
</div>
