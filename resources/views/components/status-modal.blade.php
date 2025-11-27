@props(['diary_status','flag'])

<div class="modal fade" id="statusChangeModal" tabindex="-1" aria-hidden="true" aria-labelledby="statusChangeModalLabel">
    <div class="modal-dialog">
        <form id="statusChangeForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-info-light ">
                    <h5 class="modal-title" id="statusChangeModalLabel">Change Appointment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="appointment_id">
                    <input type="hidden" name="flag" id="flag" value="{{$flag}}">
                    <div class="mb-3">
                        <label for="appointment_status" class="form-label">Select Status:</label>
                        <select id="appointment_status" name="appointment_status" class="form-select">
                            @foreach($diary_status as $id => $value)
                            <option value="{{ $id }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>