@props(['clinics', 'appointmentTypes', 'flag', 'action','patient','patients'])

<div class="modal fade" id="bookAppointmentModal" tabindex="-1" aria-labelledby="bookAppointmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="bookAppointmentForm" data-action="{{ $action }}" >
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bookAppointmentLabel">Book Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="appointment-id">

                    <div class="row g-3">
                        @if ($patient)
                        <div class="col-md-6">
                            <input type="hidden" name="patient_id" id="patient-id" value="{{$patient->id??''}}">
                            <label class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="modal-patient-name" readonly>
                        </div>
                        @else 
                            <div class="col-md-6">
                                <label class="form-label">Select Patient</label>
                                <select class="select2" id="appointment-patient-id" name="patient_id" required style="width:100%">
                                    <option value="">-- Select Patient --</option>
                                    @foreach ($patients as $p)
                                        <option value="{{ $p->id }}"
                                            data-dob="{{ format_date($p->dob) }}">{{ $p->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <div class="input-group">
                                <input readonly id="modal-dob" type="text" class="form-control " placeholder="YYYY-MM-DD">
                                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="appointment_type" class="form-label">Appointment Type</label>
                            <select class="form-select" id="appointment_type" name="appointment_type">
                                @foreach($appointmentTypes as $id => $value)
                                <option value="{{ $id }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="dob" class="form-label"><strong>Appointment Date</strong></label>
                            <div class="input-group">
                                <input id="modal-appointment-date" name="appointment_date" type="text" class="form-control flatpickr @error('dob') is-invalid @enderror" placeholder="YYYY-MM-DD">
                                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Start Time</label>
                            <input type="text" class="form-control" id="start_time" name="start_time" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">End Time</label>
                            <input type="text" class="form-control" id="end_time" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Slots</label>
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
                        <textarea class="form-control" id="appointment_note" name="appointment_note" rows="3" placeholder="Patient needs"></textarea>
                    </div>
                    @if ($flag == 1)
                        <div class="col-md-6" >
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
                    @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="modal-submit-btn">Confirm Booking</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div> 