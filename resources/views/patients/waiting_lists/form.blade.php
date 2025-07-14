<!-- Edit Visit Modal -->
<div class="modal fade" id="editVisitModal" tabindex="-1" aria-labelledby="editVisitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editVisitModalLabel">Edit Visit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editVisitForm">
          @csrf
          <div class="mb-3">
            <label for="editVisitDate" class="form-label">Visit Date</label>
            <input type="date" class="form-control" id="editVisitDate" name="visit_date" required>
          </div>
          <div class="mb-3">
            <label for="note" class="form-label">Clinic</label>
            <select class="select2" id="editClinic" name="clinic_id">
                <option value="">-- Select Clinic --</option>
                @foreach($clinics as $clinic)
                  <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                @endforeach
            </select>
          </div>
          
          <div class="mb-3">
            <label for="editNote" class="form-label">Type Of appointment</label>
            <textarea class="form-control" id="editNote" name="consult_note" required></textarea>
          </div>
          <div class="mb-3">
            <label for="editCategory" class="form-label">Category</label>
            <select class="select2" id="editCategory" name="category_id" required>
              @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->value }}</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Add Visit Modal -->
<div class="modal fade" id="addVisitModal" tabindex="-1" aria-labelledby="addVisitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="addVisitModalLabel">Add Waiting List</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- DOB -->
        <div class="mb-3">
          <input type="hidden" id="patientId" value="{{ $patient->id??0 }}">

          <label for="visit_date" class="form-label"><strong>Visit Date<span class="txt-error">*</span></strong></label>
          <div class="input-group">
            <input id="visit_date" name="visit_date" type="text" class="form-control flatpickr @error('visit_date') is-invalid @enderror"
              placeholder="YYYY-MM-DD" >
            <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
            @error('visit_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="mb-3">
          <label for="note" class="form-label">Clinic</label>
          <select class="select2" id="clinic_id" name="clinic_id">
              <option value="">-- Select Clinic --</option>
              @foreach($clinics as $clinic)
                <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
              @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label for="note" class="form-label">Consult Note</label>
          <textarea class="form-control" id="note" name="consult_note"></textarea>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label"><strong>Category<span class="txt-error">*</span></strong></label>
            <select id="category_id" name="category_id" class="select2 @error('category_id') is-invalid @enderror">
              <option value="">-- Select Title --</option>
              @foreach($categories as $title)
                <option value="{{ $title->id }}" {{ old('category_id', $patient->category_id ?? '') == $title->id ? 'selected' :
                  '' }}>
                  {{ $title->value }}
                </option>
              @endforeach
            </select>
            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Visit</button>
      </div>
    </form>
  </div>
</div>




