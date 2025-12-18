<div class="row g-4">

  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Consultant Information</strong></h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="consultant_id" class="form-label"><strong>Consultant <span class="txt-error">*</span></strong></label>
            <select id="consultant_id" required name="consultant_id"
              class="form-control select2 @error('consultant_id') is-invalid @enderror">
              <option value="">-- Select Consultant --</option>
              @foreach($consultants as $consultant)
                <option value="{{ $consultant->id }}"
                  {{ old('consultant_id', $patient->consultant_id ?? '') == $consultant->id ? 'selected' : '' }}>
                  {{ $consultant->name }} - [{{ $consultant->code }}]
                </option>
              @endforeach
            </select>
            @error('consultant_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          @if(has_role('superadmin') )
            <div class="col-md-6">
              <label for="company_id" class="form-label">Select Company:</label>
              <select name="company_id" id="company_id" class="form-control select2" required>
                  <option value="">-- Select Company --</option>
                  @foreach(\App\Models\Company::all() as $company)
                  <option value="{{ $company->id }}" {{ old('company_id', $patient->company_id ?? '') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                  @endforeach
              </select>
              @error('company_id')
              <div class="text-danger">{{ $message }}</div>
              @enderror
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  
  {{-- ▶ Personal Information --}}
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Personal Information</strong></h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <!-- Title -->
          <div class="col-md-4">
            <label for="title_id" class="form-label"><strong>Title<span class="txt-error">*</span></strong></label>
            <select id="title_id" name="title_id" class="form-control select2 @error('title_id') is-invalid @enderror" data-placeholder="-- Select --" required>
              <option value=""></option>
              @foreach($titles as $title)
              <option value="{{ $title->id }}" {{ old('title_id', $patient->title_id ?? '') == $title->id ? 'selected' :
                '' }}>
                {{ $title->value }}
              </option>
              @endforeach
            </select>
            @error('title_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Surname -->
          <div class="col-md-4">
            <label for="surname" class="form-label"><strong>Surname<span class="txt-error">*</span></strong></label>
            <input id="surname" name="surname" type="text" class="form-control @error('surname') is-invalid @enderror"
              value="{{ old('surname', $patient->surname ?? '') }}" required>
            @error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- First Name -->
          <div class="col-md-4">
            <label for="first_name" class="form-label"><strong>First Name<span
                  class="txt-error">*</span></strong></label>
            <input id="first_name" name="first_name" type="text"
              class="form-control @error('first_name') is-invalid @enderror"
              value="{{ old('first_name', $patient->first_name ?? '') }}" required>
            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- DOB -->
          <div class="col-md-4">
            <label for="dob" class="form-label"><strong>Date of Birth<span class="txt-error">*</span></strong></label>
            <div class="cal-icon">
              <input id="dob" name="dob" type="text" class="form-control datetimepicker @error('dob') is-invalid @enderror"
                placeholder="YYYY-MM-DD" value="{{ old('dob', $patient->dob ?? '') }}" required>
            </div>
            @error('dob') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          </div>

          <!-- Gender -->
          <div class="col-md-4">
            <label for="gender" class="form-label"><strong>Gender<span class="txt-error">*</span></strong></label>
            <select id="gender" name="gender" class="form-control select2 @error('gender') is-invalid @enderror" required>
              <option value="">-- Select Gender --</option>
              <option value="Male" {{ old('gender', $patient->gender ?? '')=='Male'?'selected':'' }}>Male</option>
              <option value="Female" {{ old('gender', $patient->gender ?? '')=='Female'?'selected':'' }}>Female</option>
              <option value="Other" {{ old('gender', $patient->gender ?? '')=='Other'?'selected':'' }}>Other</option>
            </select>
            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- <!-- Doctor -->
          <div class="col-md-4">
            <label for="doctor_id" class="form-label"><strong>Doctor<span class="txt-error">*</span></strong></label>
            <select id="doctor_id" name="doctor_id" class="select2 @error('doctor_id') is-invalid @enderror">
              <option value="">-- Select Doctor --</option>
              @foreach($doctors as $doctor)
              <option value="{{ $doctor->id }}" {{ old('doctor_id', $patient->doctor_id ?? '') == $doctor->id ?
                'selected' : '' }}>
                Dr. {{ $doctor->name }}
              </option>
              @endforeach
            </select>
            @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div> --}}
        </div>
      </div>
    </div>
  </div>

  {{-- ▶ Doctor Information --}}
<div class="col-12">
  <div class="card shadow-sm">
    <div class="card-header">
      <h5 class="card-title mb-0"><strong>Doctor Information</strong></h5>
    </div>
    <div class="card-body">
      <div class="row g-3">

        <!-- Primary Doctor -->
        <div class="col-md-6">
          <label for="doctor_id" class="form-label"><strong>Primary Doctor<span class="txt-error">*</span></strong></label>
          <select id="doctor_id" name="doctor_id" class="form-control select2 @error('doctor_id') is-invalid @enderror" required>
            <option value="">-- Select Doctor --</option>
            @foreach($doctors as $doctor)
              <option value="{{ $doctor->id }}" {{ old('doctor_id', $patient->doctor_id ?? '') == $doctor->id ? 'selected' : '' }}>
                Dr. {{ $doctor->name }}
              </option>
            @endforeach
          </select>
          @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Referral Doctor -->
        <div class="col-md-6">
          <label for="referral_doctor_id" class="form-label"><strong>Referral Doctor</strong></label>
          <select id="referral_doctor_id" name="referral_doctor_id" class="form-control select2 @error('referral_doctor_id') is-invalid @enderror">
            <option value="">-- Select Doctor --</option>
            @foreach($doctors as $doctor)
              <option value="{{ $doctor->id }}" {{ old('referral_doctor_id', $patient->referral_doctor_id ?? '') == $doctor->id ? 'selected' : '' }}>
                Dr. {{ $doctor->name }}
              </option>
            @endforeach
          </select>
          @error('referral_doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Other Doctor -->
        <div class="col-md-6">
          <label for="other_doctor_id" class="form-label"><strong>Other Doctor</strong></label>
          <select id="other_doctor_id" name="other_doctor_id" class="form-control select2 @error('other_doctor_id') is-invalid @enderror">
            <option value="">-- Select Doctor --</option>
            @foreach($doctors as $doctor)
              <option value="{{ $doctor->id }}" {{ old('other_doctor_id', $patient->other_doctor_id ?? '') == $doctor->id ? 'selected' : '' }}>
                Dr. {{ $doctor->name }}
              </option>
            @endforeach
          </select>
          @error('other_doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Solicitor Doctor -->
        <div class="col-md-6">
          <label for="solicitor_doctor_id" class="form-label"><strong>Solicitor Doctor</strong></label>
          <select id="solicitor_doctor_id" name="solicitor_doctor_id" class="form-control  select2 @error('solicitor_doctor_id') is-invalid @enderror">
            <option value="">-- Select Doctor --</option>
            @foreach($doctors as $doctor)
              <option value="{{ $doctor->id }}" {{ old('solicitor_doctor_id', $patient->solicitor_doctor_id ?? '') == $doctor->id ? 'selected' : '' }}>
                Dr. {{ $doctor->name }}
              </option>
            @endforeach
          </select>
          @error('solicitor_doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

      </div>
    </div>
  </div>
</div>

  {{-- ▶ Contact Information --}}
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Contact Information</strong></h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="phone" class="form-label"><strong>Phone<span class="txt-error">*</span></strong></label>
            <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
              value="{{ old('phone', $patient->phone ?? '') }}" required>
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-4">
            <label for="email" class="form-label"><strong>Email<span class="txt-error">*</span></strong></label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
              value="{{ old('email', $patient->email ?? '') }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-4">
            <label for="preferred_contact_id" class="form-label"><strong>Preferred Contact Method</strong></label>
            <select id="preferred_contact_id" name="preferred_contact_id"
              class="form-control select2 @error('preferred_contact_id') is-invalid @enderror">
              <option value="">-- Select --</option>
              @foreach($preferredContact as $method)
              <option value="{{ $method->id }}" {{ old('preferred_contact_id', $patient->preferred_contact_id ?? '') ==
                $method->id ? 'selected' : '' }}>
                {{ $method->value }}
              </option>
              @endforeach
            </select>
            @error('preferred_contact_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
        <div class="row g-3 mt-3">
          <div class="col-12">
            <label for="address" class="form-label"><strong>Address<span class="txt-error">*</span></strong></label>
            <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror"
              rows="2" required>{{ old('address', $patient->address ?? '') }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12">
    <div class="card shadow-sm mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Insurance Information</strong></h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="insurance_id" class="form-label"><strong>Insurance Provider</strong></label>
            <select id="insurance_id" name="insurance_id"
              class="form-control select2 @error('insurance_id') is-invalid @enderror">
              <option value="">-- Select Insurance --</option>
              @foreach($insurances as $insurance)
              <option value="{{ $insurance->id }}" {{ old('insurance_id', $patient->insurance_id ?? '') ==
                $insurance->id ? 'selected' : '' }}>
                {{ $insurance->code }}
              </option>
              @endforeach
            </select>
            @error('insurance_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-4">
            <label for="insurance_plan" class="form-label"><strong>Insurance Plan</strong></label>
            <input id="insurance_plan" name="insurance_plan" type="text"
              class="form-control @error('insurance_plan') is-invalid @enderror"
              value="{{ old('insurance_plan', $patient->insurance_plan ?? '') }}">
            @error('insurance_plan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-4">
            <label for="policy_no" class="form-label"><strong>Policy Number</strong></label>
            <input id="policy_no" name="policy_no" type="text"
              class="form-control @error('policy_no') is-invalid @enderror"
              value="{{ old('policy_no', $patient->policy_no ?? '') }}">
            @error('policy_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- ▶ Emergency & Medical Info --}}
  <div class="col-6 ">
    <div class="card shadow-sm mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Emergency & Medical Information</strong></h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label for="emergency_contact" class="form-label"><strong>Emergency Contact</strong></label>
          <input id="emergency_contact" name="emergency_contact" type="text"
            class="form-control @error('emergency_contact') is-invalid @enderror"
            value="{{ old('emergency_contact', $patient->emergency_contact ?? '') }}">
          @error('emergency_contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
          <label for="medical_history" class="form-label"><strong>Medical History / Notes</strong></label>
          <textarea id="medical_history" name="medical_history"
            class="form-control @error('medical_history') is-invalid @enderror"
            rows="3">{{ old('medical_history', $patient->medical_history ?? '') }}</textarea>
          @error('medical_history') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
          <label for="referral_reason" class="form-label"><strong>Referral Reason</strong></label>
          <textarea id="referral_reason" name="referral_reason"
            class="form-control @error('referral_reason') is-invalid @enderror"
            rows="2">{{ old('referral_reason', $patient->referral_reason ?? '') }}</textarea>
          @error('referral_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        
        <div class="mb-3">
          <label for="symptoms" class="form-label"><strong>Symptoms</strong></label>
          <textarea id="symptoms" name="symptoms" class="form-control @error('symptoms') is-invalid @enderror"
            rows="2">{{ old('symptoms', $patient->symptoms ?? '') }}</textarea>
          @error('symptoms') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
          <label for="patient_needs" class="form-label"><strong>Patient Needs</strong></label>
          <textarea id="patient_needs" name="patient_needs"
            class="form-control @error('patient_needs') is-invalid @enderror"
            rows="2">{{ old('patient_needs', $patient->patient_needs ?? '') }}</textarea>
          @error('patient_needs') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
          <label for="allergies" class="form-label"><strong>Allergies</strong></label>
          <textarea id="allergies" name="allergies" class="form-control @error('allergies') is-invalid @enderror"
            rows="2">{{ old('allergies', $patient->allergies ?? '') }}</textarea>
          @error('allergies') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
          <label for="diagnosis" class="form-label"><strong>Diagnosis</strong></label>
          <textarea id="diagnosis" name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror"
            rows="2">{{ old('diagnosis', $patient->diagnosis ?? '') }}</textarea>
          @error('diagnosis') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>
    </div>

  </div>

  {{-- ▶ Insurance Information --}}
  <div class="col-6">

    

    <div class="card shadow-sm mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Patient Status & Consent</strong></h5>
      </div>
      <div class="card-body">
        <div class="row align-items-center g-3">
          <div class="col-md-4 form-check">
            <input id="rip" name="rip" type="checkbox" class="form-check-input" value="1" {{ old('rip', $patient->rip ??
            false) ? 'checked' : '' }}>
            <label for="rip" class="form-check-label"><strong>RIP</strong></label>
          </div>
          <div class="col-md-8">
            <label for="rip_date" class="form-label"><strong>Date of RIP</strong></label>
            <div class="cal-icon">
              <input id="rip_date" name="rip_date" type="text"
                class="form-control datetimepicker @error('rip_date') is-invalid @enderror" placeholder="YYYY-MM-DD"
                value="{{ old('rip_date', $patient->rip_date ?? '') }}">
            </div>
            @error('rip_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-4 form-check">
            <input id="sms_consent" name="sms_consent" type="checkbox" class="form-check-input" value="1" {{
              old('sms_consent', $patient->sms_consent ?? false) ? 'checked' : '' }}>
            <label for="sms_consent" class="form-check-label">SMS Consent</label>
          </div>
          <div class="col-md-8 form-check">
            <input id="email_consent" name="email_consent" type="checkbox" class="form-check-input" value="1" {{
              old('email_consent', $patient->email_consent ?? false) ? 'checked' : '' }}>
            <label for="email_consent" class="form-check-label">Email Consent</label>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>COVID-19 Vaccination Info</strong></h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="covid_19_vaccination_date" class="form-label"><strong>Vaccination Date</strong></label>
            <div class="cal-icon">
              <input type="date"
                    name="covid_19_vaccination_date"
                    id="covid_19_vaccination_date"
                    class="form-control datetimepicker @error('covid_19_vaccination_date') is-invalid @enderror"
                    value="{{ old('covid_19_vaccination_date', optional(optional($patient ?? null)->covid_19_vaccination_date)->format('Y-m-d')) }}">
            </div>
            @error('covid_19_vaccination_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          </div>
          
          <div class="col-md-6 form-check mt-4 pt-2">
            <input id="fully_covid_19_vaccinated" name="fully_covid_19_vaccinated" type="checkbox"
              class="form-check-input" value="1" {{ old('fully_covid_19_vaccinated', optional(optional($patient ?? null)->fully_covid_19_vaccinated)
            ?? false) ? 'checked' : '' }}>
            <label for="fully_covid_19_vaccinated" class="form-check-label"><strong>Fully Vaccinated</strong></label>
          </div>

          <div class="col-md-12">
            <label for="covid_19_vaccination_note" class="form-label"><strong>Vaccination Note</strong></label>
            <textarea name="covid_19_vaccination_note" id="covid_19_vaccination_note"
              class="form-control @error('covid_19_vaccination_note') is-invalid @enderror" rows="2"
              placeholder="Any additional information...">
              {{ old('covid_19_vaccination_note', $patient->covid_19_vaccination_note ?? '') }}
            </textarea>
              @error('covid_19_vaccination_note') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          </div>

          
        </div>
      </div>
    </div>

    <div class="card shadow-sm mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Next of Kin Information</strong></h5>
      </div>
      <div class="card-body">
        <div class="row align-items-center g-3">

        <div class="col-md-6">
          <label for="next_of_kin" class="form-label"><strong>Name</strong></label>
          <input type="text" name="next_of_kin" id="next_of_kin"
            class="form-control @error('next_of_kin') is-invalid @enderror"
            value="{{ old('next_of_kin', $patient->next_of_kin ?? '') }}">
          @error('next_of_kin') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
  
        <div class="col-md-6">
          <label for="relationship" class="form-label"><strong>RelationShip</strong></label>
          <select id="relationship" name="relationship" class="form-control select2 @error('relationship') is-invalid @enderror" data-placeholder="-- Select --">
            <option value=""></option>
            @foreach($relations as $relation)
            <option value="{{ $relation->id }}" {{ old('relationship', $patient->relationship ?? '') == $relation->id ? 'selected' :
              '' }}>
              {{ $relation->value }}
            </option>
            @endforeach
          </select>
          @error('relationship') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        </div>
        <div class="col-md-12">
          <label for="kin_contact_no" class="form-label"><strong>Contact Number</strong></label>
          <input type="text" name="kin_contact_no" id="kin_contact_no"
            class="form-control @error('kin_contact_no') is-invalid @enderror"
            value="{{ old('kin_contact_no', $patient->kin_contact_no ?? '') }}">
          @error('kin_contact_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-12">
          <label for="kin_email" class="form-label"><strong>Email</strong></label>
          <input type="email" name="kin_email" id="kin_email"
            class="form-control @error('kin_email') is-invalid @enderror"
            value="{{ old('kin_email', $patient->kin_email ?? '') }}">
          @error('kin_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
  
        <div class="mb-3">
          <label for="kin_address" class="form-label"><strong>Address</strong></label>
          <textarea name="kin_address" id="kin_address"
            class="form-control @error('kin_address') is-invalid @enderror"
            rows="2">{{ old('kin_address', $patient->kin_address ?? '') }}</textarea>
          @error('kin_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
  
        
    </div>
    </div>
    
  </div>

  {{-- ▶ Signature --}}
  <div class="col-12">
    <div class="card shadow-sm mb-3">
      <div class="card-header">
        <h5 class="card-title mb-0"><strong>Patient Signature</strong></h5>
      </div>
      <div class="card-body">
        <div class="row g-3 align-items-center">

          <!-- Draw Signature -->
          <div class="col-md-6">
            <label class="form-label"><strong>Draw Signature:</strong></label>
            <canvas id="signature-pad" width="400" height="150" style="border:1px solid #ccc;"></canvas>
            <input type="hidden" name="signature_draw" id="signature_draw">
            <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="clearCanvas()">Clear</button>
          </div>

          <!-- Upload Signature -->
          <div class="col-md-6">
            <label for="signature_file" class="form-label"><strong>Upload Signature:</strong></label>
            <input type="file" name="signature_file" id="signature_file" accept="image/*" class="form-control form-control-sm mb-2">

            <!-- Existing Signature -->
            @if(!empty($patient->patient_signature))
            <label class="form-label"><strong>Existing:</strong></label>
            <div>
              <img src="{{ asset('storage/' . $patient->patient_signature) }}" width="150" height="100" class="img-thumbnail">
            </div>
            @endif
          </div>

        </div>
      </div>
    </div>
  </div>



  {{-- ▶ Submit Button --}}
  <div class="col-12 text-center mb-5">
    <button type="submit" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-floppy-disk"></i> Submit
    </button>
  </div>

</div>