@props(['clinics'])

<div class="modal fade" id="setCalendarDaysModal" tabindex="-1" aria-labelledby="setCalendarDaysModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary-light py-2">
                <h5 class="modal-title">Set Calendar Days</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-3">
                <div class="row">
                    <!-- LEFT: Clinic List -->
                    <div class="col-md-3 border-end">
                        <h6 class="fw-semibold mb-2">Clinics</h6>
                        @foreach($clinics as $clinic)
                        <div class="d-flex align-items-center mb-2" style="cursor:pointer;" onclick="selectClinic('{{ $clinic->name }}', '{{ $clinic->id }}')">
                            <div class="me-2 rounded-circle" style="width:12px; height:12px; background:{{ $clinic->color }};"></div>
                            <span>{{ $clinic->name }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- MIDDLE: Generated Dates -->
                    <div class="col-md-5 border-end">
                        <h6 class="fw-semibold mb-2">Dates</h6>
                        <table class="table table-sm table-bordered align-middle mb-0" id="dateTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Clinic</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <!-- RIGHT: Options -->
                    <div class="col-md-4">
                        <h6 class="fw-semibold mb-2">Options</h6>
                        <input type="hidden" id="selectedClinicId">

                        <div class="mb-2">
                            <label class="form-label mb-1">Clinic Name</label>
                            <input type="text" id="selectedClinic" class="form-control form-control-sm" readonly>
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1">Start Date</label>
                            <input type="text" id="startDate" class="form-control form-control-sm datetimepicker">
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1">Repeat</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="repeatType" value="weekly" checked>
                                <label class="form-check-label">Weekly</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="repeatType" value="fortnightly">
                                <label class="form-check-label">Fortnightly</label>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1">Number of Weeks</label>
                            <input type="number" id="repeatCount" class="form-control form-control-sm" min="1" value="5">
                        </div>

                        <button class="btn btn-success btn-sm w-100 mt-2" onclick="generateDates()">Generate
                            Dates</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="saveCalendarDays()">Save</button>
            </div>
        </div>
    </div>
</div>