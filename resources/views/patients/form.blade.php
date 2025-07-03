 {{-- Personal Information --}}
 <h4>Personal Information</h4>
 <div class="row mb-3">
     <div class="col-md-4">
         <label for="title_id" class="form-label"><strong>Title <span class="txt-error">*</span></strong></label>
         <select name="title_id" id="title_id" class="form-control">
             <option value="">-- Select Title --</option>
             @foreach($titles as $title)
                <option value="{{ $title->id }}" {{ old('title_id', $patient->title_id ?? '') == $title->id ? 'selected' : '' }}>
                    {{ $title->value }}
                </option>
             @endforeach
         </select>
         @error('title_id') <div class="text-danger">{{ $message }}</div> @enderror
     </div>

     <div class="col-md-4">
         <label for="surname" class="form-label"><strong>Surname <span class="txt-error">*</span></strong></label>
         <input type="text" name="surname" id="surname" class="form-control" value="{{ old('surname', $patient->surname ?? '') }}" placeholder="Surname">
         @error('surname') <div class="text-danger">{{ $message }}</div> @enderror
     </div>

     <div class="col-md-4">
         <label for="first_name" class="form-label"><strong>First Name <span class="txt-error">*</span></strong></label>
         <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name', $patient->first_name ?? '') }}" placeholder="First Name">
         @error('first_name') <div class="text-danger">{{ $message }}</div> @enderror
     </div>
 </div>

 <div class="row mb-3">
    <div class="col-md-4">
        <label for="dob" class="form-label"><strong>Date of Birth <span class="text-danger">*</span></strong></label>
        <div class="input-group">
            <input type="text" id="datepicker" name="dob" class="form-control" placeholder="YYYY-MM-DD" value="{{ old('dob', $patient->dob ?? '') }}">
            <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
          </div>
        @error('dob') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="gender" class="form-label"><strong>Gender <span class="txt-error">*</span></strong></label>
        <select name="gender" id="gender" class="form-control">
            <option value="">-- Select Gender --</option>
            <option value="Male" {{ old('gender', $patient->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender', $patient->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Other" {{ old('gender', $patient->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('gender') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

     <div class="col-md-4">
         <label for="doctor_id" class="form-label"><strong>Doctor <span class="txt-error">*</span></strong></label>
         <select name="doctor_id" id="doctor_id" class="form-control">
             <option value="">-- Select Doctor --</option>
             @foreach($doctors as $doctor)
                 <option value="{{ $doctor->id }}" {{ old('doctor_id', $patient->doctor_id ?? '') == $doctor->id ? 'selected' : '' }}>
                     Dr. {{ $doctor->first_name }} {{ $doctor->surname }}
                 </option>
             @endforeach
         </select>
         @error('doctor_id') <div class="text-danger">{{ $message }}</div> @enderror
     </div>
 </div>

 {{-- Contact Information --}}
 <h4>Contact Information</h4>
 <div class="row mb-3">
     <div class="col-md-4">
         <label for="phone" class="form-label"><strong>Phone <span class="txt-error">*</span></strong></label>
         <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $patient->phone ?? '') }}" placeholder="Phone Number">
         @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
     </div>

     <div class="col-md-4">
         <label for="email" class="form-label"><strong>Email <span class="txt-error">*</span></strong></label>
         <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $patient->email ?? '') }}" placeholder="Email Address">
         @error('email') <div class="text-danger">{{ $message }}</div> @enderror
     </div>

     <div class="col-md-4">
         <label for="preferred_contact_id" class="form-label"><strong>Preferred Contact Method </strong></label>
         <select name="preferred_contact_id" id="preferred_contact_id" class="form-control">
             <option value="">-- Select Contact Method --</option>
             @foreach($contactMethods as $method)
                 <option value="{{ $method->id }}" {{ old('preferred_contact_id', $patient->preferred_contact_id ?? '') == $method->id ? 'selected' : '' }}>
                     {{ $method->value }}
                 </option>
             @endforeach
         </select>
         @error('preferred_contact_id') <div class="text-danger">{{ $message }}</div> @enderror
     </div>
 </div>

 <div class="mb-3">
     <label for="address" class="form-label"><strong>Address <span class="txt-error">*</span></strong></label>
     <textarea name="address" id="address" class="form-control" rows="2" placeholder="Patient address">{{ old('address', $patient->address ?? '') }}</textarea>
     @error('address') <div class="text-danger">{{ $message }}</div> @enderror
 </div>

 {{-- Emergency Contact --}}
 <h4>Emergency Contact</h4>
 <div class="mb-3">
     <input type="text" name="emergency_contact" class="form-control" placeholder="Name & phone number" value="{{ old('emergency_contact', $patient->emergency_contact ?? '') }}">
     @error('emergency_contact') <div class="text-danger">{{ $message }}</div> @enderror
 </div>

 {{-- Medical Information --}}
 <h4>Medical Information</h4>
 <div class="mb-3">
     <label for="medical_history" class="form-label"><strong>Medical History / Notes</strong></label>
     <textarea name="medical_history" id="medical_history" class="form-control" rows="3" placeholder="Medical history or notes">{{ old('medical_history', $patient->medical_history ?? '') }}</textarea>
     @error('medical_history') <div class="text-danger">{{ $message }}</div> @enderror
 </div>

 <div class="mb-3">
     <label for="referral_reason" class="form-label"><strong>Referral Reason</strong></label>
     <textarea name="referral_reason" id="referral_reason" class="form-control" rows="2" placeholder="Referral reason">{{ old('referral_reason', $patient->referral_reason ?? '') }}</textarea>
     @error('referral_reason') <div class="text-danger">{{ $message }}</div> @enderror
 </div>

 <div class="mb-3">
     <label for="symptoms" class="form-label"><strong>Symptoms</strong></label>
     <textarea name="symptoms" id="symptoms" class="form-control" rows="2" placeholder="Symptoms">{{ old('symptoms', $patient->symptoms ?? '') }}</textarea>
     @error('symptoms') <div class="text-danger">{{ $message }}</div> @enderror
 </div>

 <div class="mb-3">
     <label for="patient_needs" class="form-label"><strong>Patient Needs</strong></label>
     <textarea name="patient_needs" id="patient_needs" class="form-control" rows="2" placeholder="Patient needs">{{ old('patient_needs', $patient->patient_needs ?? '') }}</textarea>
     @error('patient_needs') <div class="text-danger">{{ $message }}</div> @enderror
 </div>

 <div class="mb-3">
     <label for="allergies" class="form-label"><strong>Allergies</strong></label>
     <textarea name="allergies" id="allergies" class="form-control" rows="2" placeholder="Allergies">{{ old('allergies', $patient->allergies ?? '') }}</textarea>
     @error('allergies') <div class="text-danger">{{ $message }}</div> @enderror
 </div>

 <div class="mb-3">
     <label for="diagnosis" class="form-label"><strong>Diagnosis</strong></label>
     <textarea name="diagnosis" id="diagnosis" class="form-control" rows="2" placeholder="Diagnosis">{{ old('diagnosis', $patient->diagnosis ?? '') }}</textarea>
     @error('diagnosis') <div class="text-danger">{{ $message }}</div> @enderror
 </div>

 {{-- Insurance Information --}}
 <h4>Insurance Information</h4>
 <div class="row mb-3">
     <div class="col-md-4">
         <label for="insurance_id" class="form-label"><strong>Insurance Provider</strong></label>
         <select name="insurance_id" id="insurance_id" class="form-control">
             <option value="">-- Select Insurance --</option>
             @foreach($insurances as $insurance)
                 <option value="{{ $insurance->id }}" {{ old('insurance_id', $patient->insurance_id ?? '') == $insurance->id ? 'selected' : '' }}>
                     {{ $insurance->code }}
                 </option>
             @endforeach
         </select>
         @error('insurance_id') <div class="text-danger">{{ $message }}</div> @enderror
     </div>

     <div class="col-md-4">
         <label for="insurance_plan" class="form-label"><strong>Insurance Plan</strong></label>
         <input type="text" name="insurance_plan" id="insurance_plan" class="form-control" value="{{ old('insurance_plan', $patient->insurance_plan ?? '') }}" placeholder="Insurance Plan">
         @error('insurance_plan') <div class="text-danger">{{ $message }}</div> @enderror
     </div>

     <div class="col-md-4">
         <label for="policy_no" class="form-label"><strong>Policy Number</strong></label>
         <input type="text" name="policy_no" id="policy_no" class="form-control" value="{{ old('policy_no', $patient->insurance_plan ?? '') }}" placeholder="Policy Number">
         @error('policy_no') <div class="text-danger">{{ $message }}</div> @enderror
     </div>
 </div>

 {{-- RIP Section --}}
 <h4>Patient Status</h4>
 <div class="row mb-3 align-items-center">
     <div class="col-md-2">
         <div class="form-check">
             <input type="checkbox" name="rip" id="rip" class="form-check-input" value="1" {{ old('rip', $patient->rip ?? '') ? 'checked' : '' }}>
             <label for="rip" class="form-check-label"><strong>RIP</strong></label>
         </div>
     </div>

     <div class="col-md-4">
         <label for="rip_date" class="form-label"><strong>Date of RIP</strong></label>
         <div class="input-group">
            <input type="text" id="datepicker" name="rip_date" class="form-control" placeholder="YYYY-MM-DD" value="{{ old('rip_date', $patient->rip_date ?? '') }}">
            <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
          </div>
         @error('rip_date') <div class="text-danger">{{ $message }}</div> @enderror
     </div>
 </div>

 {{-- Consent Section --}}
 <h4>Consent</h4>
 <div class="row mb-3">
     <div class="col-md-3">
         <div class="form-check">
             <input type="checkbox" name="sms_consent" id="sms_consent" class="form-check-input" value="1" {{ old('sms_consent', $patient->sms_consent ?? '') ? 'checked' : '' }}>
             <label for="sms_consent" class="form-check-label">SMS Consent</label>
         </div>
     </div>

     <div class="col-md-3">
         <div class="form-check">
             <input type="checkbox" name="email_consent" id="email_consent" class="form-check-input" value="1" {{ old('email_consent', $patient->email_consent ?? '') ? 'checked' : '' }}>
             <label for="email_consent" class="form-check-label">Email Consent</label>
         </div>
     </div>
 </div>

{{-- Submit Button --}}
<div class="text-center mb-5">
    <button type="submit" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-floppy-disk"></i> Submit
    </button>
</div>