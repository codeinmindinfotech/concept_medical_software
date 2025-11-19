@props(['id', 'title', 'clinics'])

<div class="modal fade" id="{{ $id ?? 'moveAppointmentModal' }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-primary py-3 px-4">
                <h5 class="modal-title d-flex align-items-center mb-0">
                    <i class="bi bi-calendar2-range me-2 fs-4"></i> {{ $title ?? 'Move Appointment' }}
                </h5>
                <button type="button" class="btn-close btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body bg-light p-4">
                <div class="row g-4">
                    <!-- Left Section -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100 hover-shadow">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-list-check me-2"></i> Select Appointment
                                </h6>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">From Clinic</label>
                                    <select id="fromClinic" class="form-select">
                                        <option value="">Select Clinic</option>
                                        @foreach ($clinics as $clinic)
                                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Appointment Date</label>
                                    <div class="cal-icon">
                                        <input type="text" id="fromDate" placeholder="Select Date" class="form-control datetimepicker" />
                                    </div>
                                </div>

                                <div id="fromDateDisplay" class="border rounded p-3  text-muted small shadow-sm">
                                    Please select a date.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100 hover-shadow">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-arrow-right-square me-2"></i> Move To Target Date
                                </h6>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">To Clinic</label>
                                    <select id="toClinic" class="form-select">
                                        <option value="">Select Clinic</option>
                                        @foreach ($clinics as $clinic)
                                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">New Date</label>
                                    <div class="cal-icon">
                                        <input type="text" id="toDate" placeholder="Select Target Date" class="form-control datetimepicker"  />
                                    </div>
                                </div>

                                <div id="timeSlotsForTarget" class="border rounded p-3  text-muted small shadow-sm">
                                    Please select a clinic and target date.
                                </div>

                                <input type="hidden" id="selectedSlot" name="selected_slot">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mt-4">
                    <label class="form-label fw-semibold text-primary">
                        <i class="bi bi-chat-left-text me-1"></i> Reason for Moving
                    </label>
                    <textarea id="moveReason" class="form-control shadow-sm rounded-3" rows="3"
                        placeholder="Explain why this appointment needs to be moved..."></textarea>
                </div>

                <!-- Submit Button -->
                <div class="mt-4 text-center">
                    <button class="btn btn-primary btn-lg px-5 shadow-sm rounded-pill" onclick="submitMoveAppointment()">
                        <i class="bi bi-arrow-repeat me-2"></i> Move Appointment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
