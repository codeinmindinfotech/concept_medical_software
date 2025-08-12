@props(['clinics', 'procedures', 'flag', 'action'])
<div class="modal fade" id="manualBookingModal" tabindex="-1" aria-labelledby="manualBookingLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"><!-- wider dialog -->
        <form id="manualBookingForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manualBookingLabel">Add Hospital Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="hospital-patient-id" name="patient_id" value="">
                    <input type="hidden" id="flag" name="flag" value="{{$flag}}">
                    @if ($flag == 0)
                        <input type="hidden" class="form-control" id="hospital-clinic-id" name="clinic_id">
                    @endif
                    <input type="hidden" class="form-control" id="hospital-appointment-id" name="hospital_id">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="hospital-patient-name" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <div class="input-group">
                                <input readonly id="hospital-dob" type="text" class="form-control " placeholder="YYYY-MM-DD">
                                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="hospital_appointment_date" class="form-label">Procedure Date</label>
                            <input type="date" class="form-control" id="hospital_appointment_date" name="appointment_date" required>
                        </div>

                        <div class="col-md-6">
                            <label for="hospital_start_time" class="form-label">Procedure Time</label>
                            <input type="time" class="form-control" id="hospital_start_time" name="start_time" required>
                        </div>

                        <div class="col-md-6">
                            <label for="admission_date" class="form-label">Admission Date</label>
                            <input type="date" class="form-control" id="admission_date" name="admission_date" required>
                        </div>

                        <div class="col-md-6">
                            <label for="admission_time" class="form-label">Admission Time</label>
                            <input type="time" class="form-control" id="admission_time" name="admission_time" required>
                        </div>

                        <div class="col-md-6">
                            <label for="procedure_id" class="form-label">Procedure</label>
                            <select class="form-select" id="procedure_id" name="procedure_id" required>
                                <option value="">Select Procedure</option>
                                @foreach($procedures as $procedure)
                                    <option value="{{ $procedure->id }}">{{ $procedure->code }} - {{ $procedure->description }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="operation_duration" class="form-label">Operation Duration (minutes)</label>
                            <input type="number" min="1" class="form-control" id="operation_duration" name="operation_duration" required>
                        </div>

                        <div class="col-md-6">
                            <label for="ward" class="form-label">Ward</label>
                            <input type="text" class="form-control" id="ward" name="ward" placeholder="Ward name or number">
                        </div>

                        <div class="col-md-6">
                            <label for="allergy" class="form-label">Allergy Information</label>
                            <input type="text" class="form-control" id="allergy" name="allergy" placeholder="Patient allergy details">
                        </div>

                        @if ($flag == 1)
                            <div class="col-md-6" >
                                <label for="hospital-clinic-id" class="form-label fw-semibold">Select Clinic:</label>
                                <select id="hospital-clinic-id" name="clinic_id" class="form-select shadow-sm">
                                    <option value="">-- Choose Clinic --</option>
                                    @foreach($clinics as $clinic)
                                    <option value="{{ $clinic->id }}">
                                        {{ $clinic->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Additional notes"></textarea>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="booking-submit-btn">Add Appointment</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>