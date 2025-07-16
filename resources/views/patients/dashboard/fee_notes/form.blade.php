<!-- Fee Note Modal -->
<div class="modal fade" id="feeNoteModal" tabindex="-1" aria-labelledby="feeNoteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="feeForm">
        @csrf
        <input type="hidden" name="id" id="fee_note_id">
        <input type="hidden" name="patient_id" value="{{ $patient->id }}">

        <div class="modal-header">
          <h5 class="modal-title" id="feeNoteModalLabel">Add/Edit Fee Note</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-3">
              <label>Visit Date</label>
              <input type="date" name="visit_date" id="visit_date" class="form-control" required>
            </div>

            <div class="col-md-3">
              <label>Clinic</label>
              <select name="clinic_id" id="clinic_id" class="form-select" required>
                <option value="">-- Select --</option>
                @foreach($clinics as $clinic)
                  <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3">
              <label>Consultant</label>
              <select name="consultant_id" id="consultant_id" class="form-select">
                @foreach($consultants as $consultant)
                  <option value="{{ $consultant->id }}">{{ $consultant->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3">
              <label>Charge Code</label>
              <select name="chargecode_id" id="chargecode_id" class="form-select" required>
                @foreach($chargecodes as $code)
                  <option value="{{ $code->id }}" data-code="{{ json_encode($code) }}">{{ $code->code }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-2">
              <label>Qty</label>
              <input type="number" name="qty" id="qty" class="form-control" value="1">
            </div>

            <div class="col-md-2">
              <label>Gross</label>
              <input type="number" name="charge_gross" id="charge_gross" class="form-control">
            </div>

            <div class="col-md-2">
              <label>Reduction %</label>
              <input type="number" name="reduction_percent" id="reduction_percent" class="form-control">
            </div>

            <div class="col-md-2">
              <label>Net</label>
              <input type="number" name="charge_net" id="charge_net" class="form-control">
            </div>

            <div class="col-md-2">
              <label>VAT %</label>
              <input type="number" name="vat_rate_percent" id="vat_rate_percent" class="form-control">
            </div>

            <div class="col-md-2">
              <label>Total</label>
              <input type="number" name="line_total" id="line_total" class="form-control" readonly>
            </div>

            <div class="col-12">
              <label>Comment</label>
              <textarea name="comment" id="comment" class="form-control"></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>