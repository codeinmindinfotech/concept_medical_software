@props(['clinics', 'appointmentTypes', 'flag', 'action','patient','patients'])

<div class="modal fade" id="bookAppointmentModal" tabindex="-1" aria-labelledby="bookAppointmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="bookAppointmentForm" data-action="{{ $action }}" class="needs-validation" novalidate>
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary-light">
                    <h5 class="modal-title" id="bookAppointmentLabel">Book Appointment </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="appointment-id">

                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <label class="form-label">Select Patient</label>
                            <select class="form-select select2" id="patient-id" name="patient_id" style="width:100%">
                                <option value="">-- Select Patient --</option>
                                @foreach ($patients as $p)
                                <option value="{{ $p->id }}" data-dob="{{ format_date($p->dob) }}" data-consultant="{{ $p->consultant->name }}">{{ $p->full_name }} ({{ format_date($p->dob) }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <div class="input-group">
                                <input readonly id="modal-dob" type="text" class="form-control " placeholder="YYYY-MM-DD">
                                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="appointment_type" class="form-label">Appointment Type<span class="txt-error">*</span></label>
                            <select class="form-select" id="appointment_type" name="appointment_type" required>
                                @foreach($appointmentTypes as $id => $value)
                                <option value="{{ $id }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="dob" class="form-label"><strong>Appointment Date<span class="txt-error">*</span></strong></label>
                            <div class="input-group">
                                <input id="modal-appointment-date" name="appointment_date" type="text" class="form-control  @error('dob') is-invalid @enderror" placeholder="YYYY-MM-DD" readonly>
                                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Start Time<span class="txt-error">*</span></label>
                            <input type="text" class="form-control" id="start_time" name="start_time" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">End Time<span class="txt-error">*</span></label>
                            <input type="text" class="form-control" id="end_time" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Slots<span class="txt-error">*</span></label>
                            <div id="slot-options">
                                @for ($i = 1; $i <= 10; $i++) <div class="form-check form-check-inline">
                                    <input class="form-check-input apt-slot-radio" type="radio" name="apt_slots" id="slot{{ $i }}" {{ $i==1 ? 'checked' : '' }} value="{{ $i }}">
                                    <label class="form-check-label" for="slot{{ $i }}">{{ $i }}</label>
                            </div>
                            @endfor
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Patient Need</label>
                        <textarea class="form-control" id="patient_need" name="patient_need" rows="3" placeholder="Patient needs"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Appointment Note</label>
                        <textarea class="form-control" id="appointment_note" name="appointment_note" rows="3" placeholder="Appointment Notes"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="clinic_consultant" class="form-label">Consultant</label>
                        <input type="text" id="clinic_consultant" class="form-control" readonly>
                    </div>
                    
                    @if ($flag == 1)
                    <div class="col-md-6">
                        <label for="appointment-clinic-id" class="form-label fw-semibold">Select Clinic:</label>
                        <select id="appointment-clinic-id" name="clinic_id" class="form-select shadow-sm">
                            <option value="">-- Choose Clinic --</option>
                            @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}">
                                {{ $clinic->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <input type="hidden" name="clinic_id" id="appointment-clinic-id">
                    @endif

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Send SMS to Patient</label>
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" value="1" name="sms_sent" id="sms_sent">
                            <label class="form-check-label" for="sms_sent">
                                Check to send SMS after booking
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <a href="#" class="btn btn-primary disabled" id="create-letter-btn">
                    <i class="fa-solid fa-file-lines me-1"></i> Create Letter
                </a>
                <button type="submit" class="btn btn-success" id="modal-submit-btn">Confirm Booking</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
